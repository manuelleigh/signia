<?php
$file = 'resources/views/docs.blade.php';
$content = file_get_contents($file);

// ============================================================
// 1. FIX #empezando — inject "Empezando" section before #entornos
// ============================================================
$old_entornos = '<!-- ENTORNOS SECTION -->
                <section id="entornos" class="mb-20 pt-8">';

$new_empezando_plus_entornos = '<!-- EMPEZANDO SECTION -->
                <section id="empezando" class="mb-20 pt-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Empezando</h1>
                    <p class="mb-4 leading-relaxed">Bienvenido a la documentación oficial de <strong>Signia API v1</strong>. Esta API te permite integrar facturación electrónica peruana (SUNAT) directamente desde tu software, con soporte para facturas, boletas, notas de crédito/débito y más.</p>
                    <p class="mb-6 leading-relaxed text-gray-600">La integración completa requiere 3 pasos:</p>

                    <div class="grid gap-4 mb-8">
                        <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <span class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">1</span>
                            <div>
                                <p class="font-semibold text-gray-900">Obtener un API Token</p>
                                <p class="text-sm text-gray-600 mt-1">Genera tu token estático desde el Dashboard de Signia, o solicita uno dinámico mediante el endpoint <code class="bg-gray-100 text-blue-600 px-1 py-0.5 rounded text-xs">/auth/token</code>. El token dinámico expira en 600 segundos.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <span class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">2</span>
                            <div>
                                <p class="font-semibold text-gray-900">Registrar tu empresa RUC</p>
                                <p class="text-sm text-gray-600 mt-1">Crea la empresa en Signia mediante el endpoint <code class="bg-gray-100 text-blue-600 px-1 py-0.5 rounded text-xs">/empresa/crear</code> o desde el panel web. Todas las emisiones se asocian a un RUC registrado.</p>
                            </div>
                        </div>
                        <div class="flex items-start gap-4 p-4 bg-gray-50 rounded-lg border border-gray-200">
                            <span class="flex-shrink-0 w-8 h-8 bg-blue-600 text-white rounded-full flex items-center justify-center font-bold text-sm">3</span>
                            <div>
                                <p class="font-semibold text-gray-900">Emitir comprobantes</p>
                                <p class="text-sm text-gray-600 mt-1">Envía el JSON del comprobante al endpoint <code class="bg-gray-100 text-blue-600 px-1 py-0.5 rounded text-xs">/documents/send</code>. Signia arma el XML UBL 2.1, lo firma y lo envía a SUNAT, devolviendo el CDR y el PDF en la misma respuesta.</p>
                            </div>
                        </div>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-3 mt-8">Autenticación</h3>
                    <p class="mb-4 text-gray-600">Todos los endpoints protegidos requieren el header:</p>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm mb-4"><code>Authorization: Bearer {tu_api_token}</code></pre>
                    <p class="text-sm text-gray-500">Puedes generar o revocar tokens desde el Dashboard en <strong>Configuración &rarr; API Tokens</strong>.</p>
                </section>

                <!-- ENTORNOS SECTION -->
                <section id="entornos" class="mb-20 pt-8 border-t border-gray-100">';

$content = str_replace($old_entornos, $new_empezando_plus_entornos, $content);

// ============================================================
// 2. OBTENER TOKEN — full professional rewrite
// ============================================================
$old_token_section = '<!-- OBTENER TOKEN SECTION -->
                <section id="obtener-token" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Obtener Token</h1>
                    <p class="mb-6 leading-relaxed">Permite obtener el <code class="bg-gray-100 text-blue-600 px-1 py-0.5 rounded text-sm">access_token</code> para los procesos. Puedes generar un token estático desde el dashboard, o solicitar uno dinámico que expira en 600 segundos enviando tus credenciales.</p>
                    
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Endpoint</h3>
                    <div class="endpoint-box">
                        <span class="method-post">POST</span>
                        <span class="text-gray-800">/auth/token</span>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Body</h3>
                    <table>
                        <thead>
                            <tr><th>Campo</th><th>Tipo</th><th>Descripción</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">email</code></td>
                                <td>string</td>
                                <td>Correo electrónico de la agencia.</td>
                            </tr>
                            <tr>
                                <td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">password</code></td>
                                <td>string</td>
                                <td>Contraseña de la agencia.</td>
                            </tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta exitosa (200)</h3>
                    <div class="bg-gray-50 border border-gray-200 rounded-lg p-4 overflow-x-auto text-sm font-mono text-gray-800 mb-8">
