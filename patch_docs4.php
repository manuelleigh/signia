<?php

$docsFile = 'resources/views/docs.blade.php';
$docs = file_get_contents($docsFile);

// EMITIR: Update responses section to show both 200 and 202

$oldEmitirRespuesta = '<h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta exitosa (202 Accepted)</h3>
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

$newEmitirRespuesta = '<div class="bg-indigo-50 border-l-4 border-indigo-400 p-4 mb-6">
                        <p class="text-indigo-800 font-semibold text-sm">Arquitectura Híbrida Inteligente (Síncrona/Asíncrona)</p>
                        <p class="text-indigo-700 text-sm mt-1">Para Facturas y Boletas, intentamos responder de manera <strong>inmediata (Síncrona)</strong> con el XML y CDR. Sin embargo, si SUNAT sufre caídas temporales o envías un Resumen/Baja, Signia auto-encolará tu documento y responderá de forma <strong>Asíncrona</strong> con un <code>ticket</code> para que no pierdas la transacción.</p>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta Ideal (200 OK) <span class="text-xs bg-green-100 text-green-800 px-2 py-1 rounded ml-2">Facturas y Boletas</span></h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm mb-6"><code>{
  "success": true,
  "message": "Documento procesado correctamente.",
  "status": "accepted",
  "data": {
    "ticket": "SIG-X1Y2Z3...",
    "xml_url": "https://signia.kore.pe/storage/documents/20.../F001-1.xml",
    "cdr_url": "https://signia.kore.pe/storage/documents/20.../R-F001-1.zip",
    "pdf_url": "https://signia.kore.pe/storage/documents/20.../F001-1.pdf"
  },
  "xml_base64": "PD94bWwgdmVyc2lvbj0iMS4wIi4uLg==",
  "cdr_base64": "UEsDBBQAAAAI...",
  "pdf_base64": "JVBERi0xLjQu..."
}</code></pre>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta Asíncrona (202 Accepted) <span class="text-xs bg-yellow-100 text-yellow-800 px-2 py-1 rounded ml-2">Caídas SUNAT o Resúmenes</span></h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "success": true,
  "message": "Intermitencia con SUNAT. El documento ha sido encolado para reintentos automáticos. Use el ticket para consultar.",
  "status": "in_process",
  "data": {
    "ticket": "SIG-A1B2C3D4E5F6"
  }
}</code></pre>';

$docs = str_replace($oldEmitirRespuesta, $newEmitirRespuesta, $docs);

// UPDATE FIELDS

$oldEmitirCampos = '<tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">status</code></td><td>string</td><td>Siempre devolverá <code>in_process</code> al momento del envío.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">data.ticket</code></td><td>string</td><td>Identificador único del proceso en la cola de Signia.</td></tr>';

$newEmitirCampos = '<tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">status</code></td><td>string</td><td>Veredicto de SUNAT o estado de cola: <code>accepted</code>, <code>rejected</code>, o <code>in_process</code>.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">data.ticket</code></td><td>string</td><td>Identificador de la transacción en Signia.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">xml_base64</code></td><td>string</td><td>XML firmado en Base64 (solo en respuestas 200).</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">cdr_base64</code></td><td>string</td><td>Constancia de Recepción en Base64 (solo en respuestas 200).</td></tr>';

$docs = str_replace($oldEmitirCampos, $newEmitirCampos, $docs);


// UPDATE CONSULTAR TICKET NOTA

$oldTicketNota = '<div class="bg-indigo-50 border-l-4 border-indigo-400 p-4 rounded-r-lg mb-6">
                        <p class="text-indigo-800 font-semibold text-sm">Obligatorio en todo el flujo</p>
                        <p class="text-indigo-700 text-sm mt-1">Dado que Signia procesa asíncronamente para evitar <i>timeouts</i> con SUNAT, usarás este endpoint para obtener el XML, CDR y PDF finales de <strong>todos</strong> tus comprobantes usando el ticket retornado en <code>/send</code>.</p>
                    </div>';

$newTicketNota = '<div class="bg-indigo-50 border-l-4 border-indigo-400 p-4 rounded-r-lg mb-6">
                        <p class="text-indigo-800 font-semibold text-sm">¿Cuándo usar este endpoint?</p>
                        <p class="text-indigo-700 text-sm mt-1">Si enviaste un comprobante y Signia te devolvió código <code>200 OK</code>, ya tienes el CDR y XML, no necesitas llamar a este endpoint. Solo debes usar este endpoint si enviaste un Resumen/Baja, o si recibiste un código <code>202 Accepted</code> (Encolado por intermitencia de SUNAT).</p>
                    </div>';

$docs = str_replace($oldTicketNota, $newTicketNota, $docs);


file_put_contents($docsFile, $docs);
echo "Docs updated for hybrid.";
