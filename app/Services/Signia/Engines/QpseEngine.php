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

        // LÃ³gica de ProducciÃ³n Real
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
                    'message' => 'Error de autenticaciÃ³n con el proveedor de firma: ' . $tokenResponse->body()
                ];
            }

            $tokenData = $tokenResponse->json();
            $cpeToken = $tokenData['token'] ?? null;

            if (!$cpeToken) {
                return [
                    'success' => false,
                    'message' => 'El proveedor de firma no devolviÃ³ un token vÃ¡lido.'
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
                'message' => 'Error de conexiÃ³n con proveedor de firma: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Motor exhaustivo de validaciÃ³n simulado con mensajes amigables y sugerencias (Beta).
     */
    private function processMockDemo(Company $company, array $payload): array
    {
        $errors = [];
        
        // DOCUMENTO
        if (empty($payload['document']['document_type_id'])) {
            $errors[] = '[document_type_id]: Es obligatorio. Debes indicar quÃ© tipo de comprobante es (Ej: "01" para Factura, "03" para Boleta, "07" para Nota de CrÃ©dito).';
        }
        if (empty($payload['document']['series'])) {
            $errors[] = '[series]: Es obligatorio. Debes indicar la serie del comprobante (Ej: "F001" o "B001").';
        }
        if (empty($payload['document']['number'])) {
            $errors[] = '[number]: Es obligatorio. Debes indicar el correlativo numÃ©rico (Ej: "1", "123").';
        }
        if (empty($payload['document']['currency_type_id'])) {
            $errors[] = '[currency_type_id]: Es obligatorio. Indica la moneda (Ej: "PEN" para Soles o "USD" para DÃ³lares).';
        }

        // CLIENTE
        if (empty($payload['customer']['identity_document_type_id'])) {
            $errors[] = '[customer.identity_document_type_id]: Es obligatorio. Indica el tipo de documento del cliente (Ej: "6" para RUC, "1" para DNI).';
        }
        if (empty($payload['customer']['number'])) {
            $errors[] = '[customer.number]: Es obligatorio. Ingresa el nÃºmero de RUC o DNI del cliente.';
        } elseif (strlen($payload['customer']['number']) !== 11 && ($payload['customer']['identity_document_type_id'] ?? '') === '6') {
            $errors[] = '[customer.number]: Como indicaste el tipo "6" (RUC), el nÃºmero debe tener exactamente 11 dÃ­gitos.';
        }
        if (empty($payload['customer']['name'])) {
            $errors[] = '[customer.name]: Es obligatorio. Coloca la RazÃ³n Social o el nombre completo de tu cliente.';
        }

        // ITEMS
        if (empty($payload['items']) || !is_array($payload['items'])) {
            $errors[] = '[items]: Debes enviar un arreglo de productos o servicios. Â¡Un comprobante no puede estar vacÃ­o!';
        } else {
            $calcTotal = 0;
            foreach ($payload['items'] as $index => $item) {
                if (empty($item['internal_id'])) {
                    $errors[] = "Item [$index]: Falta 'internal_id'. Agrega un cÃ³digo de producto (Ej: 'PROD-01').";
                }
                if (empty($item['description'])) {
                    $errors[] = "Item [$index]: Falta 'description'. Â¿QuÃ© producto estÃ¡s vendiendo?";
                }
                if (empty($item['unit_type_id'])) {
                    $errors[] = "Item [$index]: Falta 'unit_type_id'. Agrega la unidad de medida segÃºn SUNAT (Ej: 'NIU' para bienes, 'ZZ' para servicios).";
                }
                if (!isset($item['quantity']) || $item['quantity'] <= 0) {
                    $errors[] = "Item [$index]: 'quantity' debe ser mayor a 0.";
                }
                if (!isset($item['unit_value'])) {
                    $errors[] = "Item [$index]: Falta 'unit_value' (Precio unitario sin IGV).";
                }
                if (!isset($item['total_igv'])) {
                    $errors[] = "Item [$index]: Falta 'total_igv' (Monto total del IGV de este producto).";
                }
                if (!isset($item['total_value'])) {
                    $errors[] = "Item [$index]: Falta 'total_value' (Valor total del producto sin IGV).";
                }
                
                // Sugerencia de matemÃ¡ticas
                if (isset($item['unit_value']) && isset($item['quantity']) && isset($item['total_value'])) {
                    $expectedTotal = round(($item['unit_value'] * $item['quantity']) + ($item['total_igv'] ?? 0), 2);
                    if (abs($item['total_value'] - $expectedTotal) > 0.5) {
                        $errors[] = "Item [$index]: MatemÃ¡ticas incorrectas. El 'total_value' que enviaste ({$item['total_value']}) no coincide con (unit_value * quantity) (esperado: {$expectedTotal}).";
                    }
                    $calcTotal += $item['total_value'];
                }
            }
        }

        // TOTALES GLOBALES
        if (!isset($payload['document']['total_taxed'])) {
            $errors[] = '[total_taxed]: Falta el total de las operaciones gravadas (Subtotal sin IGV).';
        }
        if (!isset($payload['document']['total_igv'])) {
            $errors[] = '[total_igv]: Falta el monto total del IGV (Generalmente el 18% del total gravado).';
        }
        if (!isset($payload['document']['total'])) {
            $errors[] = '[total]: Falta el monto total final del comprobante.';
        } elseif (isset($calcTotal) && abs($payload['document']['total'] - $calcTotal) > 0.5) {
            $errors[] = "[total]: El total global de la factura ({$payload['document']['total']}) no coincide con la suma de los totales de los Ã­tems ({$calcTotal}). Revisa tus cÃ¡lculos.";
        }

        // DEVOLVER ERRORES ESTRUCTURADOS SI LOS HAY
        if (count($errors) > 0) {
            return [
                'success' => false,
                'message' => 'La estructura de tu comprobante tiene problemas. Por favor, corrige las siguientes observaciones para cumplir con SUNAT:',
                'errors' => $errors // Array estructurado para que el cliente lo lea fÃ¡cil
            ];
        }

        // 2. Simulador de Respuesta Exitosa (Mock)
        $docId = ($payload['document']['series'] ?? 'F001') . '-' . ($payload['document']['number'] ?? '1');
        $dummyXml = '<?xml version="1.0" encoding="utf-8"?><Invoice xmlns="urn:oasis:names:specification:ubl:schema:xsd:Invoice-2"><cbc:ID>' . $docId . '</cbc:ID><cbc:CustomizationID>2.0</cbc:CustomizationID><cbc:UBLVersionID>2.1</cbc:UBLVersionID><cac:AccountingSupplierParty><cac:Party><cac:PartyLegalEntity><cbc:RegistrationName><![CDATA[' . $company->business_name . ']]></cbc:RegistrationName></cac:PartyLegalEntity></cac:Party></cac:AccountingSupplierParty></Invoice>';
        $dummyCdrZip = base64_encode('PK' . chr(3) . chr(4) . '... MOCK CDR SUNAT ... ' . $docId);
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
            'message' => 'Â¡Felicidades! Tu estructura es perfecta. Procesado exitosamente en Motor de Pruebas.',
            'xml_base64' => base64_encode($dummyXml),
            'cdr_base64' => $dummyCdrZip,
            'pdf_base64' => $dummyPdf,
            'ticket' => 'MOCK-' . time(),
        ];
    }

    public function consult(Company $company, string $ticket): array
    {
        $isDemo = $company->environment === 'demo';
        
        if ($isDemo) {
            return [
                'success' => true,
                'status' => 'accepted',
                'message' => 'Consulta de ticket MOCK procesada exitosamente.',
                'cdr_base64' => base64_encode('PK' . chr(3) . chr(4) . '... MOCK CDR SUNAT ... '),
            ];
        }

        $baseUrl = 'https://cpe.qpse.pe';
        $tokenUrl = $baseUrl . '/api/auth/cpe/token';
        $consultUrl = $baseUrl . '/api/cpe/consultar-ticket';

        try {
            $tokenResponse = Http::post($tokenUrl, [
                'username' => $company->qpse_username,
                'password' => $company->qpse_password
            ]);

            if (!$tokenResponse->successful()) {
                return [
                    'success' => false,
                    'status' => 'exception',
                    'message' => 'Error autenticando con proveedor de firma'
                ];
            }

            $cpeToken = $tokenResponse->json('token');

            $response = Http::withToken($cpeToken)
                ->timeout(15)
                ->post($consultUrl, [
                    'ticket' => $ticket
                ]);

            if ($response->successful()) {
                $data = $response->json();
                return [
                    'success' => true,
                    'status' => $data['status'] ?? 'accepted', // Asumiendo que QPSE devuelve un status
                    'message' => 'Consulta procesada.',
                    'cdr_base64' => $data['cdr_base64'] ?? null,
                ];
            }

            return [
                'success' => false,
                'status' => 'exception',
                'message' => 'Error del proveedor de firma al consultar ticket: ' . $response->body()
            ];

        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error("QpseEngine Consult Error: " . $e->getMessage());
            return [
                'success' => false,
                'status' => 'exception',
                'message' => 'Error de conexiÃ³n con proveedor de firma: ' . $e->getMessage()
            ];
        }
    }
}

