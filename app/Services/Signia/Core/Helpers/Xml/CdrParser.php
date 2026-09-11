<?php

namespace App\Services\Signia\Core\Helpers\Xml;

use DOMDocument;
use Exception;
use ZipArchive;

class CdrParser
{
    /**
     * Extrae y analiza el CDR desde un string ZIP (Base64 decodificado).
     *
     * @param string $zipContent Contenido binario del ZIP
     * @return array
     */
    public static function parseZip(string $zipContent): array
    {
        $zipPath = sys_get_temp_dir() . '/' . uniqid('cdr_') . '.zip';
        file_put_contents($zipPath, $zipContent);

        $zip = new ZipArchive();
        $xmlContent = null;

        if ($zip->open($zipPath) === true) {
            for ($i = 0; $i < $zip->numFiles; $i++) {
                $filename = $zip->getNameIndex($i);
                if (pathinfo($filename, PATHINFO_EXTENSION) === 'xml') {
                    $xmlContent = $zip->getFromIndex($i);
                    break;
                }
            }
            $zip->close();
        }

        @unlink($zipPath);

        if (!$xmlContent) {
            return [
                'status' => 'exception',
                'code' => null,
                'description' => 'No se encontró archivo XML dentro del ZIP devuelto por SUNAT.'
            ];
        }

        return self::parseXml($xmlContent);
    }

    /**
     * Analiza el XML del CDR.
     *
     * @param string $xmlContent
     * @return array
     */
    public static function parseXml(string $xmlContent): array
    {
        $doc = new DOMDocument();
        // Suprimir advertencias si el XML tiene namespaces raros
        @$doc->loadXML($xmlContent);

        $responseCodeNodes = $doc->getElementsByTagName('ResponseCode');
        $descriptionNodes = $doc->getElementsByTagName('Description');

        $code = $responseCodeNodes->length > 0 ? $responseCodeNodes->item(0)->nodeValue : null;
        $description = $descriptionNodes->length > 0 ? $descriptionNodes->item(0)->nodeValue : 'Sin descripción';

        return [
            'status' => self::determineStatus($code),
            'code' => $code,
            'description' => $description
        ];
    }

    /**
     * Determina el estado del comprobante en base al código de respuesta de SUNAT.
     *
     * @param string|null $code
     * @return string
     */
    private static function determineStatus(?string $code): string
    {
        if ($code === null) {
            return 'exception';
        }

        $codeNum = (int) $code;

        // SUNAT acepta con código 0
        if ($codeNum === 0) {
            return 'accepted';
        }

        // Códigos de rechazo: entre 2000 y 3999
        if ($codeNum >= 2000 && $codeNum <= 3999) {
            return 'rejected';
        }

        // Códigos de observaciones: < 2000 o >= 4000
        return 'accepted_with_observations';
    }
}