<pre>{
  "access_token": "1|r4pw6Cqo9NyNJHsu...",
  "expires_in": 600
}</pre>
                    </div>
                </section>';

$new_token_section = '<!-- OBTENER TOKEN SECTION -->
                <section id="obtener-token" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Obtener Token</h1>
                    <p class="mb-4 leading-relaxed">Genera un <code class="bg-gray-100 text-blue-600 px-1 py-0.5 rounded text-sm">access_token</code> dinámico con vigencia de 600 segundos (10 minutos) usando las credenciales de la agencia. Ideal para integración B2B donde el ERP del cliente solicita un token fresco antes de cada emisión.</p>
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 rounded-r-lg mb-6">
                        <p class="text-blue-800 font-semibold text-sm">Alternativa: Token Estático</p>
                        <p class="text-blue-700 text-sm mt-1">También puedes generar un token permanente desde el Dashboard en <strong>Configuración &rarr; API Tokens</strong>. Ese token no expira y es ideal para pruebas o integraciones simples.</p>
                    </div>

                    <div class="endpoint-box">
                        <span class="method-post">POST</span>
                        <span class="text-gray-800">/auth/token</span>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Headers</h3>
                    <table>
                        <thead><tr><th>Header</th><th>Valor</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Content-Type</code></td><td>application/json</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Accept</code></td><td>application/json</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Parámetros del Body</h3>
                    <table>
                        <thead><tr><th>Campo</th><th>Tipo</th><th>Requerido</th><th>Descripción</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">email</code></td><td>string</td><td><strong>Sí</strong></td><td>Correo electrónico de la cuenta de la agencia en Signia.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">password</code></td><td>string</td><td><strong>Sí</strong></td><td>Contraseña de la cuenta de la agencia.</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Ejemplo de Request</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "email": "agencia@ejemplo.com",
  "password": "miPassword123"
}</code></pre>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta exitosa (200)</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "access_token": "1|r4pw6Cqo9NyNJHsu7aBcDeFgHiJkLmNoPqRsTuVwXyZ",
  "expires_in": 600
}</code></pre>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Campos de la respuesta</h3>
                    <table>
                        <thead><tr><th>Campo</th><th>Tipo</th><th>Descripción</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">access_token</code></td><td>string</td><td>Bearer token a usar en el header <code>Authorization</code> de todos los demás endpoints.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">expires_in</code></td><td>integer</td><td>Segundos de vigencia del token. Pasados 600 segundos, el token caduca y deberás solicitar uno nuevo.</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Errores posibles</h3>
                    <table>
                        <thead><tr><th>HTTP</th><th>Mensaje</th><th>Causa</th></tr></thead>
                        <tbody>
                            <tr><td>401</td><td>Credenciales incorrectas</td><td>El email o la contraseña no coinciden con ninguna cuenta de agencia.</td></tr>
                            <tr><td>422</td><td>The email field is required</td><td>Falta el campo <code>email</code> en el body.</td></tr>
                            <tr><td>422</td><td>The password field is required</td><td>Falta el campo <code>password</code> en el body.</td></tr>
                        </tbody>
                    </table>
                </section>';

$content = str_replace($old_token_section, $new_token_section, $content);

// ============================================================
// 3. EMITIR COMPROBANTE — add headers, full response, errors
// ============================================================
$old_emitir_end = '                </section>

                <!-- CONSULTAR TICKET SECTION -->';

