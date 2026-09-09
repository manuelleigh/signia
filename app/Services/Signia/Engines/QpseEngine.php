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
        // El token se guarda a nivel de compañía en su configuración o se usa el token master de la agencia.
        // Dado que la integración con QPSE requiere el Token Bearer que te proveen.
        $qpseEndpoint = config('services.qpse.endpoint', 'https://api.qpse.pe/v3/firmar-xml');
        $qpseToken = config('services.qpse.token', 'AQUI_IRÁ_EL_TOKEN_QUE_ME_BRINDARÁS');

        try {
            $response = \Illuminate\Support\Facades\Http::withToken($qpseToken)
                ->timeout(10)
                ->post($qpseEndpoint, $payload);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'message' => 'Enviado a QPSE exitosamente.',
                    'xml_base64' => $data['xml_base64'] ?? null,
                    'cdr_base64' => $data['cdr_base64'] ?? null,
                    'pdf_base64' => $data['pdf_base64'] ?? null,
                    'ticket' => $data['ticket'] ?? null,
                ];
            }

            return [
                'success' => false,
                'message' => 'Error de QPSE: ' . $response->body()
            ];

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("QpseEngine Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error de conexión con QPSE: ' . $e->getMessage()
            ];
        }
    }
}
