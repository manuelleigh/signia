<?php
$file = 'resources/views/docs.blade.php';
$content = file_get_contents($file);

// 1. Sidebar Links
$sidebarLinks = <<<HTML
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
HTML;

$content = str_replace('<h3 class="sidebar-title">Recursos</h3>', $sidebarLinks . "\n\n            " . '<h3 class="sidebar-title">Recursos</h3>', $content);

// 2. TOC Links
$tocLinks = <<<HTML
                <a href="#listar-empresas" class="toc-link">Listar Empresas</a>
                <a href="#crear-empresa" class="toc-link">Crear Empresa</a>
                <a href="#produccion-empresa" class="toc-link">Pasar a Producción</a>
                <a href="#certificado-empresa" class="toc-link">Subir Certificado</a>
                <a href="#eliminar-empresa" class="toc-link">Eliminar Empresa</a>
                <a href="#saldo-agencia" class="toc-link">Consultar Saldo</a>
                <a href="#historial-docs" class="toc-link">Historial de Docs</a>
                <a href="#reintentar-doc" class="toc-link">Reintentar Envío</a>
HTML;

$content = str_replace('<a href="#catalogos" class="toc-link">Catálogos SUNAT</a>', $tocLinks . "\n                " . '<a href="#catalogos" class="toc-link">Catálogos SUNAT</a>', $content);
// handle encoding issue just in case
$content = str_replace('<a href="#catalogos" class="toc-link">Cat??logos SUNAT</a>', $tocLinks . "\n                " . '<a href="#catalogos" class="toc-link">Cat??logos SUNAT</a>', $content);

// 3. Main Content
$mainContent = file_get_contents('docs/api-b2b-empresas.md');
// I will convert the main content to basic HTML manually for the script.
$newSections = <<<HTML
                <!-- B2B EMPRESAS -->
                <section id="listar-empresas" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Listar Empresas</h1>
                    <p class="mb-6 leading-relaxed">Obtiene todas las empresas asociadas a la cuenta de tu agencia.</p>
                    <div class="endpoint-box"><span class="text-blue-600 font-bold">GET</span> /api/v1/empresas</div>
                </section>

                <section id="crear-empresa" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Crear Empresa</h1>
                    <p class="mb-6 leading-relaxed">Registra una nueva empresa en el sistema y provisiona sus credenciales.</p>
                    <div class="endpoint-box"><span class="method-post">POST</span> /api/v1/empresa/crear</div>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
    "ruc": "20123456789",
    "business_name": "Mi Empresa SAC",
    "environment": "demo",
    "engine_type": "qpse"
}</code></pre>
                </section>

                <section id="produccion-empresa" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Pasar Empresa a Producción</h1>
                    <p class="mb-6 leading-relaxed">Cambia el entorno de una empresa de demo a producción.</p>
                    <div class="endpoint-box"><span class="method-post">POST</span> /api/v1/empresa/produccion</div>
                    <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
    "ruc": "20123456789"
}</code></pre>
                </section>

                <section id="certificado-empresa" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Subir Certificado P12</h1>
                    <p class="mb-6 leading-relaxed">Sube el certificado digital para el motor nativo.</p>
                    <div class="endpoint-box"><span class="method-post">POST</span> /api/v1/empresa/certificado</div>
                    <p class="text-sm mt-2 text-gray-600">Requiere Content-Type: multipart/form-data con los campos <code>ruc</code>, <code>certificate</code> (archivo) y <code>password</code>.</p>
                </section>

                <section id="eliminar-empresa" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Eliminar Empresa</h1>
                    <p class="mb-6 leading-relaxed">Da de baja o suspende una empresa.</p>
                    <div class="endpoint-box"><span class="text-red-600 font-bold">DELETE</span> /api/v1/empresa/{ruc}</div>
                </section>

                <section id="saldo-agencia" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Consultar Saldo</h1>
                    <p class="mb-6 leading-relaxed">Permite consultar la bolsa de firmas de la agencia.</p>
                    <div class="endpoint-box"><span class="text-blue-600 font-bold">GET</span> /api/v1/saldo</div>
                </section>

                <section id="historial-docs" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Historial de Documentos</h1>
                    <p class="mb-6 leading-relaxed">Obtiene el listado paginado de documentos y sus links de descarga físicos (XML/CDR/PDF).</p>
                    <div class="endpoint-box"><span class="text-blue-600 font-bold">GET</span> /api/v1/documents?ruc={ruc}&status={status}</div>
                </section>

                <section id="reintentar-doc" class="mb-20 pt-8 border-t border-gray-100">
                    <h1 class="text-3xl font-bold text-gray-900 mb-6">Reintentar Envío</h1>
                    <p class="mb-6 leading-relaxed">Reintenta procesar un comprobante en excepción o pendiente.</p>
                    <div class="endpoint-box"><span class="method-post">POST</span> /api/v1/documents/reintentar</div>
                     <pre class="bg-gray-800 text-gray-100 p-4 rounded-lg overflow-x-auto text-sm"><code>{
    "ruc": "20123456789",
    "serie": "F001",
    "number": "1"
}</code></pre>
                </section>
HTML;

$content = str_replace('<!-- CATALOGOS SECTION -->', $newSections . "\n\n                " . '<!-- CATALOGOS SECTION -->', $content);

file_put_contents($file, $content);
?>
