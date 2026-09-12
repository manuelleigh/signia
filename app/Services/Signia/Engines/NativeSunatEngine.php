<?php

namespace App\Services\Signia\Engines;

use App\Contracts\EngineContract;
use App\Models\Company;
use App\Services\Signia\Core\Helpers\Xml\XmlFormat;
use App\Services\Signia\Core\WS\Signed\XmlSigned;
use App\Services\Signia\Core\WS\Client\WsClient;
use App\Services\Signia\Core\Helpers\Xml\CdrParser;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class NativeSunatEngine implements EngineContract
{
    public function process(Company $company, array $payload): array
    {
        try {
            // Auto-inyectar los datos de la empresa para evitar errores si el cliente solo envi?? el RUC
            $payload['company'] = array_merge([
                'ruc' => $company->ruc,
                'number' => $company->ruc,
                'name' => $company->business_name,
                'trade_name' => $company->business_name,
                'address' => '-',
                'ubigeo' => '150101',
            ], $payload['company'] ?? []);

            $docType = $payload['document']['document_type_id'] ?? '01';
            
            // 1. Determinar plantilla Blade
            $bladeView = $this->getBladeView($docType);
            
            // 2. Construir XML
            $view = View::make($bladeView, $payload);
            $xmlUnsigned = XmlFormat::format($view->render());

            // 3. Firmar XML
            $signer = new XmlSigned();
            if (!$company->certificate || empty($company->certificate->file_content)) {
                throw new \Exception("La empresa no tiene un certificado configurado.");
            }
            $signer->setCertificate($company->certificate->file_content);
            $xmlSigned = $signer->signXml($xmlUnsigned);

            // 4. Comprimir ZIP
            $zipName = $payload['company']['ruc'] . '-' . $docType . '-' . $payload['document']['series'] . '-' . $payload['document']['number'];
            $zipPath = sys_get_temp_dir() . '/' . $zipName . '.zip';
            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
                $zip->addFromString($zipName . '.xml', $xmlSigned);
                $zip->close();
            }
            $zipContent = file_get_contents($zipPath);
            @unlink($zipPath);

            // 5. Enviar vía SoapClient
            $isProd = $company->environment === 'production';
            $wsdl = $isProd 
                ? 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService?wsdl'
                : 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl';

            $ws = new WsClient($wsdl);
            $ws->setCredentials(
                $company->certificate->sol_user,
                decrypt($company->certificate->sol_password)
            );

            $params = [
                'fileName' => $zipName . '.zip',
                'contentFile' => $zipContent,
            ];

            // Si es Resumen o Baja, usar sendSummary
            if (in_array($docType, ['RC', 'RA'])) {
                $response = $ws->call('sendSummary', [$params]);
                $ticket = null;
                if (isset($response->ticket)) {
                    $ticket = $response->ticket;
                }

                return [
                    'success' => true,
                    'message' => 'Resumen/Baja enviado a SUNAT.',
                    'status' => 'in_process',
                    'xml_base64' => base64_encode($xmlSigned),
                    'ticket' => $ticket,
                ];
            }

            // Comprobantes normales
            $response = $ws->call('sendBill', [$params]);
            $cdrZip = null;
            $status = 'exception';
            $message = 'Enviado, pero sin CDR de respuesta.';
            $sunatCode = null;

            if (isset($response->applicationResponse)) {
                $cdrZip = $response->applicationResponse;
                
                // Analizar el CDR
                $parsedCdr = CdrParser::parseZip($cdrZip);
                $status = $parsedCdr['status'];
                $sunatCode = $parsedCdr['code'];
                $message = $parsedCdr['description'];
            }

            // 6. Generar PDF (Solo para comprobantes, no para resúmenes)
            $pdfContent = null;
            try {
                $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', $payload);
                $pdfContent = $pdf->output();
            } catch (\Exception $e) {
                Log::warning("No se pudo generar el PDF: " . $e->getMessage());
            }

            return [
                'success' => true, // La comunicación técnica funcionó
                'status' => $status, // Veredicto de SUNAT (accepted, rejected...)
                'message' => $message,
                'sunat_code' => $sunatCode,
                'xml_base64' => base64_encode($xmlSigned),
                'cdr_base64' => $cdrZip ? base64_encode($cdrZip) : null,
                'pdf_base64' => $pdfContent ? base64_encode($pdfContent) : null,
            ];

        } catch (\Exception $e) {
            Log::error("NativeSunatEngine Error: " . $e->getMessage());
            return [
                'success' => false,
                'status' => 'exception',
                'message' => 'Error en el Motor Nativo: ' . $e->getMessage()
            ];
        }
    }

    private function getBladeView(string $docType): string
    {
        return match ($docType) {
            '01', '03' => 'ubl21.invoice',
            '07' => 'ubl21.credit',
            '08' => 'ubl21.debit',
            'RC' => 'ubl21.summary',
            'RA' => 'ubl21.voided',
            '09' => 'ubl21.dispatch',
            default => 'ubl21.invoice',
        };
    }

    public function consult(Company $company, string $ticket): array
    {
        try {
            $isProd = $company->environment === 'production';
            $wsdl = $isProd
                ? 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billConsultService?wsdl'
                : 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billConsultService?wsdl';
            $serviceUrl = $isProd
                ? 'https://e-factura.sunat.gob.pe/ol-ti-itcpfegem/billService'
                : 'https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService';

            $ws = new WsClient('consultCdrStatus');
            $ws->setService($serviceUrl); 
            $ws->setCredentials(
                $company->certificate->sol_user,
                decrypt($company->certificate->sol_password)
            );

            $params = ['ticket' => $ticket];
            $response = $ws->call('getStatus', [$params]);

            if (isset($response->status->content)) {
                $cdrZip = $response->status->content;
                $parsedCdr = CdrParser::parseZip($cdrZip);

                return [
                    'success' => true,
                    'status' => $parsedCdr['status'],
                    'message' => $parsedCdr['description'],
                    'sunat_code' => $parsedCdr['code'],
                    'cdr_base64' => base64_encode($cdrZip),
                ];
            } elseif (isset($response->status->statusCode)) {
                // Puede que este en proceso todavia
                if ($response->status->statusCode == '98') {
                    return [
                        'success' => true,
                        'status' => 'in_process',
                        'message' => 'Ticket aún en proceso en SUNAT.',
                        'sunat_code' => '98',
                        'cdr_base64' => null,
                    ];
                }
            }

            return [
                'success' => false,
                'status' => 'exception',
                'message' => 'Respuesta inesperada de SUNAT al consultar ticket.',
            ];

        } catch (\Exception $e) {
            Log::error("NativeSunatEngine Consult Error: " . $e->getMessage());
            return [
                'success' => false,
                'status' => 'exception',
                'message' => 'Error de conexión con SUNAT: ' . $e->getMessage()
            ];
        }
    }
}
