<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Models\Document;
use App\Services\Billing\BalanceService;
use App\Services\Signia\EngineRouter;
use Illuminate\Http\Request;
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

            // 4. Guardar historial
            $document = Document::create([
                'agency_id' => $agency->id,
                'company_id' => $company->id,
                'document_type' => $docType,
                'serie' => $series,
                'number' => $number,
                'xml_hash' => $result['xml_hash'] ?? null,
                'status' => 'accepted',
                'ticket' => $result['ticket'] ?? null,
                'cdr_path' => $result['cdr_url'] ?? null,
            ]);

            return response()->json([
                'status' => 'success',
                'message' => 'Documento procesado correctamente.',
                'data' => $document
            ]);

        } catch (Exception $e) {
            // Revertir saldo
            if (!$isDemo) {
                $agency->increment($balanceField);
            }
            
            return response()->json([
                'status' => 'error',
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
}
