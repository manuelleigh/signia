<?php

$docsFile = 'resources/views/docs.blade.php';
$docs = file_get_contents($docsFile);

// EMITIR
$oldEmitirRespuesta = '<h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta exitosa (200)</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "success": true,
  "message": "Documento procesado correctamente.",
  "data": {
    "id": 15,
    "serie": "F001",
    "number": "1",
    "status": "accepted",
    "xml_path": "documents/20123456789/2026/09/20123456789-01-F001-1.xml",
    "cdr_path": "documents/20123456789/2026/09/R-20123456789-01-F001-1.zip",
    "pdf_path": "documents/20123456789/2026/09/20123456789-01-F001-1.pdf"
  },
  "xml_base64": "PD94bWwgdmVyc2lvbj0iMS4wIi4uLg==",
  "cdr_base64": "UEsDBBQAAAAI...",
  "pdf_base64": "JVBERi0xLjQu..."
}</code></pre>';

$newEmitirRespuesta = '<h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta exitosa (202 Accepted)</h3>
                    <div class="bg-indigo-50 border-l-4 border-indigo-400 p-4 mb-4">
                        <p class="text-indigo-800 font-semibold text-sm">Procesamiento Asíncrono</p>
                        <p class="text-indigo-700 text-sm mt-1">Para garantizar alta disponibilidad frente a las intermitencias de SUNAT, Signia encola tu comprobante inmediatamente. Debes usar el <strong>ticket</strong> devuelto para consultar el CDR y PDF finales.</p>
                    </div>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "success": true,
  "message": "Documento encolado correctamente. Consulte el estado con el ticket provisto.",
  "status": "in_process",
  "data": {
    "ticket": "SIG-A1B2C3D4E5F6"
  }
}</code></pre>';

$docs = str_replace($oldEmitirRespuesta, $newEmitirRespuesta, $docs);

// EMITIR CAMPOS RESPUESTA
$oldEmitirCampos = '<tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">success</code></td><td>boolean</td><td>Salud técnica del proceso en Signia. <code>true</code> = proceso completado.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">data.status</code></td><td>string</td><td>Veredicto de SUNAT: <code>accepted</code> (aceptado), <code>rejected</code> (rechazado), <code>exception</code> (error temporal).</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">xml_base64</code></td><td>string</td><td>XML UBL 2.1 firmado, codificado en Base64. Guárdalo para trazabilidad.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">cdr_base64</code></td><td>string</td><td>Constancia de Recepción de SUNAT (.zip), codificado en Base64.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">pdf_base64</code></td><td>string</td><td>Representación impresa del comprobante en PDF, codificado en Base64.</td></tr>';

$newEmitirCampos = '<tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">status</code></td><td>string</td><td>Siempre devolverá <code>in_process</code> al momento del envío.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">data.ticket</code></td><td>string</td><td>Identificador único del proceso en la cola de Signia.</td></tr>';

$docs = str_replace($oldEmitirCampos, $newEmitirCampos, $docs);


// CONSULTAR TICKET NOTA
$oldTicketNota = '<div class="bg-indigo-50 border-l-4 border-indigo-400 p-4 rounded-r-lg mb-6">
                        <p class="text-indigo-800 font-semibold text-sm">¿Cuándo necesito este endpoint?</p>
                        <p class="text-indigo-700 text-sm mt-1">Para <strong>facturas, boletas y notas de crédito/débito</strong>, el CDR llega en la respuesta inmediata de <code>/send</code>. Este endpoint solo es necesario para <strong>resúmenes diarios (RC)</strong>, <strong>comunicaciones de baja (RA)</strong> y <strong>guías de remisión</strong>, que SUNAT procesa de forma asíncrona.</p>
                    </div>';
