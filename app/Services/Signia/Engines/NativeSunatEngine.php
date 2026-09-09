<?php

namespace App\Services\Signia\Engines;

use App\Contracts\EngineContract;
use App\Models\Company;
use App\Services\Signia\Core\Helpers\Xml\XmlFormat;
use App\Services\Signia\Core\WS\Signed\XmlSigned;
use App\Services\Signia\Core\WS\Client\WsClient;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;

class NativeSunatEngine implements EngineContract
{
    public function process(Company $company, array $payload): array
    {
        try {
            // 1. Build XML using Blade Template (Array Based)
            $view = View::make('ubl21.invoice', $payload);
            $xmlUnsigned = XmlFormat::format($view->render());

            // 2. Sign XML using XmlSigned & xmlseclibs
            $signer = new XmlSigned();
            // TODO: In production, load actual cert from $company->certificate->file_content
            // For now, if no cert is found, we throw.
            if (!$company->certificate || empty($company->certificate->file_content)) {
                throw new \Exception("La empresa no tiene un certificado configurado.");
            }
            $signer->setCertificate($company->certificate->file_content);
            $xmlSigned = $signer->signXml($xmlUnsigned);

            // 3. Compress ZIP
            $zipName = $payload['company']['ruc'] . '-' . $payload['document']['document_type_id'] . '-' . $payload['document']['series'] . '-' . $payload['document']['number'];
            $zipPath = sys_get_temp_dir() . '/' . $zipName . '.zip';
            $zip = new \ZipArchive();
            if ($zip->open($zipPath, \ZipArchive::CREATE) === TRUE) {
                $zip->addFromString($zipName . '.xml', $xmlSigned);
                $zip->close();
            }
            $zipContent = file_get_contents($zipPath);
            unlink($zipPath);

            // 4. Send via SoapClient
            // URL por defecto SUNAT BETA: https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService
            $ws = new WsClient('https://e-beta.sunat.gob.pe/ol-ti-itcpfegem-beta/billService?wsdl');
            $ws->setCredentials(
                $company->certificate->sol_user,
                decrypt($company->certificate->sol_password)
            );

            // Params for sendBill
            $params = [
                'fileName' => $zipName . '.zip',
                'contentFile' => $zipContent,
            ];

            $response = $ws->call('sendBill', [$params]);
            $cdrZip = null;
            if (isset($response->applicationResponse)) {
                $cdrZip = base64_encode($response->applicationResponse);
            }

            // 5. Generar PDF (Representación Impresa)
            $pdf = \Barryvdh\DomPDF\Facade\Pdf::loadView('pdf.invoice', $payload);
            $pdfContent = $pdf->output();

            return [
                'success' => true,
                'message' => 'Documento enviado a SUNAT exitosamente (Nativo)',
                'xml_base64' => base64_encode($xmlSigned),
                'cdr_base64' => $cdrZip,
                'pdf_base64' => base64_encode($pdfContent),
            ];

        } catch (\Exception $e) {
            Log::error("NativeSunatEngine Error: " . $e->getMessage());
            return [
                'success' => false,
                'message' => 'Error en el Motor Nativo: ' . $e->getMessage()
            ];
        }
    }
}
