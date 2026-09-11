<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Document;
use App\Services\Billing\BalanceService;
use App\Services\Signia\EngineRouter;
use App\Jobs\ProcessDocumentJob;
use Illuminate\Http\Request;
use Exception;
use Illuminate\Support\Str;

class DocumentController extends Controller
{
    public function __construct(
        protected BalanceService $balanceService,
        protected EngineRouter $engineRouter
    ) {}

    public function send(Request $request)
    {
        $request->validate([
            'company.ruc' => 'required|string|size:11',
            'document.document_type_id' => 'required|string',
            'document.series' => 'required|string',
            'document.number' => 'required|string',
        ]);

        $ruc = $request->input('company.ruc');
        $docType = $request->input('document.document_type_id');
        $series = $request->input('document.series');
        $number = $request->input('document.number');

        // 1. Identificar la empresa y la agencia
        $agency = $request->user()->agency;
        if (!$agency) {
            return response()->json(['error' => 'Agency not found'], 401);
        }

        $company = Company::where('ruc', $ruc)
            ->where('agency_id', $agency->id)
            ->first();

        if (!$company) {
            return response()->json(['error' => 'RUC no registrado bajo esta agencia.'], 404);
        }

        // Verificar si el documento ya existe para evitar cobros dobles
        $existingDoc = Document::where('company_id', $company->id)
                                ->where('document_type', $docType)
                                ->where('serie', $series)
                                ->where('number', $number)
                                ->first();
        if ($existingDoc && $existingDoc->status !== 'exception' && $existingDoc->status !== 'rejected') {
            return response()->json(['error' => 'El documento ya existe y está procesado o en cola.'], 422);
        }

        $isDemo = $company->environment === 'demo';

        // 2. Descontar saldo con Bloqueo Pesimista (SOLO en Producción)
        $engineType = $company->engine_type;
        $balanceField = $engineType === 'qpse' ? 'balance_qpse' : 'balance_native';

        if (!$isDemo) {
            try {
                $this->balanceService->deductBalance($agency->id, $engineType);
            } catch (Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
            }
        }

        try {
            $ticket = 'SIG-' . strtoupper(Str::random(12));
            
            $document = $existingDoc;
            if (!$document) {
                $document = Document::create([
                    'agency_id' => $agency->id,
                    'company_id' => $company->id,
                    'document_type' => $docType,
                    'serie' => $series,
                    'number' => $number,
                    'status' => 'in_process',
                    'ticket' => $ticket,
                ]);
            } else {
                $document->update([
                    'status' => 'in_process',
                    'ticket' => $ticket,
                ]);
            }

            // Si es un Resumen Diario o Baja, SIEMPRE va asíncrono
            if (in_array($docType, ['RC', 'RA'])) {
                ProcessDocumentJob::dispatch($document->id, $request->all(), $company->id, $agency->id);
                return response()->json([
                    'success' => true,
                    'message' => 'Resumen/Baja encolado correctamente. Consulte el estado con el ticket provisto.',
                    'status' => 'in_process',
                    'data' => [
                        'ticket' => $ticket,
                    ]
                ], 202);
            }

            // Para Facturas, Boletas y Notas, intentamos Síncrono primero
            try {
                $engine = $this->engineRouter->resolve($company);
                $result = $engine->process($company, $request->all());

                if ((isset($result['status']) && $result['status'] === 'exception' && empty($result['success'])) || (isset($result['success']) && $result['success'] === false)) {
                    throw new Exception($result['message'] ?? 'Error del motor');
                }

                // Guardar archivos
                $fileNameBase = "{$ruc}-{$docType}-{$series}-{$number}";
                $pathPrefix = "documents/{$ruc}/" . date('Y/m');

                $xmlPath = null;
                if (!empty($result['xml_base64'])) {
                    $xmlPath = "{$pathPrefix}/{$fileNameBase}.xml";
                    \Illuminate\Support\Facades\Storage::disk('public')->put($xmlPath, base64_decode($result['xml_base64']));
                }

                $cdrPath = null;
                if (!empty($result['cdr_base64'])) {
                    $cdrPath = "{$pathPrefix}/R-{$fileNameBase}.zip";
                    \Illuminate\Support\Facades\Storage::disk('public')->put($cdrPath, base64_decode($result['cdr_base64']));
                }

                $pdfPath = null;
                if (!empty($result['pdf_base64'])) {
                    $pdfPath = "{$pathPrefix}/{$fileNameBase}.pdf";
                    \Illuminate\Support\Facades\Storage::disk('public')->put($pdfPath, base64_decode($result['pdf_base64']));
                }

                $document->update([
                    'status' => $result['status'] ?? 'accepted',
                    'ticket' => $result['ticket'] ?? $ticket,
                    'xml_path' => $xmlPath,
                    'cdr_path' => $cdrPath,
                    'pdf_path' => $pdfPath,
                ]);

                return response()->json([
                    'success' => true,
                    'message' => 'Documento procesado correctamente.',
                    'status' => $document->status,
                    'data' => [
                        'ticket' => $document->ticket,
                        'xml_url' => $xmlPath ? url("storage/" . $xmlPath) : null,
                        'cdr_url' => $cdrPath ? url("storage/" . $cdrPath) : null,
                        'pdf_url' => $pdfPath ? url("storage/" . $pdfPath) : null,
                    ],
                    'xml_base64' => $result['xml_base64'] ?? null,
                    'cdr_base64' => $result['cdr_base64'] ?? null,
                    'pdf_base64' => $result['pdf_base64'] ?? null,
                ], 200);

            } catch (\Exception $engineEx) {
                // Si falla (timeout, error de SUNAT), lo mandamos a la cola para que se siga reintentando
                \Illuminate\Support\Facades\Log::warning("Proceso síncrono falló para doc {$document->id}, enviando a cola. Error: " . $engineEx->getMessage());
                ProcessDocumentJob::dispatch($document->id, $request->all(), $company->id, $agency->id);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Intermitencia con SUNAT. El documento ha sido encolado para reintentos automáticos. Use el ticket para consultar.',
                    'status' => 'in_process',
                    'data' => [
                        'ticket' => $ticket,
                    ]
                ], 202);
            }

        } catch (Exception $e) {
            // Revertir saldo
            if (!$isDemo) {
                $agency->increment($balanceField);
            }
            
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 400);
        }
    }

    public function consult(Request $request)
    {
        $request->validate([
            'ruc' => 'required|string|size:11',
            'ticket' => 'required|string'
        ]);

        $agency = $request->user()->agency;
        if (!$agency) {
            return response()->json(['success' => false, 'message' => 'Agencia no encontrada.'], 401);
        }

        $document = Document::whereHas('company', function($q) use ($request, $agency) {
            $q->where('ruc', $request->ruc)->where('agency_id', $agency->id);
        })
        ->where('ticket', $request->ticket)
        ->first();

        if (!$document) {
            return response()->json(['success' => false, 'message' => 'Ticket no encontrado.'], 404);
        }

        // Si ya está terminado a nivel de base de datos local
        if (in_array($document->status, ['accepted', 'accepted_with_observations', 'rejected'])) {
            return response()->json([
                'success' => true,
                'message' => 'Consulta procesada. El documento ya cuenta con resolución final.',
                'status' => $document->status,
                'cdr_url' => $document->cdr_path ? url("storage/" . $document->cdr_path) : null,
                'xml_url' => $document->xml_path ? url("storage/" . $document->xml_path) : null,
                'pdf_url' => $document->pdf_path ? url("storage/" . $document->pdf_path) : null,
            ]);
        }

        // Si es un documento enviado por sendSummary (Resumen/Baja) o si el motor en sí quedó pendiente
        if ($document->status === 'in_process') {
            try {
                // Consultamos con el motor directo (ya sea SUNAT o QPSE)
                $engine = $this->engineRouter->resolve($document->company);
                // Si el ticket es de Signia (arranca con SIG-), significa que el worker todavía no lo procesa.
                if (str_starts_with($document->ticket, 'SIG-')) {
                    return response()->json([
                        'success' => true,
                        'status' => 'in_process',
                        'message' => 'El documento sigue en cola de procesamiento local.',
                    ]);
                }
                
                // Si el ticket no es de Signia, es un ticket real de SUNAT o QPSE devuelto tras un sendSummary
                $result = $engine->consult($document->company, $document->ticket);
                
                if (isset($result['status']) && $result['status'] !== 'in_process' && $result['status'] !== 'exception') {
                    // Actualizar documento con los base64 si llegaron
                    $ruc = $document->company->ruc;
                    $fileNameBase = "{$ruc}-{$document->document_type}-{$document->serie}-{$document->number}";
                    $pathPrefix = "documents/{$ruc}/" . date('Y/m');
                    $cdrPath = $document->cdr_path;

                    if (!empty($result['cdr_base64'])) {
                        $cdrPath = "{$pathPrefix}/R-{$fileNameBase}.zip";
                        \Illuminate\Support\Facades\Storage::disk('public')->put($cdrPath, base64_decode($result['cdr_base64']));
                        $document->update(['cdr_path' => $cdrPath]);
                    }

                    $document->update(['status' => $result['status']]);
                }

                return response()->json([
                    'success' => $result['success'] ?? true,
                    'status' => $result['status'] ?? 'in_process',
                    'message' => $result['message'] ?? 'Consulta realizada.',
                    'cdr_base64' => $result['cdr_base64'] ?? null,
                ]);

            } catch (Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error al consultar motor: ' . $e->getMessage()
                ], 500);
            }
        }

        return response()->json([
            'success' => false,
            'status' => $document->status,
            'message' => 'El estado actual no permite consulta externa.',
        ]);
    }

    public function index(Request $request)
    {
        $agency = $request->user()->agency;
        if (!$agency) {
            return response()->json(['success' => false, 'message' => 'Agencia no encontrada.'], 401);
        }

        $query = Document::where('agency_id', $agency->id)->with('company:id,ruc,business_name');

        if ($request->has('ruc')) {
            $query->whereHas('company', function($q) use ($request) {
                $q->where('ruc', $request->ruc);
            });
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $documents = $query->latest()->paginate($request->per_page ?? 15);

        $documents->getCollection()->transform(function ($doc) {
            $doc->xml_url = $doc->xml_path ? url("storage/" . $doc->xml_path) : null;
            $doc->cdr_url = $doc->cdr_path ? url("storage/" . $doc->cdr_path) : null;
            $doc->pdf_url = $doc->pdf_path ? url("storage/" . $doc->pdf_path) : null;
            return $doc;
        });

        return response()->json([
            'success' => true,
            'data' => $documents
        ]);
    }

    public function retry(Request $request)
    {
        $request->validate([
            'serie' => 'required|string',
            'number' => 'required|string',
            'ruc' => 'required|string|size:11',
            'payload' => 'required|array' // Necesitamos el payload original
        ]);

        $agency = $request->user()->agency;
        if (!$agency) {
            return response()->json(['success' => false, 'message' => 'Agencia no encontrada.'], 401);
        }

        $document = Document::whereHas('company', function($q) use ($request, $agency) {
            $q->where('ruc', $request->ruc)->where('agency_id', $agency->id);
        })
        ->where('serie', $request->serie)
        ->where('number', $request->number)
        ->first();

        if (!$document) {
            return response()->json(['success' => false, 'message' => 'Documento no encontrado.'], 404);
        }

        if (!in_array($document->status, ['exception', 'rejected'])) {
            return response()->json(['success' => false, 'message' => 'Solo se pueden reintentar comprobantes fallidos o en excepción.'], 422);
        }

        $company = $document->company;
        $isDemo = $company->environment === 'demo';
        $engineType = $company->engine_type;
        $balanceField = $engineType === 'qpse' ? 'balance_qpse' : 'balance_native';

        // Si fue rejected o exception y no se descontó saldo (o se devolvió), cobramos de nuevo
        if (!$isDemo) {
            try {
                $this->balanceService->deductBalance($agency->id, $engineType);
            } catch (Exception $e) {
                return response()->json(['status' => 'error', 'message' => $e->getMessage()], 400);
            }
        }

        $ticket = 'SIG-' . strtoupper(Str::random(12));
        $document->update([
            'status' => 'in_process',
            'ticket' => $ticket
        ]);

        ProcessDocumentJob::dispatch($document->id, $request->payload, $company->id, $agency->id);

        return response()->json([
            'success' => true,
            'message' => 'Reintento de envío encolado correctamente.',
            'data' => [
                'ticket' => $ticket
            ]
        ], 202);
    }
}
