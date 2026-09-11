@extends('docs.layout')

@section('title', 'Subir certificado')

@section('toc')
    <a href="#overview"        class="block text-slate-500 hover:text-slate-950">Descripci&oacute;n</a>
    <a href="#endpoint"        class="block text-slate-500 hover:text-slate-950">Endpoint</a>
    <a href="#auth"            class="block text-slate-500 hover:text-slate-950">Autenticaci&oacute;n</a>
    <a href="#headers"         class="block text-slate-500 hover:text-slate-950">Headers</a>
    <a href="#body"            class="block text-slate-500 hover:text-slate-950">Body (form-data)</a>
    <a href="#formats"         class="block text-slate-500 hover:text-slate-950">Formatos</a>
    <a href="#request-example" class="block text-slate-500 hover:text-slate-950">Ejemplo cURL</a>
    <a href="#success"         class="block text-slate-500 hover:text-slate-950">Respuesta 200</a>
    <a href="#errors"          class="block text-slate-500 hover:text-slate-950">Errores</a>
    <a href="#security"        class="block text-slate-500 hover:text-slate-950">Seguridad</a>
@endsection

@section('content')
    <article class="mx-auto max-w-4xl">

        {{-- OVERVIEW --}}
        <section id="overview" class="scroll-mt-24">
            <div class="flex flex-wrap items-center gap-2 text-sm font-semibold">
                <span class="text-cyan-700">Empresas</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-500">Subir certificado</span>
            </div>
            <h1 class="mt-5 text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl">Subir certificado digital</h1>
            <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600">Sube y configura el certificado digital (.p12 o .pem) requerido para firmar comprobantes. Es indispensable para empresas que operan con el motor <code>native</code>. Para el motor <code>pse</code> el certificado se delega autom&aacute;ticamente y este endpoint no es necesario.</p>
        </section>

        {{-- ENDPOINT --}}
        <section id="endpoint" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Endpoint</h2>
            <div class="mt-5 flex min-w-0 items-center overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <span class="self-stretch bg-emerald-500 px-4 py-4 font-mono text-sm font-bold text-white">POST</span>
                <code class="min-w-0 overflow-x-auto px-4 py-4 text-sm font-semibold text-slate-800">/api/v1/empresa/certificado</code>
                <span class="ml-auto mr-4 hidden shrink-0 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 sm:block">Requiere Bearer token</span>
            </div>
        </section>

        {{-- AUTH --}}
        <section id="auth" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Autenticaci&oacute;n</h2>
            <p class="mt-3 leading-7 text-slate-600">Requiere un token Bearer v&aacute;lido obtenido con <a href="{{ route('docs.authentication') }}" class="font-semibold text-cyan-700 underline underline-offset-2 hover:text-cyan-900">POST /api/v1/auth/token</a>.</p>
        </section>

        {{-- HEADERS --}}
        <section id="headers" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Headers</h2>
            <div class="mt-5 overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-4 py-3">Header</th><th class="px-4 py-3">Valor</th><th class="hidden px-4 py-3 sm:table-cell">Requerido</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr><td class="px-4 py-4 font-mono font-semibold text-slate-900">Accept</td><td class="px-4 py-4 font-mono text-slate-600">application/json</td><td class="hidden px-4 py-4 sm:table-cell">S&iacute;</td></tr>
                        <tr><td class="px-4 py-4 font-mono font-semibold text-slate-900">Content-Type</td><td class="px-4 py-4 font-mono text-slate-600">multipart/form-data</td><td class="hidden px-4 py-4 sm:table-cell">S&iacute;</td></tr>
                        <tr><td class="px-4 py-4 font-mono font-semibold text-slate-900">Authorization</td><td class="px-4 py-4 font-mono text-slate-600">Bearer &lt;access_token&gt;</td><td class="hidden px-4 py-4 sm:table-cell">S&iacute;</td></tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
                <strong class="text-amber-950">Importante:</strong> Al subir un archivo, el <code>Content-Type</code> debe ser <strong>obligatoriamente</strong> <code>multipart/form-data</code>. No env&iacute;es JSON en este endpoint.
            </div>
        </section>

        {{-- BODY --}}
        <section id="body" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Body (form-data)</h2>
            <div class="mt-5 overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-4 py-3">Campo</th><th class="px-4 py-3">Tipo</th><th class="hidden px-4 py-3 md:table-cell">Descripci&oacute;n</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">ruc</code><span class="ml-1 text-rose-600">*</span></td>
                            <td class="px-4 py-4">text</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. RUC de 11 d&iacute;gitos al que pertenece el certificado.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">certificate</code><span class="ml-1 text-rose-600">*</span></td>
                            <td class="px-4 py-4">file</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. Archivo del certificado digital. Formatos permitidos: <code>.p12</code>, <code>.pem</code>.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">password</code><span class="ml-1 text-rose-600">*</span></td>
                            <td class="px-4 py-4">text</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. Contrase&ntilde;a del archivo del certificado.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- FORMATOS --}}
        <section id="formats" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Comportamiento del endpoint</h2>
            <ul class="mt-5 space-y-3 rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm leading-6 text-slate-700">
                <li><strong class="text-slate-950">Actualizaci&oacute;n:</strong> Si la empresa ya tiene un certificado, subir uno nuevo lo reemplazar&aacute; e invalidar&aacute; el anterior autom&aacute;ticamente. Usa este endpoint tanto para alta como para renovaci&oacute;n por vencimiento.</li>
                <li><strong class="text-slate-950">Formato del archivo:</strong> Solo se aceptan extensiones <code>.p12</code> o <code>.pem</code>. M&aacute;ximo 5 MB.</li>
            </ul>
        </section>

        {{-- EJEMPLO --}}
        <section id="request-example" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Ejemplo de solicitud cURL</h2>
            <div class="mt-5">
                <x-docs.code id="cert-request" label="cURL">curl --request POST 'https://signia.kore.pe/api/v1/empresa/certificado' \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer 1|nY2k7...access_token' \
  --form 'ruc="20100070970"' \
  --form 'certificate=@"/ruta/absoluta/al/certificado.p12"' \
  --form 'password="mi-password-seguro"'</x-docs.code>
            </div>
            <p class="mt-4 text-sm text-slate-500">Nota: el par&aacute;metro <code>@</code> en cURL indica que el valor es la ruta a un archivo que debe ser enviado.</p>
        </section>

        {{-- RESPUESTA 200 --}}
        <section id="success" class="mt-14 scroll-mt-24">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-slate-950">Respuesta exitosa</h2>
                <span class="rounded-full bg-emerald-100 px-2.5 py-1 font-mono text-xs font-bold text-emerald-800">200 OK</span>
            </div>
            <p class="mt-3 leading-7 text-slate-600">Devuelve la ruta en la que el certificado fue almacenado de forma segura.</p>
            <div class="mt-5">
                <x-docs.code id="cert-200">{
  "success": true,
  "message": "Certificado subido y configurado correctamente.",
  "data": {
    "ruc": "20100070970",
    "certificate_path": "certificates/20100070970/certificado.p12"
  }
}</x-docs.code>
            </div>
        </section>

        {{-- ERRORES --}}
        <section id="errors" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Errores</h2>
            <div class="mt-5 space-y-4">
                
                <details class="group rounded-xl border border-slate-200 p-5" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">404</code> RUC no encontrado</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">No se encontr&oacute; la empresa bajo tu cuenta de agencia o el RUC es incorrecto.</p>
                    <div class="mt-4"><x-docs.code id="cert-404">{
  "success": false,
  "message": "RUC no encontrado en tu cuenta."
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-amber-600">422</code> Validaci&oacute;n fallida</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">El RUC no tiene 11 d&iacute;gitos, falta el archivo, el archivo no es un formato permitido, o falta la contrase&ntilde;a.</p>
                    <div class="mt-4"><x-docs.code id="cert-422">{
  "message": "The certificate must be a file of type: p12, pem.",
  "errors": {
    "certificate": ["The certificate must be a file of type: p12, pem."]
  }
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">401</code> No autenticado</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Falta token o agencia vinculada.</p>
                    <div class="mt-4"><x-docs.code id="cert-401">{
  "message": "Unauthenticated."
}</x-docs.code></div>
                </details>

            </div>
        </section>

        {{-- SEGURIDAD --}}
        <section id="security" class="mb-20 mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Manejo de seguridad</h2>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Cifrado de contrase&ntilde;as</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">La contrase&ntilde;a enviada en el campo <code>password</code> se cifra autom&aacute;ticamente usando el <code>APP_KEY</code> de Signia antes de almacenarse en la base de datos (AES-256). Nunca se guarda en texto plano.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Restricci&oacute;n de lectura</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Ni la contrase&ntilde;a ni el archivo del certificado se exponen o devuelven nunca a trav&eacute;s de la API. Este endpoint es estrictamente de escritura (write-only).</p>
                </div>
            </div>
        </section>

    </article>
@endsection
