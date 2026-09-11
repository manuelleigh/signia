<?php

$controllerFile = 'app/Http/Controllers/Api/DocumentController.php';
$content = file_get_contents($controllerFile);

$oldSend = '        try {
            // 3. Crear registro inicial en DB
            $ticket = \'SIG-\' . strtoupper(Str::random(12));
            
            $document = $existingDoc;
            if (!$document) {
                $document = Document::create([
                    \'agency_id\' => $agency->id,
                    \'company_id\' => $company->id,
                    \'document_type\' => $docType,
                    \'serie\' => $series,
                    \'number\' => $number,
                    \'status\' => \'in_process\',
                    \'ticket\' => $ticket,
                ]);
            } else {
                $document->update([
                    \'status\' => \'in_process\',
                    \'ticket\' => $ticket,
                ]);
            }

            // 4. Encolar el procesamiento
            ProcessDocumentJob::dispatch($document->id, $request->all(), $company->id, $agency->id);

            return response()->json([
                \'success\' => true,
                \'message\' => \'Documento encolado correctamente. Consulte el estado con el ticket provisto.\',
                \'status\' => \'in_process\',
                \'data\' => [
                    \'ticket\' => $ticket,
                ]
            ], 202);

        } catch (Exception $e) {';

$newSend = '        try {
            $ticket = \'SIG-\' . strtoupper(Str::random(12));
            
            $document = $existingDoc;
            if (!$document) {
                $document = Document::create([
                    \'agency_id\' => $agency->id,
                    \'company_id\' => $company->id,
                    \'document_type\' => $docType,
                    \'serie\' => $series,
                    \'number\' => $number,
                    \'status\' => \'in_process\',
                    \'ticket\' => $ticket,
                ]);
            } else {
                $document->update([
                    \'status\' => \'in_process\',
                    \'ticket\' => $ticket,
                ]);
            }

            // Si es un Resumen Diario o Baja, SIEMPRE va asíncrono
            if (in_array($docType, [\'RC\', \'RA\'])) {
                ProcessDocumentJob::dispatch($document->id, $request->all(), $company->id, $agency->id);
                return response()->json([
                    \'success\' => true,
                    \'message\' => \'Resumen/Baja encolado correctamente. Consulte el estado con el ticket provisto.\',
                    \'status\' => \'in_process\',
                    \'data\' => [
                        \'ticket\' => $ticket,
                    ]
                ], 202);
            }

            // Para Facturas, Boletas y Notas, intentamos Síncrono primero
            try {
                $engine = $this->engineRouter->resolve($company);
                $result = $engine->process($company, $request->all());

                if ((isset($result[\'status\']) && $result[\'status\'] === \'exception\' && empty($result[\'success\'])) || (isset($result[\'success\']) && $result[\'success\'] === false)) {
                    throw new Exception($result[\'message\'] ?? \'Error del motor\');
                }

                // Guardar archivos
                $fileNameBase = "{$ruc}-{$docType}-{$series}-{$number}";
                $pathPrefix = "documents/{$ruc}/" . date(\'Y/m\');

                $xmlPath = null;
                if (!empty($result[\'xml_base64\'])) {
                    $xmlPath = "{$pathPrefix}/{$fileNameBase}.xml";
                    \Illuminate\Support\Facades\Storage::disk(\'public\')->put($xmlPath, base64_decode($result[\'xml_base64\']));
                }

                $cdrPath = null;
                if (!empty($result[\'cdr_base64\'])) {
                    $cdrPath = "{$pathPrefix}/R-{$fileNameBase}.zip";
                    \Illuminate\Support\Facades\Storage::disk(\'public\')->put($cdrPath, base64_decode($result[\'cdr_base64\']));
                }

                $pdfPath = null;
                if (!empty($result[\'pdf_base64\'])) {
                    $pdfPath = "{$pathPrefix}/{$fileNameBase}.pdf";
                    \Illuminate\Support\Facades\Storage::disk(\'public\')->put($pdfPath, base64_decode($result[\'pdf_base64\']));
                }

                $document->update([
                    \'status\' => $result[\'status\'] ?? \'accepted\',
                    \'ticket\' => $result[\'ticket\'] ?? $ticket,
                    \'xml_path\' => $xmlPath,
                    \'cdr_path\' => $cdrPath,
                    \'pdf_path\' => $pdfPath,
                ]);

                return response()->json([
                    \'success\' => true,
                    \'message\' => \'Documento procesado correctamente.\',
                    \'status\' => $document->status,
                    \'data\' => [
                        \'ticket\' => $document->ticket,
                        \'xml_url\' => $xmlPath ? url("storage/" . $xmlPath) : null,
                        \'cdr_url\' => $cdrPath ? url("storage/" . $cdrPath) : null,
                        \'pdf_url\' => $pdfPath ? url("storage/" . $pdfPath) : null,
                    ],
                    \'xml_base64\' => $result[\'xml_base64\'] ?? null,
                    \'cdr_base64\' => $result[\'cdr_base64\'] ?? null,
                    \'pdf_base64\' => $result[\'pdf_base64\'] ?? null,
                ], 200);

            } catch (\Exception $engineEx) {
                // Si falla (timeout, error de SUNAT), lo mandamos a la cola para que se siga reintentando
                \Illuminate\Support\Facades\Log::warning("Proceso síncrono falló para doc {$document->id}, enviando a cola. Error: " . $engineEx->getMessage());
                ProcessDocumentJob::dispatch($document->id, $request->all(), $company->id, $agency->id);
                
                return response()->json([
                    \'success\' => true,
                    \'message\' => \'Intermitencia con SUNAT. El documento ha sido encolado para reintentos automáticos. Use el ticket para consultar.\',
                    \'status\' => \'in_process\',
                    \'data\' => [
                        \'ticket\' => $ticket,
                    ]
                ], 202);
            }

        } catch (Exception $e) {';

$content = str_replace($oldSend, $newSend, $content);

file_put_contents($controllerFile, $content);
echo "DocumentController updated.";