$new_emitir_addition = '
                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Headers requeridos</h3>
                    <table>
                        <thead><tr><th>Header</th><th>Valor</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Authorization</code></td><td>Bearer {api_token}</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Content-Type</code></td><td>application/json</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Accept</code></td><td>application/json</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Parámetros completos del Body</h3>
                    <table>
                        <thead><tr><th>Campo</th><th>Tipo</th><th>Req.</th><th>Descripción</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">company.ruc</code></td><td>string(11)</td><td><strong>Sí</strong></td><td>RUC de la empresa emisora. Debe estar registrada en Signia.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">document.document_type_id</code></td><td>string(2)</td><td><strong>Sí</strong></td><td>Tipo de comprobante: <code>01</code> Factura, <code>03</code> Boleta, <code>07</code> Nota de Crédito, <code>08</code> Nota de Débito.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">document.series</code></td><td>string</td><td><strong>Sí</strong></td><td>Serie del comprobante. Facturas: <code>F001</code>. Boletas: <code>B001</code>. Electrónicas: <code>FF01</code> / <code>FB01</code>.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">document.number</code></td><td>string</td><td><strong>Sí</strong></td><td>Número correlativo del comprobante. Ejemplo: <code>1</code>, <code>125</code>.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">document.currency_type_id</code></td><td>string(3)</td><td><strong>Sí</strong></td><td>Moneda ISO 4217: <code>PEN</code> (soles), <code>USD</code> (dólares).</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">document.date_of_issue</code></td><td>string</td><td>No</td><td>Fecha de emisión en formato <code>YYYY-MM-DD</code>. Por defecto: fecha actual.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">customer.number</code></td><td>string</td><td><strong>Sí</strong></td><td>DNI (8 dígitos) o RUC (11 dígitos) del cliente receptor.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">customer.name</code></td><td>string</td><td><strong>Sí</strong></td><td>Nombre o razón social del receptor.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">items[].description</code></td><td>string</td><td><strong>Sí</strong></td><td>Descripción del producto o servicio.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">items[].quantity</code></td><td>number</td><td><strong>Sí</strong></td><td>Cantidad del ítem.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">items[].unit_value</code></td><td>number</td><td><strong>Sí</strong></td><td>Valor unitario <strong>sin IGV</strong>.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">items[].igv_percentage</code></td><td>number</td><td>No</td><td>Porcentaje de IGV. Por defecto: <code>18</code>.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">items[].unit_type_id</code></td><td>string</td><td>No</td><td>Código de unidad de medida SUNAT. Ejemplo: <code>NIU</code> (unidad), <code>ZZ</code> (servicio). Por defecto: <code>NIU</code>.</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta exitosa (200)</h3>
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
}</code></pre>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Campos de la respuesta</h3>
                    <table>
                        <thead><tr><th>Campo</th><th>Tipo</th><th>Descripción</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">success</code></td><td>boolean</td><td>Salud técnica del proceso en Signia. <code>true</code> = proceso completado.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">data.status</code></td><td>string</td><td>Veredicto de SUNAT: <code>accepted</code> (aceptado), <code>rejected</code> (rechazado), <code>exception</code> (error temporal).</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">xml_base64</code></td><td>string</td><td>XML UBL 2.1 firmado, codificado en Base64. Guárdalo para trazabilidad.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">cdr_base64</code></td><td>string</td><td>Constancia de Recepción de SUNAT (.zip), codificado en Base64.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">pdf_base64</code></td><td>string</td><td>Representación impresa del comprobante en PDF, codificado en Base64.</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Errores posibles</h3>
                    <table>
                        <thead><tr><th>HTTP</th><th>Mensaje</th><th>Causa</th></tr></thead>
                        <tbody>
                            <tr><td>401</td><td>Unauthenticated</td><td>Token inválido, expirado o no enviado.</td></tr>
                            <tr><td>400</td><td>Saldo insuficiente</td><td>La agencia no tiene firmas disponibles en su bolsa.</td></tr>
                            <tr><td>404</td><td>RUC no registrado bajo esta agencia</td><td>El RUC enviado no existe en Signia para la agencia autenticada.</td></tr>
                            <tr><td>422</td><td>The company.ruc field is required</td><td>Falta alguno de los campos obligatorios en el body.</td></tr>
                            <tr><td>500</td><td>Error del motor de firma</td><td>Fallo al comunicarse con el proveedor de firmas o SUNAT. Reintentar con <code>/documents/reintentar</code>.</td></tr>
                        </tbody>
                    </table>
                </section>

                <!-- CONSULTAR TICKET SECTION -->';

$content = str_replace($old_emitir_end, $new_emitir_addition, $content);

