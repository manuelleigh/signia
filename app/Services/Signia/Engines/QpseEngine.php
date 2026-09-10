<?php

namespace App\Services\Signia\Engines;

use App\Contracts\EngineContract;
use App\Models\Company;
use Illuminate\Support\Facades\Http;
use Exception;

class QpseEngine implements EngineContract
{
    public function process(Company $company, array $payload): array
    {
        $isDemo = $company->environment === 'demo';
        
        $baseUrl = $isDemo ? 'https://demo-cpe.qpse.pe' : 'https://cpe.qpse.pe';
        $tokenUrl = $baseUrl . '/api/auth/cpe/token';
        // Asumiendo que el payload ya viene formateado para enviar, usamos el endpoint 'enviar'
        // o si es solo para generar XML usamos 'generar'. Para simplificar, usamos generar/enviar.
        // Asumiremos que el endpoint final es /api/cpe/generar basado en la petición normal.
        $sendUrl = $baseUrl . '/api/cpe/generar';

        try {
            // 1. Obtener Token CPE
            $tokenResponse = Http::post($tokenUrl, [
                'username' => $company->qpse_username,
                'password' => $company->qpse_password
            ]);

            if (!$tokenResponse->successful()) {
                return [
                    'success' => false,
                    'message' => 'Error de autenticación con el proveedor de firma: ' . $tokenResponse->body()
                ];
            }

            $tokenData = $tokenResponse->json();
            $cpeToken = $tokenData['token'] ?? null;

            if (!$cpeToken) {
                return [
                    'success' => false,
                    'message' => 'El proveedor de firma no devolvió un token válido.'
                ];
            }

            // 2. Enviar comprobante
            $response = Http::withToken($cpeToken)
                ->timeout(15)
                ->post($sendUrl, $payload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'message' => 'Procesado exitosamente.',
                    'xml_base64' => $data['xml_base64'] ?? null,
                    'cdr_base64' => $data['cdr_base64'] ?? null,
                    'pdf_base64' => $data['pdf_base64'] ?? null,
                    'ticket' => $data['ticket'] ?? null,
                ];
            }

            return [
                'success' => false,
                'message' => 'Error del proveedor de firma: ' . $response->body()
            ];

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("QpseEngine Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error de conexión con proveedor de firma: ' . $e->getMessage()
            ];
        }
    }
}
