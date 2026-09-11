<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signia Developers | API</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&family=Roboto+Mono:wght@400;500&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background-color: #ffffff; color: #374151; }
        code, pre { font-family: 'Roboto Mono', monospace; }
        table { width: 100%; border-collapse: collapse; margin-top: 1rem; margin-bottom: 2rem; font-size: 0.875rem; }
        th { text-align: left; padding: 0.75rem 1rem; background-color: #f9fafb; border: 1px solid #e5e7eb; font-weight: 600; color: #4b5563; }
        td { padding: 0.75rem 1rem; border: 1px solid #e5e7eb; vertical-align: top; }
        .endpoint-box { background-color: #f9fafb; padding: 1rem; border-radius: 0.5rem; font-family: 'Roboto Mono', monospace; font-size: 0.875rem; margin-top: 1rem; margin-bottom: 2rem; display: flex; align-items: center; gap: 0.5rem; }
        .method-post { color: #8b5cf6; font-weight: 600; }
        .sidebar-link { display: block; padding: 0.5rem 0; color: #4b5563; font-size: 0.875rem; transition: color 0.2s; }
        .sidebar-link:hover { color: #111827; }
        .sidebar-link.active { color: #2563eb; font-weight: 500; }
        .sidebar-title { font-size: 0.875rem; font-weight: 600; color: #111827; margin-top: 1.5rem; margin-bottom: 0.5rem; }
        
        .toc-link { display: block; padding: 0.25rem 0; color: #6b7280; font-size: 0.8125rem; border-left: 2px solid transparent; padding-left: 1rem; margin-left: -2px; }
        .toc-link:hover { color: #111827; border-left-color: #d1d5db; }
        .toc-link.active { color: #2563eb; border-left-color: #2563eb; }
    </style>
</head>
<body class="antialiased h-screen overflow-hidden flex flex-col">
    
    <!-- Navbar -->
    <nav class="h-16 border-b border-gray-200 flex items-center justify-between px-4 sm:px-6 lg:px-8 shrink-0 bg-white z-10">
        <div class="flex items-center gap-4">
            <a href="/">
                <img src="{{ asset('img/logo-horizontal.png') }}" alt="Signia" class="h-6 w-auto">
            </a>
            <span class="text-gray-400 font-light text-xl mb-1">|</span>
            <span class="font-semibold text-gray-700">API v1</span>
        </div>
        <div class="flex items-center gap-4">
            <div class="relative">
                <input type="text" placeholder="Buscar   Ctrl K" class="bg-gray-50 border border-gray-200 text-sm rounded-md pl-8 pr-4 py-1.5 focus:outline-none focus:ring-1 focus:ring-blue-500 w-64 text-gray-500">
                <svg class="w-4 h-4 text-gray-400 absolute left-2.5 top-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
            </div>
            <a href="{{ route('dashboard') }}" class="text-sm font-medium text-blue-600 hover:text-blue-700">Dashboard</a>
        </div>
    </nav>

    <div class="flex flex-1 overflow-hidden">
        
        <!-- Left Sidebar -->
        <aside class="w-64 overflow-y-auto border-r border-gray-200 px-6 py-6 shrink-0 bg-white">
            <h3 class="sidebar-title mt-0">Introducción</h3>
            <a href="#empezando" class="sidebar-link">Empezando</a>
            <a href="#entornos" class="sidebar-link">Entornos</a>
            
            <h3 class="sidebar-title">Endpoints API</h3>
            <a href="#obtener-token" class="sidebar-link active">Obtener Token</a>
            <a href="#emitir-json" class="sidebar-link">Emitir Comprobante</a>
            <a href="#consultar-ticket" class="sidebar-link">Consultar ticket</a>
            <a href="#interpretacion" class="sidebar-link">Interpretación rápida</a>
            
                        <h3 class="sidebar-title">Empresas (B2B)</h3>
            <a href="#listar-empresas" class="sidebar-link">Listar Empresas</a>
            <a href="#crear-empresa" class="sidebar-link">Crear Empresa</a>
            <a href="#produccion-empresa" class="sidebar-link">Pasar a Producción</a>
            <a href="#certificado-empresa" class="sidebar-link">Subir Certificado</a>
            <a href="#eliminar-empresa" class="sidebar-link">Eliminar Empresa</a>

            <h3 class="sidebar-title">Operaciones B2B</h3>
            <a href="#saldo-agencia" class="sidebar-link">Consultar Saldo</a>
            <a href="#historial-docs" class="sidebar-link">Historial de Docs</a>
            <a href="#reintentar-doc" class="sidebar-link">Reintentar Envío</a>

            <h3 class="sidebar-title">Recursos</h3>
            <a href="#catalogos" class="sidebar-link">Catálogos SUNAT</a>
            <a href="#errores" class="sidebar-link">Códigos de Error</a>
        </aside>

        <!-- Main Content Area -->
        <main class="flex-1 overflow-y-auto bg-white relative">
            <div class="max-w-4xl mx-auto px-8 py-10">
                
                <!-- ENTORNOS SECTION -->
                <section id="entornos" class="mb-20 pt-8">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Entornos de Integración</h1>
                    <p class="mb-6 leading-relaxed">Signia B2B provee un único endpoint base. La plataforma enruta automáticamente los documentos a SUNAT Beta (Motor Nativo) o a Producción (Motor PSE) según la configuración del RUC en el Dashboard.</p>
                    
                    <div class="endpoint-box mb-10">
                        <span class="text-gray-500">URL Base:</span>
                        <span class="text-gray-800">https://signia.kore.pe/api/v1</span>
                    </div>
                </section>

                <!-- OBTENER TOKEN SECTION -->
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
                </section>

                <!-- EMITIR JSON SECTION -->
                <section id="emitir-json" class="mb-20 pt-8 border-t border-gray-100" x-data="{ lang: 'curl' }">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Emitir Comprobante</h1>
                    <p class="mb-6 leading-relaxed text-gray-600">Este es nuestro endpoint estrella. Construye el comprobante enviando los datos estructurados en formato JSON. Signia se encarga de armar el XML UBL 2.1, firmarlo y enviarlo, todo en 1 solo paso.</p>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Endpoint</h3>
                    <div class="endpoint-box">
                        <span class="method-post">POST</span>
                        <span class="text-gray-800">/documents/send</span>
                    </div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Body</h3>
                    <table>
                        <thead>
                            <tr><th>Campo</th><th>Tipo</th><th>Descripción</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">company.ruc</code></td>
                                <td>string(11)</td>
                                <td>RUC de la empresa emisora registrada en Signia.</td>
                            </tr>
                            <tr>
                                <td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">document.document_type_id</code></td>
                                <td>string(2)</td>
                                <td>Tipo de comprobante según catálogo SUNAT.</td>
                            </tr>
                            <tr>
                                <td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">items[]</code></td>
                                <td>array</td>
                                <td>Lista de productos o servicios del comprobante.</td>
                            </tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-4 mt-8">Ejemplo de Código</h3>
                    
                    <div class="flex border-b border-gray-200 mb-4 gap-6 text-sm font-medium">
                        <button @click="lang = 'curl'" :class="{'text-blue-600 border-b-2 border-blue-600': lang === 'curl', 'text-gray-500': lang !== 'curl'}" class="pb-2">cURL</button>
                        <button @click="lang = 'php'" :class="{'text-blue-600 border-b-2 border-blue-600': lang === 'php', 'text-gray-500': lang !== 'php'}" class="pb-2">PHP (Guzzle)</button>
                        <button @click="lang = 'js'" :class="{'text-blue-600 border-b-2 border-blue-600': lang === 'js', 'text-gray-500': lang !== 'js'}" class="pb-2">Node.js</button>
                    </div>

                    <div class="bg-gray-900 border border-gray-800 rounded-lg p-5 overflow-x-auto text-sm font-mono text-gray-300 mb-8 shadow-inner">
<pre x-show="lang === 'curl'"><code class="text-emerald-400">curl -X POST https://signia.kore.pe/api/v1/documents/send \
  -H "Authorization: Bearer TU_TOKEN_API" \
  -H "Content-Type: application/json" \
  -d '{
    "company": {
      "ruc": "20123456789"
    },
    "document": {
      "document_type_id": "01",
      "series": "F001",
      "number": "1",
      "currency_type_id": "PEN"
    },
    "items": [
      {
        "quantity": 1,
        "unit_price": 118.00,
        "description": "Desarrollo Software"
      }
    ]
  }'</code></pre>

<pre x-show="lang === 'php'" style="display:none;"><code class="text-blue-300">$client = new \GuzzleHttp\Client();
$response = $client->post('https://signia.kore.pe/api/v1/documents/send', [
    'headers' => [
        'Authorization' => 'Bearer TU_TOKEN_API',
        'Accept'        => 'application/json',
    ],
    'json' => [
        'company' => ['ruc' => '20123456789'],
        'document' => ['series' => 'F001', 'number' => '1'],
        'items' => [...]
    ]
]);

echo $response->getBody();</code></pre>

<pre x-show="lang === 'js'" style="display:none;"><code class="text-yellow-300">fetch('https://signia.kore.pe/api/v1/documents/send', {
  method: 'POST',
  headers: {
    'Authorization': 'Bearer TU_TOKEN_API',
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    company: { ruc: "20123456789" },
    document: { series: "F001", number: "1" },
    items: [...]
  })
})
.then(res => res.json())
.then(console.log);</code></pre>
                    </div>

                </section>

                <!-- CONSULTAR TICKET SECTION -->
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
                </section>

                <!-- INTERPRETACION SECTION -->
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
                </section>
                
                                <!-- B2B EMPRESAS (REEMPLAZADO) -->
                <section id="listar-empresas" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Listar Empresas</h1>
                    <p class="mb-6 leading-relaxed">Obtiene todas las empresas asociadas a la cuenta de tu agencia. Ideal para que tu sistema consulte dinámicamente cuáles RUCs están activos y cuáles siguen en modo demo.</p>
                    <div class="endpoint-box"><span class="text-blue-600 font-bold">GET</span> /api/v1/empresas</div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Headers</h3>
                    <table>
                        <thead><tr><th>Header</th><th>Valor</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Authorization</code></td><td>Bearer {api_token}</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Accept</code></td><td>application/json</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta exitosa (200)</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "success": true,
  "data": [
    {
      "id": 1,
      "ruc": "20123456789",
      "business_name": "Mi Empresa SAC",
      "environment": "demo",
      "engine_type": "pse",
      "pse_username": "20123456789MODDEMO",
      "sol_user": "MIUSUARIOSOL",
      "created_at": "2026-01-15T10:30:00.000000Z"
    }
  ]
}</code></pre>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Campos de la respuesta</h3>
                    <table>
                        <thead><tr><th>Campo</th><th>Tipo</th><th>Descripción</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">id</code></td><td>integer</td><td>Identificador interno de la empresa en Signia.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">ruc</code></td><td>string</td><td>RUC de 11 dígitos.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">business_name</code></td><td>string</td><td>Razón social de la empresa.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">environment</code></td><td>string</td><td><code>demo</code> o <code>production</code>.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">engine_type</code></td><td>string</td><td><code>pse</code> (firma delegada por Signia) o <code>native</code> (certificado propio).</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">pse_username</code></td><td>string</td><td>Usuario del Motor PSE asignado al registrar la empresa.</td></tr>
                        </tbody>
                    </table>
                </section>

                <section id="crear-empresa" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Crear Empresa</h1>
                    <p class="mb-6 leading-relaxed">Registra una nueva empresa bajo tu agencia en Signia. Si el motor es <code>pse</code>, el sistema provisiona y devuelve automáticamente las credenciales del Motor PSE para ese RUC.</p>
                    <div class="endpoint-box"><span class="method-post">POST</span> /api/v1/empresa/crear</div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Headers</h3>
                    <table>
                        <thead><tr><th>Header</th><th>Valor</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Authorization</code></td><td>Bearer {api_token}</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Content-Type</code></td><td>application/json</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Accept</code></td><td>application/json</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Parámetros del Body</h3>
                    <table>
                        <thead><tr><th>Campo</th><th>Tipo</th><th>Requerido</th><th>Descripción</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">ruc</code></td><td>string</td><td><strong>Sí</strong></td><td>RUC de 11 dígitos de la empresa a registrar.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">business_name</code></td><td>string</td><td><strong>Sí</strong></td><td>Razón social de la empresa. Máx. 255 caracteres.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">environment</code></td><td>string</td><td>No</td><td>Entorno inicial: <code>demo</code> o <code>production</code>. Por defecto: <code>demo</code>.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">engine_type</code></td><td>string</td><td><strong>Sí</strong></td><td><code>pse</code> para firma delegada por Signia, o <code>native</code> para certificado digital propio.</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Ejemplo de Request</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "ruc": "20123456789",
  "business_name": "Mi Empresa SAC",
  "environment": "demo",
  "engine_type": "pse"
}</code></pre>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta exitosa (201)</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "success": true,
  "message": "Empresa registrada satisfactoriamente",
  "data": {
    "ruc": "20123456789",
    "business_name": "Mi Empresa SAC",
    "environment": "demo",
    "engine_type": "pse",
    "username": "20123456789MODDEMO",
    "password": "p@ssG3n3r4d0"
  }
}</code></pre>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Errores posibles</h3>
                    <table>
                        <thead><tr><th>HTTP</th><th>Mensaje</th><th>Causa</th></tr></thead>
                        <tbody>
                            <tr><td>422</td><td>The ruc field is required</td><td>Falta el campo <code>ruc</code> en el body.</td></tr>
                            <tr><td>422</td><td>The ruc field must be 11 characters</td><td>El RUC no tiene 11 dígitos.</td></tr>
                            <tr><td>422</td><td>The engine type field is required</td><td>Falta el campo <code>engine_type</code>.</td></tr>
                            <tr><td>400</td><td>El RUC ya se encuentra registrado en tu cuenta</td><td>Ya existe este RUC para la agencia autenticada.</td></tr>
                            <tr><td>400</td><td>Error al registrar en el motor PSE</td><td>Fallo al provisionar credenciales en el proveedor externo.</td></tr>
                        </tbody>
                    </table>
                </section>

                <section id="produccion-empresa" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Pasar Empresa a Producción</h1>
                    <p class="mb-6 leading-relaxed">Cambia el entorno de una empresa de <code>demo</code> a <code>production</code>. Si el motor es <code>pse</code>, Signia realiza la activación formal en el proveedor de firmas automáticamente.</p>
                    <div class="bg-amber-50 border-l-4 border-amber-400 p-4 rounded-r-lg mb-6">
                        <p class="text-amber-800 font-semibold text-sm">Acción irreversible</p>
                        <p class="text-amber-700 text-sm mt-1">Una vez en producción, la empresa no puede regresar a modo demo. Asegúrese de que las credenciales SOL y el certificado digital estén correctamente configurados antes de ejecutar.</p>
                    </div>
                    <div class="endpoint-box"><span class="method-post">POST</span> /api/v1/empresa/produccion</div>

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
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">ruc</code></td><td>string</td><td><strong>Sí</strong></td><td>RUC de 11 dígitos de la empresa a activar en producción.</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Ejemplo de Request</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "ruc": "20123456789"
}</code></pre>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta exitosa (200)</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "success": true,
  "message": "Empresa actualizada a producción exitosamente.",
  "data": {
    "ruc": "20123456789",
    "environment": "production"
  }
}</code></pre>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Errores posibles</h3>
                    <table>
                        <thead><tr><th>HTTP</th><th>Mensaje</th><th>Causa</th></tr></thead>
                        <tbody>
                            <tr><td>404</td><td>RUC no encontrado en tu cuenta</td><td>El RUC no existe o no pertenece a la agencia autenticada.</td></tr>
                            <tr><td>400</td><td>La empresa ya se encuentra en producción</td><td>El entorno ya era <code>production</code>.</td></tr>
                            <tr><td>400</td><td>Error al pasar a producción en el motor PSE</td><td>Fallo al activar en el proveedor externo.</td></tr>
                        </tbody>
                    </table>
                </section>

                <section id="certificado-empresa" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Subir Certificado Digital</h1>
                    <p class="mb-6 leading-relaxed">Sube y vincula el certificado digital (<code>.p12</code> o <code>.pem</code>) de una empresa que usa el motor <code>native</code>. La contraseña queda almacenada de forma encriptada.</p>
                    <div class="endpoint-box"><span class="method-post">POST</span> /api/v1/empresa/certificado</div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Headers</h3>
                    <table>
                        <thead><tr><th>Header</th><th>Valor</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Authorization</code></td><td>Bearer {api_token}</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Content-Type</code></td><td>multipart/form-data</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Parámetros (form-data)</h3>
                    <table>
                        <thead><tr><th>Campo</th><th>Tipo</th><th>Requerido</th><th>Descripción</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">ruc</code></td><td>string</td><td><strong>Sí</strong></td><td>RUC de 11 dígitos de la empresa a la cual pertenece el certificado.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">certificate</code></td><td>file</td><td><strong>Sí</strong></td><td>Archivo <code>.p12</code> o <code>.pem</code> emitido por SUNAT u OSE autorizado.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">password</code></td><td>string</td><td><strong>Sí</strong></td><td>Contraseña del certificado digital.</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta exitosa (200)</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "success": true,
  "message": "Certificado subido y configurado correctamente.",
  "data": {
    "ruc": "20123456789",
    "certificate_path": "certificates/20123456789/certificado.p12"
  }
}</code></pre>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Errores posibles</h3>
                    <table>
                        <thead><tr><th>HTTP</th><th>Mensaje</th><th>Causa</th></tr></thead>
                        <tbody>
                            <tr><td>404</td><td>RUC no encontrado en tu cuenta</td><td>El RUC no pertenece a la agencia autenticada.</td></tr>
                            <tr><td>422</td><td>The certificate must be a file of type: p12, pem</td><td>El archivo subido no tiene el formato permitido.</td></tr>
                            <tr><td>422</td><td>The password field is required</td><td>Falta la contraseña del certificado en el body.</td></tr>
                        </tbody>
                    </table>
                </section>

                <section id="eliminar-empresa" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Eliminar Empresa</h1>
                    <p class="mb-6 leading-relaxed">Da de baja una empresa de tu agencia de forma inmediata. Una vez eliminada, ese RUC no podrá emitir comprobantes a través de Signia hasta ser registrado nuevamente. El historial de documentos emitidos se conserva por trazabilidad (4 años).</p>
                    <div class="bg-red-50 border-l-4 border-red-400 p-4 rounded-r-lg mb-6">
                        <p class="text-red-800 font-semibold text-sm">Acción permanente</p>
                        <p class="text-red-700 text-sm mt-1">Esta operación elimina las credenciales y la configuración de la empresa del sistema. Úsala para suspender clientes morosos o dar de baja empresas cerradas.</p>
                    </div>
                    <div class="endpoint-box"><span class="text-red-600 font-bold">DELETE</span> /api/v1/empresa/{ruc}</div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Headers</h3>
                    <table>
                        <thead><tr><th>Header</th><th>Valor</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Authorization</code></td><td>Bearer {api_token}</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Parámetros de la URL</h3>
                    <table>
                        <thead><tr><th>Parámetro</th><th>Tipo</th><th>Descripción</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">ruc</code></td><td>string</td><td>RUC de 11 dígitos de la empresa a eliminar. Ejemplo: <code>DELETE /api/v1/empresa/20123456789</code></td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta exitosa (200)</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "success": true,
  "message": "Empresa eliminada/suspendida correctamente."
}</code></pre>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Errores posibles</h3>
                    <table>
                        <thead><tr><th>HTTP</th><th>Mensaje</th><th>Causa</th></tr></thead>
                        <tbody>
                            <tr><td>404</td><td>RUC no encontrado en tu cuenta</td><td>El RUC no existe o no pertenece a la agencia autenticada.</td></tr>
                        </tbody>
                    </table>
                </section>

                <section id="saldo-agencia" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Consultar Saldo</h1>
                    <p class="mb-6 leading-relaxed">Retorna el saldo disponible de la bolsa de firmas de tu agencia, desglosado por tipo de motor. Ideal para implementar alertas automáticas en tu sistema cuando el saldo esté por debajo de un umbral.</p>
                    <div class="endpoint-box"><span class="text-blue-600 font-bold">GET</span> /api/v1/saldo</div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Headers</h3>
                    <table>
                        <thead><tr><th>Header</th><th>Valor</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Authorization</code></td><td>Bearer {api_token}</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Accept</code></td><td>application/json</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta exitosa (200)</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "success": true,
  "data": {
    "balance_pse": 997,
    "balance_native": 500
  }
}</code></pre>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Campos de la respuesta</h3>
                    <table>
                        <thead><tr><th>Campo</th><th>Tipo</th><th>Descripción</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">balance_pse</code></td><td>integer</td><td>Firmas disponibles para el Motor PSE (delegado). Se descuenta 1 por cada comprobante emitido en entorno <code>production</code>.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">balance_native</code></td><td>integer</td><td>Firmas disponibles para el Motor Nativo (certificado propio). No se descuenta en entorno demo.</td></tr>
                        </tbody>
                    </table>
                </section>

                <section id="historial-docs" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Historial de Documentos</h1>
                    <p class="mb-6 leading-relaxed">Retorna el listado paginado de todos los documentos emitidos por tu agencia. Cada documento incluye los links de descarga directa de los archivos físicos (XML firmado, CDR de respuesta SUNAT y PDF representación impresa) almacenados por Signia como parte de la custodia de 4 años.</p>
                    <div class="endpoint-box"><span class="text-blue-600 font-bold">GET</span> /api/v1/documents</div>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Headers</h3>
                    <table>
                        <thead><tr><th>Header</th><th>Valor</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Authorization</code></td><td>Bearer {api_token}</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">Accept</code></td><td>application/json</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Query Parameters (opcionales)</h3>
                    <table>
                        <thead><tr><th>Parámetro</th><th>Tipo</th><th>Descripción</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">ruc</code></td><td>string</td><td>Filtra documentos de un RUC específico.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">status</code></td><td>string</td><td>Filtra por estado SUNAT: <code>accepted</code>, <code>rejected</code>, <code>exception</code>.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">per_page</code></td><td>integer</td><td>Documentos por página. Por defecto: <code>15</code>.</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Ejemplo de Request</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>GET /api/v1/documents?ruc=20123456789&amp;status=accepted&amp;per_page=20</code></pre>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta exitosa (200)</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "success": true,
  "data": {
    "current_page": 1,
    "total": 42,
    "per_page": 20,
    "data": [
      {
        "id": 15,
        "serie": "F001",
        "number": "1",
        "document_type": "01",
        "status": "accepted",
        "xml_url": "https://signia.kore.pe/storage/documents/20123456789/2026/09/20123456789-01-F001-1.xml",
        "cdr_url": "https://signia.kore.pe/storage/documents/20123456789/2026/09/R-20123456789-01-F001-1.zip",
        "pdf_url": "https://signia.kore.pe/storage/documents/20123456789/2026/09/20123456789-01-F001-1.pdf",
        "company": {
          "ruc": "20123456789",
          "business_name": "Mi Empresa SAC"
        },
        "created_at": "2026-09-10T20:00:00.000000Z"
      }
    ]
  }
}</code></pre>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Campos de la respuesta</h3>
                    <table>
                        <thead><tr><th>Campo</th><th>Tipo</th><th>Descripción</th></tr></thead>
                        <tbody>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">status</code></td><td>string</td><td>Veredicto de SUNAT: <code>accepted</code>, <code>rejected</code>, <code>exception</code>.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">xml_url</code></td><td>string|null</td><td>URL de descarga directa del XML firmado. Null si aún no fue generado.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">cdr_url</code></td><td>string|null</td><td>URL de descarga directa del CDR (.zip) con la respuesta oficial de SUNAT.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">pdf_url</code></td><td>string|null</td><td>URL de descarga directa del PDF (representación impresa del comprobante).</td></tr>
                        </tbody>
                    </table>
                </section>

                <section id="reintentar-doc" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Reintentar Envío</h1>
                    <p class="mb-6 leading-relaxed">Reintenta el procesamiento de un comprobante que quedó en estado <code>exception</code> (timeout de SUNAT, error temporal de red, CDR pendiente de consulta). Permite implementar un botón de "Forzar reenvío" dentro del ERP del cliente.</p>
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
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">ruc</code></td><td>string</td><td><strong>Sí</strong></td><td>RUC emisor del comprobante (11 dígitos).</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">serie</code></td><td>string</td><td><strong>Sí</strong></td><td>Serie del comprobante. Ejemplos: <code>F001</code>, <code>B001</code>, <code>FF01</code>.</td></tr>
                            <tr><td><code class="text-blue-600 bg-blue-50 px-1 py-0.5 rounded">number</code></td><td>string</td><td><strong>Sí</strong></td><td>Número correlativo del comprobante. Ejemplo: <code>1</code>.</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Ejemplo de Request</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "ruc": "20123456789",
  "serie": "F001",
  "number": "1"
}</code></pre>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Respuesta exitosa (200)</h3>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "success": true,
  "message": "Reintento de envío o consulta encolado/ejecutado.",
  "data": {
    "id": 15,
    "serie": "F001",
    "number": "1",
    "status": "accepted",
    "cdr_url": "https://signia.kore.pe/storage/documents/20123456789/2026/09/R-20123456789-01-F001-1.zip"
  }
}</code></pre>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Errores posibles</h3>
                    <table>
                        <thead><tr><th>HTTP</th><th>Mensaje</th><th>Causa</th></tr></thead>
                        <tbody>
                            <tr><td>404</td><td>Documento no encontrado</td><td>No existe ese comprobante bajo el RUC indicado para la agencia.</td></tr>
                            <tr><td>422</td><td>The ruc field is required</td><td>Falta alguno de los campos requeridos en el body.</td></tr>
                        </tbody>
                    </table>
                </section>

                <!-- CATALOGOS SECTION -->
                <section id="catalogos" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Catálogos SUNAT</h1>
                    <p class="mb-6 leading-relaxed">Para mantener la compatibilidad con los estándares de SUNAT, utilizamos los mismos códigos de catálogo oficiales. Aquí tienes los más utilizados en las peticiones.</p>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Tipo de Documento (Catálogo 01)</h3>
                    <table>
                        <thead>
                            <tr><th>Código</th><th>Descripción</th></tr>
                        </thead>
                        <tbody>
                            <tr><td><code>01</code></td><td>Factura</td></tr>
                            <tr><td><code>03</code></td><td>Boleta</td></tr>
                            <tr><td><code>07</code></td><td>Nota de Crédito</td></tr>
                            <tr><td><code>08</code></td><td>Nota de Débito</td></tr>
                            <tr><td><code>09</code></td><td>Guía de Remisión Remitente</td></tr>
                        </tbody>
                    </table>

                    <h3 class="text-lg font-semibold text-gray-900 mb-2 mt-8">Afectación al IGV (Catálogo 07)</h3>
                    <table>
                        <thead>
                            <tr><th>Código</th><th>Descripción</th></tr>
                        </thead>
                        <tbody>
                            <tr><td><code>10</code></td><td>Gravado - Operación Onerosa</td></tr>
                            <tr><td><code>20</code></td><td>Exonerado - Operación Onerosa</td></tr>
                            <tr><td><code>30</code></td><td>Inafecto - Operación Onerosa</td></tr>
                        </tbody>
                    </table>
                </section>

                <!-- ERRORES SECTION -->
                <section id="errores" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Códigos de Error HTTP</h1>
                    <p class="mb-6 leading-relaxed">Nuestra API utiliza códigos de estado HTTP convencionales para indicar el éxito o fracaso de una petición.</p>

                    <table>
                        <thead>
                            <tr><th>Código HTTP</th><th>Mensaje general</th><th>Causa común</th></tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><code class="text-red-600 bg-red-50 px-1 py-0.5 rounded">400 Bad Request</code></td>
                                <td>Error de validación</td>
                                <td>Faltan campos obligatorios en el JSON o el RUC es inválido.</td>
                            </tr>
                            <tr>
                                <td><code class="text-red-600 bg-red-50 px-1 py-0.5 rounded">401 Unauthorized</code></td>
                                <td>No autorizado</td>
                                <td>El token Bearer no se envió, es inválido o expiró.</td>
                            </tr>
                            <tr>
                                <td><code class="text-red-600 bg-red-50 px-1 py-0.5 rounded">402 Payment Required</code></td>
                                <td>Saldo insuficiente</td>
                                <td>La agencia se ha quedado sin saldo de firmas disponible.</td>
                            </tr>
                            <tr>
                                <td><code class="text-red-600 bg-red-50 px-1 py-0.5 rounded">404 Not Found</code></td>
                                <td>RUC no registrado</td>
                                <td>El RUC enviado no está registrado bajo tu Agencia.</td>
                            </tr>
                            <tr>
                                <td><code class="text-red-600 bg-red-50 px-1 py-0.5 rounded">500 Server Error</code></td>
                                <td>Error interno</td>
                                <td>Error crítico en los servidores de Signia o caída total de SUNAT.</td>
                            </tr>
                        </tbody>
                    </table>
                </section>
                
                <div class="flex justify-between items-center py-8 border-t border-gray-200 mt-20">
                    <a href="#" class="text-gray-500 hover:text-gray-900 text-sm flex flex-col items-start"><span class="text-xs text-gray-400">Página anterior</span> Obtener Token</a>
                    <a href="#" class="text-blue-600 hover:text-blue-800 text-sm font-medium flex flex-col items-end"><span class="text-xs text-gray-400 font-normal">Siguiente página</span> Consultar Ticket</a>
                </div>

            </div>
        </main>

        <!-- Right Sidebar (On this page) -->
        <aside class="w-64 overflow-y-auto px-4 py-10 shrink-0 hidden xl:block bg-white border-l border-gray-100">
            <h4 class="text-xs font-semibold text-gray-900 mb-3">En esta página</h4>
            <nav class="space-y-1">
                <a href="#entornos" class="toc-link">Entornos</a>
                <a href="#obtener-token" class="toc-link active">Obtener Token</a>
                <div class="pl-4 space-y-1">
                    <a href="#endpoint" class="toc-link">Endpoint</a>
                    <a href="#body" class="toc-link">Body</a>
                </div>
                <a href="#emitir-json" class="toc-link">Emitir Comprobante</a>
                <a href="#consultar-ticket" class="toc-link">Consultar ticket</a>
                <a href="#interpretacion" class="toc-link">Interpretación rápida</a>
            </nav>
        </aside>

    </div>

</body>
</html>