// ============================================================
// 4. CONSULTAR TICKET — add headers, request example, response, errors
// ============================================================
$old_ticket = '<!-- CONSULTAR TICKET SECTION -->
                <section id="consultar-ticket" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Consultar ticket</h1>
                    <div class="bg-indigo-50 border border-indigo-100 p-4 rounded-lg mb-6">
                        <p class="text-sm text-indigo-900 font-medium">Nota: Para facturas, boletas y notas, el CDR se obtiene de forma síncrona en la respuesta de <code>/send</code>. La consulta de ticket solo aplica para resúmenes diarios, comunicaciones de baja y guías de remisión.</p>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Endpoint</h3>
                    <div class="endpoint-box">
                        <span class="method-post">POST</span>
                        <span class="text-gray-800">/documents/consult</span>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Body</h3>
                    <table>
                        <thead>
                            <tr><th>Campo</th><th>Tipo</th><th>Descripción</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">ruc</code></td>
                                <td>string(11)</td>
                                <td>RUC emisor.</td>
                            </tr>
                            <tr>
                                <td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">ticket</code></td>
                                <td>string</td>
                                <td>Número de ticket devuelto por SUNAT.</td>
                            </tr>
                        </tbody>
                    </table>
                </section>';

$new_ticket = '<!-- CONSULTAR TICKET SECTION -->
                <section id="consultar-ticket" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Consultar Ticket</h1>
                    <p class="mb-4 leading-relaxed">Consulta el resultado de procesamiento asíncrono de SUNAT mediante el número de ticket. SUNAT entrega un ticket cuando el proceso no puede resolverse de manera sincrónica (ej. resúmenes diarios, bajas masivas, guías de remisión).</p>
                    <div class="bg-indigo-50 border-l-4 border-indigo-400 p-4 rounded-r-lg mb-6">
                        <p class="text-indigo-800 font-semibold text-sm">¿Cuándo necesito este endpoint?</p>
                        <p class="text-indigo-700 text-sm mt-1">Para <strong>facturas, boletas y notas de crédito/débito</strong>, el CDR llega en la respuesta inmediata de <code>/send</code>. Este endpoint solo es necesario para <strong>resúmenes diarios (RC)</strong>, <strong>comunicaciones de baja (RA)</strong> y <strong>guías de remisión</strong>, que SUNAT procesa de forma asíncrona.</p>
                    </div>

                    <div class="endpoint-box">
                        <span class="method-post">POST</span>
                        <span class="text-gray-800">/documents/consult</span>
                    </div>

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
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">ruc</code></td><td>string(11)</td><td><strong>Sí</strong></td><td>RUC de la empresa emisora del comprobante.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">ticket</code></td><td>string</td><td><strong>Sí</strong></td><td>Número de ticket devuelto por SUNAT en la respuesta del <code>/send</code> original (campo <code>data.ticket</code>).</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Ejemplo de Request</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "ruc": "20123456789",
  "ticket": "2026091012345678"
}</code></pre>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta exitosa (200)</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "success": true,
  "message": "Consulta de ticket procesada correctamente",
  "status": "accepted",
  "cdr_base64": "UEsDBBQAAAAIAA..."
}</code></pre>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Campos de la respuesta</h3>
                    <table>
                        <thead><tr><th>Campo</th><th>Tipo</th><th>Descripción</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">status</code></td><td>string</td><td>Resultado de SUNAT: <code>accepted</code>, <code>rejected</code>, o <code>in_process</code> (aún en cola).</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">cdr_base64</code></td><td>string|null</td><td>CDR de respuesta en Base64. <code>null</code> si el ticket todavía está en proceso.</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Errores posibles</h3>
                    <table>
                        <thead><tr><th>HTTP</th><th>Mensaje</th><th>Causa</th></tr></thead>
                        <tbody>
                            <tr><td>422</td><td>The ruc field is required</td><td>Falta alguno de los campos obligatorios.</td></tr>
                            <tr><td>404</td><td>RUC no encontrado</td><td>El RUC no pertenece a la agencia autenticada.</td></tr>
                            <tr><td>500</td><td>Error al consultar el ticket</td><td>SUNAT no respondió. Reintentar en unos segundos.</td></tr>
                        </tbody>
                    </table>
                </section>';

$content = str_replace($old_ticket, $new_ticket, $content);

