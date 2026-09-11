<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Document;
use App\Services\Billing\BalanceService;
use App\Services\Signia\EngineRouter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Exception;

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

        $isDemo = $company->environment === 'demo';

        // 2. Descontar saldo con Bloqueo Pesimista (SOLO en ProducciÃ³n)
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
            // 3. Enrutar al Motor Correspondiente (PSE o Nativo)
            $engine = $this->engineRouter->resolve($company);
            $payload = $request->all();
            $result = $engine->process($company, $payload);

            if ((isset($result['status']) && $result['status'] === 'error') || (isset($result['success']) && $result['success'] === false)) {
                // Revertir saldo
                if (!$isDemo) {
                    $agency->increment($balanceField);
                }
                return response()->json($result, 500);
            }

            // 4. Guardar archivos localmente para trazabilidad (4 aÃ±os SUNAT)
            $fileNameBase = "{$ruc}-{$docType}-{$series}-{$number}";
            $pathPrefix = "documents/{$ruc}/" . date('Y/m');
            
            $xmlPath = null;
            if (!empty($result['xml_base64'])) {
                $xmlPath = "{$pathPrefix}/{$fileNameBase}.xml";
                Storage::disk('public')->put($xmlPath, base64_decode($result['xml_base64']));
            }
            
            $cdrPath = null;
            if (!empty($result['cdr_base64'])) {
                $cdrPath = "{$pathPrefix}/R-{$fileNameBase}.zip";
                Storage::disk('public')->put($cdrPath, base64_decode($result['cdr_base64']));
            }

            $pdfPath = null;
            if (!empty($result['pdf_base64'])) {
                $pdfPath = "{$pathPrefix}/{$fileNameBase}.pdf";
                Storage::disk('public')->put($pdfPath, base64_decode($result['pdf_base64']));
            }

            // 5. Guardar historial
            $document = Document::create([
                'agency_id' => $agency->id,
                'company_id' => $company->id,
                'document_type' => $docType,
                'serie' => $series,
                'number' => $number,
                'xml_hash' => $result['xml_hash'] ?? null,
                'status' => 'accepted',
                'ticket' => $result['ticket'] ?? null,
                'xml_path' => $xmlPath,
                'cdr_path' => $cdrPath,
                'pdf_path' => $pdfPath,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Documento procesado correctamente.',
                'data' => $document,
                'xml_base64' => $result['xml_base64'] ?? null,
                'cdr_base64' => $result['cdr_base64'] ?? null,
                'pdf_base64' => $result['pdf_base64'] ?? null,
                'ticket' => $result['ticket'] ?? null,
            ]);

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

        // Simulacion de respuesta de SUNAT para el ticket
        return response()->json([
            'success' => true,
            'message' => 'Consulta de ticket procesada correctamente',
            'status' => 'accepted',
            'cdr_base64' => 'UEsDBBQAAAAIA...' // CDR simulado
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

        // Map para agregar URLs de descarga absolutas
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
            'ruc' => 'required|string|size:11'
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

        // Lógica de reintento simulada para propósitos de la API B2B
        // Aquí se usaría el motor para reenviar o consultar el CDR pendiente.
        return response()->json([
            'success' => true,
            'message' => 'Reintento de envío o consulta encolado/ejecutado.',
            'data' => $document
        ]);
    }
}