$newTicketNota = '<div class="bg-indigo-50 border-l-4 border-indigo-400 p-4 rounded-r-lg mb-6">
                        <p class="text-indigo-800 font-semibold text-sm">Obligatorio en todo el flujo</p>
                        <p class="text-indigo-700 text-sm mt-1">Dado que Signia procesa asíncronamente para evitar <i>timeouts</i> con SUNAT, usarás este endpoint para obtener el XML, CDR y PDF finales de <strong>todos</strong> tus comprobantes usando el ticket retornado en <code>/send</code>.</p>
                    </div>';

$docs = str_replace($oldTicketNota, $newTicketNota, $docs);

// CONSULTAR TICKET RESPUESTA
$oldTicketRespuesta = '<h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta exitosa (200)</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "success": true,
  "message": "Consulta de ticket procesada correctamente",
  "status": "accepted",
  "cdr_base64": "UEsDBBQAAAAIAA..."
}</code></pre>';
$newTicketRespuesta = '<h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta exitosa (Completado)</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "success": true,
  "message": "Consulta procesada. El documento ya cuenta con resolución final.",
  "status": "accepted",
  "xml_url": "https://signia.kore.pe/storage/documents/20.../F001-1.xml",
  "cdr_url": "https://signia.kore.pe/storage/documents/20.../R-F001-1.zip",
  "pdf_url": "https://signia.kore.pe/storage/documents/20.../F001-1.pdf"
}</code></pre>
<h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta (Aún en Cola)</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "success": true,
  "status": "in_process",
  "message": "El documento sigue en cola de procesamiento local."
}</code></pre>';

$docs = str_replace($oldTicketRespuesta, $newTicketRespuesta, $docs);


// REINTENTAR ENDPOINT
$oldReintentar = '<section id="reintentar" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Reintentar Envío</h1>
                    <p class="mb-6 leading-relaxed">Si un documento se quedó en estado <code>exception</code> (por caídas temporales de SUNAT o problemas de conexión), puedes reencolarlo. Signia no descontará saldo si el documento ya fue cobrado previamente pero no logró terminar.</p>
                    <div class="endpoint-box"><span class="method-post">POST</span> /api/v1/documents/reintentar</div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Headers</h3>
                    <table>
                        <thead><tr><th>Header</th><th>Valor</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Authorization</code></td><td>Bearer {api_token}</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Content-Type</code></td><td>application/json</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Parámetros del Body</h3>
                    <table>
                        <thead><tr><th>Campo</th><th>Tipo</th><th>Requerido</th><th>Descripción</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">ruc</code></td><td>string(11)</td><td><strong>Sí</strong></td><td>RUC del emisor.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">serie</code></td><td>string</td><td><strong>Sí</strong></td><td>Serie del comprobante fallido.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">number</code></td><td>string</td><td><strong>Sí</strong></td><td>Correlativo del comprobante fallido.</td></tr>
                        </tbody>
                    </table>
                </section>';

$newReintentar = '<section id="reintentar" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Reintentar Envío</h1>
                    <p class="mb-6 leading-relaxed">Si un documento se quedó en estado <code>exception</code> (por caídas temporales de SUNAT) o <code>rejected</code>, puedes volver a intentar procesarlo. <strong>Debes enviar el payload JSON original completo</strong>.</p>
                    <div class="endpoint-box"><span class="method-post">POST</span> /api/v1/documents/reintentar</div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Parámetros del Body</h3>
                    <table>
                        <thead><tr><th>Campo</th><th>Tipo</th><th>Requerido</th><th>Descripción</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">ruc</code></td><td>string(11)</td><td><strong>Sí</strong></td><td>RUC del emisor.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">serie</code></td><td>string</td><td><strong>Sí</strong></td><td>Serie del comprobante fallido.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">number</code></td><td>string</td><td><strong>Sí</strong></td><td>Correlativo del comprobante fallido.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">payload</code></td><td>object</td><td><strong>Sí</strong></td><td>El objeto JSON completo que enviaste originalmente al <code>/send</code>.</td></tr>
                        </tbody>
                    </table>
                </section>';
$docs = str_replace($oldReintentar, $newReintentar, $docs);

file_put_contents($docsFile, $docs);
echo "Docs updated.";