// ============================================================
// 5. INTERPRETACION — expand with full flow table
// ============================================================
$old_interpretacion = '<!-- INTERPRETACION SECTION -->
                <section id="interpretacion" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Interpretación rápida</h1>
                    <p class="mb-6 leading-relaxed">Tabla de interpretación para las respuestas que retornan los endpoints de emisión y consulta.</p>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Definiciones</h3>
                    <table>
                        <thead>
                            <tr><th>Campo</th><th>Tipo</th><th>Descripción</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">success</code></td>
                                <td>boolean</td>
                                <td>Salud técnica del proceso interno en Signia.</td>
                            </tr>
                            <tr>
                                <td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">status</code></td>
                                <td>string</td>
                                <td>Veredicto de SUNAT (<code>accepted</code>, <code>rejected</code>, <code>exception</code>).</td>
                            </tr>
                        </tbody>
                    </table>
                </section>';

$new_interpretacion = '<!-- INTERPRETACION SECTION -->
                <section id="interpretacion" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Interpretación rápida</h1>
                    <p class="mb-6 leading-relaxed">Guía de referencia para interpretar correctamente las respuestas de Signia y actuar en consecuencia en tu sistema.</p>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Campos clave</h3>
                    <table>
                        <thead><tr><th>Campo</th><th>Tipo</th><th>Descripción</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">success</code></td><td>boolean</td><td>Indica si Signia procesó correctamente la solicitud técnicamente. <code>true</code> no garantiza que SUNAT aceptó el comprobante.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">data.status</code></td><td>string</td><td>Veredicto de SUNAT sobre el comprobante.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">xml_base64</code></td><td>string</td><td>XML UBL 2.1 firmado y validado. Guárdalo siempre (obligatorio por SUNAT por 4 años).</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">cdr_base64</code></td><td>string</td><td>Constancia de Recepción de SUNAT. Prueba legal de que el comprobante fue presentado.</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Estados de SUNAT y qué hacer</h3>
                    <table>
                        <thead><tr><th>status</th><th>Significado</th><th>Acción recomendada</th></tr></thead>
                        <tbody>
                            <tr>
                                <td><span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">accepted</span></td>
                                <td>SUNAT aceptó el comprobante sin observaciones.</td>
                                <td>Guardar XML, CDR y PDF. Mostrar al usuario como emitido.</td>
                            </tr>
                            <tr>
                                <td><span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-amber-50 text-amber-700 border border-amber-200">accepted_with_observations</span></td>
                                <td>SUNAT aceptó el comprobante pero con advertencias no bloqueantes.</td>
                                <td>Guardar archivos. Revisar las observaciones en el CDR para corregir en futuros comprobantes.</td>
                            </tr>
                            <tr>
                                <td><span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-red-50 text-red-700 border border-red-200">rejected</span></td>
                                <td>SUNAT rechazó el comprobante por un error de datos (RUC inválido, montos incorrectos, etc.).</td>
                                <td>Corregir los datos del comprobante y emitir uno nuevo. NO anular el rechazado, ya no tiene validez.</td>
                            </tr>
                            <tr>
                                <td><span class="inline-flex items-center px-2 py-1 rounded text-xs font-semibold bg-gray-100 text-gray-600 border border-gray-300">exception</span></td>
                                <td>Error técnico temporal (SUNAT caído, timeout de red). El comprobante pudo o no llegar.</td>
                                <td>Esperar unos minutos y usar <code>/documents/reintentar</code> o <code>/documents/consult</code> con el ticket.</td>
                            </tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Diferencia entre success y status</h3>
                    <table>
                        <thead><tr><th>success</th><th>data.status</th><th>¿Qué pasó?</th></tr></thead>
                        <tbody>
                            <tr><td><code>true</code></td><td><code>accepted</code></td><td>✅ Todo bien. Comprobante válido ante SUNAT.</td></tr>
                            <tr><td><code>true</code></td><td><code>rejected</code></td><td>⚠️ Signia funcionó pero SUNAT rechazó por datos incorrectos.</td></tr>
                            <tr><td><code>true</code></td><td><code>exception</code></td><td>⏳ Signia funcionó pero SUNAT no respondió. Reintentar.</td></tr>
                            <tr><td><code>false</code></td><td>—</td><td>❌ Error en Signia (saldo, RUC no registrado, campos faltantes). Ver campo <code>message</code>.</td></tr>
                        </tbody>
                    </table>
                </section>';

$content = str_replace($old_interpretacion, $new_interpretacion, $content);

file_put_contents($file, $content);
echo "Done — all sections updated";
