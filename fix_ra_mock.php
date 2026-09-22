<?php
$file = 'app/Services/Signia/Engines/QpseEngine.php';
$content = file_get_contents($file);

// We need to bypass the strict validation for RA and RC in processMockDemo
$search = 'if (empty($payload[\'document\'][\'document_type_id\'])) {';

$replace = 'if (in_array($payload[\'document\'][\'document_type_id\'] ?? \'\', [\'RA\', \'RC\'])) {
            return [
                \'success\' => true,
                \'message\' => \'??Felicidades! Resumen de Baja/Diario procesado exitosamente en Motor de Pruebas.\',
                \'xml_base64\' => base64_encode(\'<?xml version="1.0"?><VoidedDocuments></VoidedDocuments>\'),
                \'ticket\' => \'MOCK-\' . time(),
            ];
        }

        if (empty($payload[\'document\'][\'document_type_id\'])) {';

$content = str_replace($search, $replace, $content);
file_put_contents($file, $content);
echo "DONE";
