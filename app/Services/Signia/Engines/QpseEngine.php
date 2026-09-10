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

        if ($isDemo) {
            return $this->processMockDemo($company, $payload);
        }

        // Lógica de Producción Real
        $baseUrl = 'https://cpe.qpse.pe';
        $tokenUrl = $baseUrl . '/api/auth/cpe/token';
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

    /**
     * Motor exhaustivo de validación simulado para no consumir la API de QPSE durante Beta/Demo.
     */
    private function processMockDemo(Company $company, array $payload): array
    {
        // 1. Validación exhaustiva de estructura de comprobante
        $errors = [];
        
        if (empty($payload['document']['document_type_id'])) {
            $errors[] = 'Falta document.document_type_id (Ej: 01 para Factura, 03 para Boleta).';
        }
        if (empty($payload['document']['series'])) {
            $errors[] = 'Falta document.series (Ej: F001).';
        }
        if (empty($payload['document']['number'])) {
            $errors[] = 'Falta document.number (Ej: 123).';
        }
        if (empty($payload['customer']['identity_document_type_id'])) {
            $errors[] = 'Falta customer.identity_document_type_id (Ej: 6 para RUC).';
        }
        if (empty($payload['customer']['number'])) {
            $errors[] = 'Falta customer.number (Ej: 20123456789).';
        }
        if (empty($payload['customer']['name'])) {
            $errors[] = 'Falta customer.name (Razón social del cliente).';
        }
        if (empty($payload['document']['currency_type_id'])) {
            $errors[] = 'Falta document.currency_type_id (Ej: PEN o USD).';
        }
        
        // Items
        if (empty($payload['items']) || !is_array($payload['items'])) {
            $errors[] = 'Falta el arreglo de items, o no contiene productos.';
        } else {
            foreach ($payload['items'] as $index => $item) {
                if (!isset($item['internal_id'])) $errors[] = "Item $index: Falta internal_id.";
                if (!isset($item['description'])) $errors[] = "Item $index: Falta description.";
                if (!isset($item['unit_type_id'])) $errors[] = "Item $index: Falta unit_type_id (Ej: NIU o ZZ).";
                if (!isset($item['quantity'])) $errors[] = "Item $index: Falta quantity.";
                if (!isset($item['unit_value'])) $errors[] = "Item $index: Falta unit_value.";
                if (!isset($item['unit_price'])) $errors[] = "Item $index: Falta unit_price.";
                if (!isset($item['total'])) $errors[] = "Item $index: Falta total.";
                if (!isset($item['total_taxes'])) $errors[] = "Item $index: Falta total_taxes.";
            }
        }

        // Totales
        if (!isset($payload['document']['total_taxed'])) $errors[] = 'Falta document.total_taxed.';
        if (!isset($payload['document']['total_igv'])) $errors[] = 'Falta document.total_igv.';
        if (!isset($payload['document']['total'])) $errors[] = 'Falta document.total.';

        if (count($errors) > 0) {
            return [
                'success' => false,
                'message' => 'Errores de validación estructural (Signia MOCK): ' . implode(' | ', $errors)
            ];
        }

        // 2. Simulador de Respuesta Exitosa (Mock)
        // XML Simulando el firmado UBL 2.1
        $docId = $payload['document']['series'] . '-' . $payload['document']['number'];
        $dummyXml = '<?xml version="1.0" encoding="utf-8"?><Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"><cbc:ID>' . $docId . '</cbc:ID><cbc:CustomizationID>2.0</cbc:CustomizationID><cbc:UBLVersionID>2.1</cbc:UBLVersionID><cac:AccountingSupplierParty><cac:Party><cac:PartyLegalEntity><cbc:RegistrationName><![CDATA[' . $company->business_name . ']]></cbc:RegistrationName></cac:PartyLegalEntity></cac:Party></cac:AccountingSupplierParty></Invoice>';
        
        // CDR (Zip) Simulado (Base64)
        // Este es un zip genérico de un CDR de SUNAT (vacío, solo la cabecera MOCK)
        $dummyCdrZip = base64_encode('PK' . chr(3) . chr(4) . '... MOCK CDR SUNAT ... ' . $docId);
        
        // PDF Simulado
        $dummyPdf = base64_encode('%PDF-1.4
1 0 obj
<< /Type /Catalog /Pages 2 0 R >>
endobj
2 0 obj
<< /Type /Pages /Kids [3 0 R] /Count 1 >>
endobj
3 0 obj
<< /Type /Page /Parent 2 0 R /MediaBox [0 0 612 792] >>
endobj
xref
0 4
0000000000 65535 f 
0000000009 00000 n 
0000000058 00000 n 
0000000115 00000 n 
trailer
<< /Size 4 /Root 1 0 R >>
startxref
199
%%EOF');

        return [
            'success' => true,
            'message' => 'Procesado exitosamente (Validación MOCK Local).',
            'xml_base64' => base64_encode($dummyXml),
            'cdr_base64' => $dummyCdrZip,
            'pdf_base64' => $dummyPdf,
            'ticket' => 'MOCK-' . time(),
        ];
    }
}
