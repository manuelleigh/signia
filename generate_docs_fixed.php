<?php

$docs = <<<HTML
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signia Developers | API</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif; background-color: #ffffff; }
        .sidebar { width: 280px; height: 100vh; position: fixed; top: 0; left: 0; overflow-y: auto; background-color: #fafafa; border-right: 1px solid #eaeaea; }
        .main-content { margin-left: 280px; padding: 40px 80px; max-width: 1200px; }
        .endpoint-box { display: flex; align-items: center; background-color: #f1f5f9; border-radius: 8px; padding: 12px 16px; font-family: monospace; font-size: 14px; margin-bottom: 24px; border: 1px solid #e2e8f0; }
        .method-post { color: #10b981; font-weight: bold; margin-right: 12px; }
        .method-get { color: #3b82f6; font-weight: bold; margin-right: 12px; }
        .method-delete { color: #ef4444; font-weight: bold; margin-right: 12px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 24px; font-size: 14px; }
        th { text-align: left; border-bottom: 2px solid #e2e8f0; padding: 12px 8px; color: #64748b; font-weight: 600; }
        td { border-bottom: 1px solid #f1f5f9; padding: 12px 8px; color: #334155; vertical-align: top; }
        .code-tab { cursor: pointer; padding: 8px 16px; border-bottom: 2px solid transparent; color: #64748b; font-weight: 500; font-size: 14px; }
        .code-tab.active { border-bottom-color: #3b82f6; color: #3b82f6; }
        .nav-link { display: block; padding: 8px 24px; color: #475569; text-decoration: none; font-size: 14px; margin-bottom: 4px; border-left: 3px solid transparent; }
        .nav-link:hover { color: #0f172a; background-color: #f1f5f9; }
        .nav-link.active { color: #3b82f6; font-weight: 600; border-left-color: #3b82f6; background-color: #eff6ff; }
        .nav-section { font-size: 12px; font-weight: 700; color: #1e293b; text-transform: uppercase; letter-spacing: 0.05em; margin: 24px 24px 8px 24px; }
        pre code { font-family: 'Fira Code', Consolas, Monaco, 'Andale Mono', 'Ubuntu Mono', monospace; font-size: 13px; line-height: 1.5; }
    </style>
</head>
<body class="text-slate-800">

    <header class="bg-white border-b border-gray-200 fixed top-0 w-full z-10 lg:hidden flex items-center justify-between px-4 py-3">
        <div class="flex items-center gap-2 font-bold text-xl text-slate-800">
            <svg class="w-6 h-6 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            Signia
        </div>
    </header>

    <aside class="sidebar hidden lg:block pt-6">
        <div class="flex items-center gap-2 font-bold text-xl text-slate-800 px-6 mb-8">
            <svg class="w-7 h-7 text-blue-600" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2L2 7l10 5 10-5-10-5zM2 17l10 5 10-5M2 12l10 5 10-5"/></svg>
            Signia API v1
        </div>

        <div class="nav-section">Introducción</div>
        <a href="#empezando" class="nav-link active">Empezando</a>
        <a href="#entornos" class="nav-link">Entornos</a>

        <div class="nav-section">Endpoints API</div>
        <a href="#obtener-token" class="nav-link">Autenticación (Token)</a>
        <a href="#emitir-comprobante" class="nav-link">Emitir Comprobante</a>
        <a href="#consultar-ticket" class="nav-link">Consultar Ticket</a>
        <a href="#reintentar" class="nav-link">Reintentar Envío</a>

        <div class="nav-section">Operaciones B2B</div>
        <a href="#saldo" class="nav-link">Consultar Saldo</a>
        <a href="#listar-empresas" class="nav-link">Listar Empresas</a>
        <a href="#crear-empresa" class="nav-link">Crear Empresa</a>
        <a href="#produccion-empresa" class="nav-link">Pasar a Producción</a>
        <a href="#certificado-empresa" class="nav-link">Subir Certificado</a>
    </aside>

    <main class="main-content lg:mt-0 mt-16 pb-32">

        <!-- INTRO -->
        <section id="empezando" class="mb-20 pt-8">
            <h1 class="text-4xl font-extrabold text-slate-900 mb-4">API Reference B2B</h1>
            <p class="text-lg text-slate-600 mb-6 leading-relaxed">Bienvenido a la documentación oficial de <strong>Signia B2B</strong>. Integra facturación electrónica UBL 2.1 directamente en tu ERP o POS de forma rápida y segura.</p>
            <div class="bg-blue-50 border-l-4 border-blue-500 p-4 rounded-r-lg mb-8">
                <p class="text-blue-800 font-semibold">URL Base</p>
                <code class="text-blue-600 bg-blue-100/50 px-2 py-1 rounded mt-2 inline-block">https://signia.kore.pe/api/v1</code>
            </div>
        </section>

        <!-- ENTORNOS -->
        <section id="entornos" class="mb-20 pt-8 border-t border-slate-200">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Entornos de Pruebas y Producción</h2>
            <p class="text-slate-600 mb-6">El API de Signia maneja inteligentemente los entornos. Cuando creas una Empresa B2B en tu panel, nace por defecto en estado <code>demo</code>. Cualquier comprobante que envíes usando el RUC de esa empresa no será enviado a SUNAT real, sino procesado por nuestro Motor Matemático Local (Mock) que simulará firmas y te devolverá CDRs idénticos para que pruebes infinitamente sin gastar tu saldo de firmas real.</p>
            <p class="text-slate-600 mb-6">Cuando la empresa esté lista, usa el endpoint de <code>Pasar a Producción</code> para cambiar su entorno. A partir de ahí, las firmas usarán tu saldo real y se comunicarán con SUNAT/PSE de producción de forma automática, ¡sin que cambies ni una línea de código en tu ERP!</p>
        </section>

        <!-- AUTH -->
        <section id="obtener-token" class="mb-20 pt-8 border-t border-slate-200">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Autenticación</h2>
            <p class="text-slate-600 mb-6">Todas las peticiones a la API deben incluir un token Bearer en el header <code>Authorization</code>. Puedes generar tokens estáticos en tu Dashboard B2B o generarlos programáticamente con este endpoint.</p>
            
            <div class="endpoint-box">
                <span class="method-post">POST</span> /auth/token
            </div>
            
            <h3 class="text-lg font-semibold text-slate-800 mb-3">Headers</h3>
            <table>
                <thead><tr><th>Header</th><th>Valor Requerido</th></tr></thead>
                <tbody>
                    <tr><td><code>Accept</code></td><td>application/json</td></tr>
                </tbody>
            </table>

            <h3 class="text-lg font-semibold text-slate-800 mb-3">Body (JSON)</h3>
            <table>
                <thead><tr><th>Parámetro</th><th>Tipo</th><th>Descripción</th></tr></thead>
                <tbody>
                    <tr><td><code>email</code></td><td>string</td><td>El correo electrónico de tu cuenta de Agencia.</td></tr>
                    <tr><td><code>password</code></td><td>string</td><td>Tu contraseña de acceso.</td></tr>
                    <tr><td><code>device_name</code></td><td>string</td><td>Identificador de la máquina (Ej. "ERP-Central").</td></tr>
                </tbody>
            </table>

            <div class="border-b border-slate-200 mb-4 flex gap-2 tabs-container" data-group="auth">
                <button class="code-tab active" data-target="auth-curl">cURL</button>
                <button class="code-tab text-slate-500" data-target="auth-php">PHP</button>
                <button class="code-tab text-slate-500" data-target="auth-node">Node.js</button>
            </div>

            <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto code-content" id="auth-curl" data-group="auth" style="display:block;">
<pre><code class="text-slate-300"><span class="text-pink-400">curl</span> -X POST https://signia.kore.pe/api/v1/auth/token \
  -H <span class="text-green-300">"Accept: application/json"</span> \
  -H <span class="text-green-300">"Content-Type: application/json"</span> \
  -d <span class="text-green-300">'{
    "email": "agencia@kore.pe",
    "password": "secretpassword",
    "device_name": "MI_ERP"
}'</span></code></pre>
            </div>

            <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto code-content" id="auth-php" data-group="auth" style="display:none;">
<pre><code class="text-slate-300">&lt;?php
<span class="text-pink-400">\$client</span> = new \GuzzleHttp\Client();
<span class="text-pink-400">\$response</span> = \$client->post('https://signia.kore.pe/api/v1/auth/token', [
    'json' => [
        'email' => 'agencia@kore.pe',
        'password' => 'secretpassword',
        'device_name' => 'MI_ERP'
    ]
]);
echo \$response->getBody();</code></pre>
            </div>

            <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto code-content" id="auth-node" data-group="auth" style="display:none;">
<pre><code class="text-slate-300"><span class="text-pink-400">const</span> axios = require('axios');

axios.post('https://signia.kore.pe/api/v1/auth/token', {
    email: 'agencia@kore.pe',
    password: 'secretpassword',
    device_name: 'MI_ERP'
}).then(res => console.log(res.data));</code></pre>
            </div>
        </section>

        <!-- EMITIR COMPROBANTE -->
        <section id="emitir-comprobante" class="mb-20 pt-8 border-t border-slate-200">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Emitir Comprobante</h2>
            <p class="text-slate-600 mb-6">Este es el endpoint principal. Envía la estructura JSON de tu comprobante y Signia se encarga de transformarlo a XML UBL 2.1, firmarlo con el certificado de la empresa y enviarlo a SUNAT o al PSE, retornando el CDR y el PDF.</p>
            
            <div class="bg-indigo-50 border-l-4 border-indigo-500 p-4 rounded-r-lg mb-8">
                <p class="text-indigo-800 font-bold text-sm">Arquitectura Híbrida Inteligente</p>
                <p class="text-indigo-700 text-sm mt-1">Para Facturas y Boletas, el API procesa la petición de manera <strong>Síncrona</strong> (devuelve XML/CDR al instante). Si SUNAT experimenta caídas, o si envías un Resumen Diario/Baja, Signia asume el control <strong>Asíncrono</strong> y te devuelve un <code>ticket</code> para que no pierdas la transacción.</p>
            </div>

            <div class="endpoint-box">
                <span class="method-post">POST</span> /documents/send
            </div>

            <h3 class="text-xl font-bold text-slate-800 mb-4 mt-8 border-b pb-2">Estructura del Body (JSON)</h3>
            
            <h4 class="text-md font-bold text-slate-800 mt-6 mb-2">1. Objeto <code>company</code></h4>
            <table>
                <thead><tr><th width="25%">Campo</th><th width="15%">Tipo</th><th>Descripción</th></tr></thead>
                <tbody>
                    <tr><td><code>ruc</code> <span class="text-red-500">*</span></td><td>string(11)</td><td>RUC de la empresa emisora (Previamente creada en tu dashboard).</td></tr>
                    <tr><td><code>name</code> <span class="text-red-500">*</span></td><td>string</td><td>Razón social de la empresa emisora.</td></tr>
                    <tr><td><code>trade_name</code></td><td>string</td><td>Nombre comercial (Opcional).</td></tr>
                    <tr><td><code>ubigeo</code></td><td>string(6)</td><td>Código de ubigeo (Ej. <code>150101</code>).</td></tr>
                    <tr><td><code>address</code></td><td>string</td><td>Dirección fiscal.</td></tr>
                </tbody>
            </table>

            <h4 class="text-md font-bold text-slate-800 mt-6 mb-2">2. Objeto <code>document</code></h4>
            <table>
                <thead><tr><th width="25%">Campo</th><th width="15%">Tipo</th><th>Descripción</th></tr></thead>
                <tbody>
                    <tr><td><code>document_type_id</code> <span class="text-red-500">*</span></td><td>string(2)</td><td><code>01</code> Factura, <code>03</code> Boleta, <code>07</code> Nota Crédito, <code>08</code> Nota Débito, <code>RC</code> Resumen, <code>RA</code> Baja.</td></tr>
                    <tr><td><code>series</code> <span class="text-red-500">*</span></td><td>string(4)</td><td>Ej. <code>F001</code>, <code>B001</code>.</td></tr>
                    <tr><td><code>number</code> <span class="text-red-500">*</span></td><td>string</td><td>Correlativo. Ej. <code>1</code>.</td></tr>
                    <tr><td><code>date_of_issue</code> <span class="text-red-500">*</span></td><td>date</td><td>Formato <code>YYYY-MM-DD</code>.</td></tr>
                    <tr><td><code>time_of_issue</code></td><td>time</td><td>Formato <code>HH:MM:SS</code>.</td></tr>
                    <tr><td><code>currency_type_id</code> <span class="text-red-500">*</span></td><td>string(3)</td><td><code>PEN</code> (Soles), <code>USD</code> (Dólares).</td></tr>
                    <tr><td><code>operation_type_id</code></td><td>string(4)</td><td>Por defecto <code>0101</code> (Venta Interna).</td></tr>
                    <tr><td><code>total_taxed</code> <span class="text-red-500">*</span></td><td>numeric</td><td>Total gravado (Base Imponible).</td></tr>
                    <tr><td><code>total_igv</code> <span class="text-red-500">*</span></td><td>numeric</td><td>Suma total del IGV (18%).</td></tr>
                    <tr><td><code>total_value</code> <span class="text-red-500">*</span></td><td>numeric</td><td>Igual al total gravado en ventas normales.</td></tr>
                    <tr><td><code>total</code> <span class="text-red-500">*</span></td><td>numeric</td><td>Importe total de la venta (Base + IGV).</td></tr>
                </tbody>
            </table>

            <h4 class="text-md font-bold text-slate-800 mt-6 mb-2">3. Objeto <code>customer</code></h4>
            <table>
                <thead><tr><th width="25%">Campo</th><th width="15%">Tipo</th><th>Descripción</th></tr></thead>
                <tbody>
                    <tr><td><code>identity_document_type_id</code> <span class="text-red-500">*</span></td><td>string(1)</td><td><code>6</code> (RUC), <code>1</code> (DNI), <code>0</code> (Doc. Trib. No. Dom.).</td></tr>
                    <tr><td><code>number</code> <span class="text-red-500">*</span></td><td>string</td><td>Número de documento del cliente.</td></tr>
                    <tr><td><code>name</code> <span class="text-red-500">*</span></td><td>string</td><td>Razón social o nombres del cliente.</td></tr>
                </tbody>
            </table>

            <h4 class="text-md font-bold text-slate-800 mt-6 mb-2">4. Array <code>items[]</code></h4>
            <table>
                <thead><tr><th width="25%">Campo</th><th width="15%">Tipo</th><th>Descripción</th></tr></thead>
                <tbody>
                    <tr><td><code>unit_type_id</code></td><td>string(3)</td><td>Unidad de medida (Ej. <code>NIU</code>, <code>ZZ</code>).</td></tr>
                    <tr><td><code>quantity</code> <span class="text-red-500">*</span></td><td>numeric</td><td>Cantidad del ítem.</td></tr>
                    <tr><td><code>description</code> <span class="text-red-500">*</span></td><td>string</td><td>Descripción del producto/servicio.</td></tr>
                    <tr><td><code>unit_value</code> <span class="text-red-500">*</span></td><td>numeric</td><td>Valor unitario (Sin IGV).</td></tr>
                    <tr><td><code>unit_price</code> <span class="text-red-500">*</span></td><td>numeric</td><td>Precio unitario (Con IGV).</td></tr>
                    <tr><td><code>total_base_igv</code> <span class="text-red-500">*</span></td><td>numeric</td><td>Base imponible (unit_value * quantity).</td></tr>
                    <tr><td><code>percentage_igv</code></td><td>numeric</td><td>Porcentaje aplicable (Por defecto <code>18</code>).</td></tr>
                    <tr><td><code>total_igv</code> <span class="text-red-500">*</span></td><td>numeric</td><td>IGV total de la línea.</td></tr>
                    <tr><td><code>total_value</code> <span class="text-red-500">*</span></td><td>numeric</td><td>Total de la línea sin IGV.</td></tr>
                    <tr><td><code>affectation_igv_type_id</code></td><td>string(2)</td><td>Afectación al IGV (Ej. <code>10</code> Gravado Onerosa).</td></tr>
                    <tr><td><code>price_type_id</code></td><td>string(2)</td><td>Tipo de precio (Ej. <code>01</code> Precio Unitario).</td></tr>
                </tbody>
            </table>

            <!-- Payload y Tabs -->
            <div class="border-b border-slate-200 mb-4 mt-8 flex gap-2 tabs-container" data-group="send">
                <button class="code-tab active" data-target="send-curl">cURL (Completo)</button>
                <button class="code-tab text-slate-500" data-target="send-php">PHP</button>
            </div>

            <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto code-content" id="send-curl" data-group="send" style="display:block;">
<pre><code class="text-slate-300"><span class="text-pink-400">curl</span> -X POST https://signia.kore.pe/api/v1/documents/send \
  -H <span class="text-green-300">"Authorization: Bearer TU_TOKEN"</span> \
  -H <span class="text-green-300">"Content-Type: application/json"</span> \
  -d <span class="text-green-300">'{
  "company": {
    "ruc": "20123456789",
    "name": "MI EMPRESA SAC",
    "address": "Av. Los Pinos 123",
    "ubigeo": "150101"
  },
  "customer": {
    "identity_document_type_id": "6",
    "number": "20987654321",
    "name": "CLIENTE EJEMPLO SAC"
  },
  "document": {
    "document_type_id": "01",
    "series": "F001",
    "number": "1",
    "date_of_issue": "2026-09-11",
    "time_of_issue": "12:00:00",
    "currency_type_id": "PEN",
    "total_taxed": 100.00,
    "total_igv": 18.00,
    "total_value": 100.00,
    "total": 118.00
  },
  "items": [
    {
      "unit_type_id": "NIU",
      "quantity": 1,
      "description": "Desarrollo de Software",
      "unit_value": 100.00,
      "unit_price": 118.00,
      "total_base_igv": 100.00,
      "percentage_igv": 18,
      "total_igv": 18.00,
      "total_value": 100.00,
      "affectation_igv_type_id": "10",
      "price_type_id": "01"
    }
  ]
}'</span></code></pre>
            </div>

            <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto code-content" id="send-php" data-group="send" style="display:none;">
<pre><code class="text-slate-300">&lt;?php
<span class="text-pink-400">\$client</span> = new \GuzzleHttp\Client();
<span class="text-pink-400">\$response</span> = \$client->post('https://signia.kore.pe/api/v1/documents/send', [
    'headers' => [
        'Authorization' => 'Bearer TU_TOKEN',
        'Accept' => 'application/json'
    ],
    'json' => [
        'company' => ['ruc' => '20123456789', 'name' => 'MI EMPRESA SAC', 'ubigeo' => '150101'],
        'customer' => ['identity_document_type_id' => '6', 'number' => '20987654321', 'name' => 'CLIENTE SAC'],
        'document' => [
            'document_type_id' => '01', 'series' => 'F001', 'number' => '1', 'date_of_issue' => '2026-09-11',
            'currency_type_id' => 'PEN', 'total_taxed' => 100.00, 'total_igv' => 18.00, 'total_value' => 100.00, 'total' => 118.00
        ],
        'items' => [
            [
                'unit_type_id' => 'NIU', 'quantity' => 1, 'description' => 'Servicio',
                'unit_value' => 100.00, 'unit_price' => 118.00, 'total_base_igv' => 100.00, 'percentage_igv' => 18,
                'total_igv' => 18.00, 'total_value' => 100.00, 'affectation_igv_type_id' => '10', 'price_type_id' => '01'
            ]
        ]
    ]
]);
echo \$response->getBody();</code></pre>
            </div>

            <!-- RESPUESTAS -->
            <h3 class="text-lg font-bold text-green-700 mt-12 mb-2 flex items-center gap-2">
                Respuesta Ideal (200 OK) - Síncrono
            </h3>
            <p class="text-sm text-slate-600 mb-4">Devuelto al instante cuando SUNAT/PSE responden rápido.</p>
            <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm mb-8"><code>{
  "success": true,
  "message": "Documento procesado correctamente.",
  "status": "accepted",
  "data": {
    "ticket": "SIG-X1Y2Z3...",
    "xml_url": "https://signia.kore.pe/storage/documents/201.../F001-1.xml",
    "cdr_url": "https://signia.kore.pe/storage/documents/201.../R-F001-1.zip",
    "pdf_url": "https://signia.kore.pe/storage/documents/201.../F001-1.pdf"
  },
  "xml_base64": "PD94bWwgdmVyc2...",
  "cdr_base64": "UEsDBBQAAAAI...",
  "pdf_base64": "JVBERi0xLjQu..."
}</code></pre>

            <h3 class="text-lg font-bold text-yellow-600 mt-8 mb-2 flex items-center gap-2">
                Respuesta Encolada (202 Accepted) - Asíncrono
            </h3>
            <p class="text-sm text-slate-600 mb-4">Devuelto si SUNAT da timeout o envías un Resumen Diario. Usa <code>/consult</code> con el ticket retornado.</p>
            <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm mb-8"><code>{
  "success": true,
  "message": "Intermitencia con SUNAT. El documento ha sido encolado para reintentos automáticos. Use el ticket para consultar.",
  "status": "in_process",
  "data": {
    "ticket": "SIG-A1B2C3D4E5F6"
  }
}</code></pre>

        </section>

        <!-- CONSULTAR TICKET -->
        <section id="consultar-ticket" class="mb-20 pt-8 border-t border-slate-200">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Consultar Ticket</h2>
            <p class="text-slate-600 mb-6">Usa este endpoint cuando Signia te devuelva un estado <code>in_process</code> (HTTP 202) con un ticket. Signia verificará constantemente la cola o consultará a SUNAT hasta obtener la respuesta final.</p>
            
            <div class="endpoint-box">
                <span class="method-post">POST</span> /documents/consult
            </div>

            <h3 class="text-lg font-semibold text-slate-800 mb-3">Body (JSON)</h3>
            <table>
                <thead><tr><th>Campo</th><th>Tipo</th><th>Descripción</th></tr></thead>
                <tbody>
                    <tr><td><code>ruc</code></td><td>string(11)</td><td>RUC de la empresa emisora.</td></tr>
                    <tr><td><code>ticket</code></td><td>string</td><td>Ticket devuelto por Signia (Ej. <code>SIG-A1B2C3...</code>).</td></tr>
                </tbody>
            </table>

            <div class="border-b border-slate-200 mb-4 flex gap-2 tabs-container" data-group="consult">
                <button class="code-tab active" data-target="consult-curl">cURL</button>
            </div>
            <div class="bg-slate-900 rounded-lg p-4 overflow-x-auto code-content" id="consult-curl" data-group="consult" style="display:block;">
<pre><code class="text-slate-300"><span class="text-pink-400">curl</span> -X POST https://signia.kore.pe/api/v1/documents/consult \
  -H <span class="text-green-300">"Authorization: Bearer TU_TOKEN"</span> \
  -H <span class="text-green-300">"Content-Type: application/json"</span> \
  -d <span class="text-green-300">'{
    "ruc": "20123456789",
    "ticket": "SIG-A1B2C3D4E5F6"
}'</span></code></pre>
            </div>
        </section>

        <!-- REINTENTAR -->
        <section id="reintentar" class="mb-20 pt-8 border-t border-slate-200">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Reintentar Envío</h2>
            <p class="text-slate-600 mb-6">Si un comprobante fue procesado y su estado final es <code>exception</code> o <code>rejected</code>, puedes volver a enviarlo a la cola. <strong>Debes incluir el payload JSON original completo.</strong></p>
            
            <div class="endpoint-box">
                <span class="method-post">POST</span> /documents/reintentar
            </div>

            <h3 class="text-lg font-semibold text-slate-800 mb-3">Body (JSON)</h3>
            <table>
                <thead><tr><th>Campo</th><th>Tipo</th><th>Descripción</th></tr></thead>
                <tbody>
                    <tr><td><code>ruc</code></td><td>string(11)</td><td>RUC de la empresa emisora.</td></tr>
                    <tr><td><code>serie</code></td><td>string</td><td>Serie del comprobante fallido (Ej. <code>F001</code>).</td></tr>
                    <tr><td><code>number</code></td><td>string</td><td>Número del comprobante fallido (Ej. <code>1</code>).</td></tr>
                    <tr><td><code>payload</code></td><td>object</td><td>El objeto JSON completo que enviaste originalmente.</td></tr>
                </tbody>
            </table>
        </section>

        <!-- OTRAS OPERACIONES B2B -->
        <section id="saldo" class="mb-20 pt-8 border-t border-slate-200">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Consultar Saldo B2B</h2>
            <p class="text-slate-600 mb-6">Obtén la cantidad de firmas disponibles en tu cuenta de Agencia.</p>
            <div class="endpoint-box"><span class="method-get">GET</span> /saldo</div>
            <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
  "success": true,
  "data": {
    "agency": "Mi Agencia SAC",
    "balance_pse": 1050,
    "balance_native": 5000
  }
}</code></pre>
        </section>

        <section id="listar-empresas" class="mb-20 pt-8 border-t border-slate-200">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Listar Empresas</h2>
            <p class="text-slate-600 mb-6">Obtén la lista de empresas asociadas a tu agencia.</p>
            <div class="endpoint-box"><span class="method-get">GET</span> /empresas</div>
        </section>

        <section id="crear-empresa" class="mb-20 pt-8 border-t border-slate-200">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Crear Empresa</h2>
            <p class="text-slate-600 mb-6">Afilia una nueva empresa bajo tu agencia.</p>
            <div class="endpoint-box"><span class="method-post">POST</span> /empresa/crear</div>
            <table>
                <thead><tr><th>Campo</th><th>Tipo</th><th>Descripción</th></tr></thead>
                <tbody>
                    <tr><td><code>ruc</code></td><td>string(11)</td><td>RUC de la nueva empresa.</td></tr>
                    <tr><td><code>business_name</code></td><td>string</td><td>Razón social.</td></tr>
                    <tr><td><code>sol_user</code></td><td>string</td><td>Usuario SOL de SUNAT.</td></tr>
                    <tr><td><code>sol_password</code></td><td>string</td><td>Clave SOL de SUNAT.</td></tr>
                </tbody>
            </table>
        </section>

        <section id="produccion-empresa" class="mb-20 pt-8 border-t border-slate-200">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Pasar a Producción</h2>
            <p class="text-slate-600 mb-6">Cambia el entorno de una empresa de <code>demo</code> a <code>production</code>.</p>
            <div class="endpoint-box"><span class="method-post">POST</span> /empresa/produccion</div>
            <table>
                <thead><tr><th>Campo</th><th>Tipo</th><th>Descripción</th></tr></thead>
                <tbody>
                    <tr><td><code>ruc</code></td><td>string(11)</td><td>RUC de la empresa a cambiar.</td></tr>
                    <tr><td><code>engine_type</code></td><td>string</td><td>Motor a usar: <code>native</code> (certificado propio) o <code>pse</code> (delegado).</td></tr>
                </tbody>
            </table>
        </section>

        <section id="certificado-empresa" class="mb-20 pt-8 border-t border-slate-200">
            <h2 class="text-3xl font-bold text-slate-900 mb-4">Subir Certificado</h2>
            <p class="text-slate-600 mb-6">Sube el archivo <code>.p12</code> del certificado digital si usarás el motor nativo.</p>
            <div class="endpoint-box"><span class="method-post">POST</span> /empresa/certificado</div>
            <table>
                <thead><tr><th>Campo</th><th>Tipo</th><th>Descripción</th></tr></thead>
                <tbody>
                    <tr><td><code>ruc</code></td><td>string(11)</td><td>RUC de la empresa.</td></tr>
                    <tr><td><code>certificate</code></td><td>file</td><td>Archivo .p12 o .pfx.</td></tr>
                    <tr><td><code>password</code></td><td>string</td><td>Contraseña del certificado.</td></tr>
                </tbody>
            </table>
        </section>

    </main>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            // Handle Tabs
            document.querySelectorAll('.code-tab').forEach(tab => {
                tab.addEventListener('click', () => {
                    const group = tab.getAttribute('data-group');
                    const targetId = tab.getAttribute('data-target');
                    
                    document.querySelectorAll(`.tabs-container[data-group="\${group}"] .code-tab`).forEach(t => {
                        t.classList.remove('active');
                        t.classList.add('text-slate-500');
                    });
                    
                    document.querySelectorAll(`.code-content[data-group="\${group}"]`).forEach(c => {
                        c.style.display = 'none';
                    });
                    
                    tab.classList.add('active');
                    tab.classList.remove('text-slate-500');
                    
                    const content = document.getElementById(targetId);
                    if(content) {
                        content.style.display = 'block';
                    }
                });
            });

            // Handle Scroll spy
            const sections = document.querySelectorAll('section');
            const navLinks = document.querySelectorAll('.sidebar .nav-link');

            window.addEventListener('scroll', () => {
                let current = '';
                sections.forEach(section => {
                    const sectionTop = section.offsetTop;
                    if (pageYOffset >= sectionTop - 100) {
                        current = section.getAttribute('id');
                    }
                });

                navLinks.forEach(link => {
                    link.classList.remove('active');
                    if (link.getAttribute('href').includes(current)) {
                        link.classList.add('active');
                    }
                });
            });
        });
    </script>
</body>
</html>
HTML;

file_put_contents('resources/views/docs.blade.php', $docs);
echo "Documentation rewritten with inline styles for display none and all endpoints.";

