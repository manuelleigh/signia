@extends('docs.layout')

@section('title', 'Pasar a producci&oacute;n')

@section('toc')
    <a href="#overview"        class="block text-slate-500 hover:text-slate-950">Descripci&oacute;n</a>
    <a href="#endpoint"        class="block text-slate-500 hover:text-slate-950">Endpoint</a>
    <a href="#auth"            class="block text-slate-500 hover:text-slate-950">Autenticaci&oacute;n</a>
    <a href="#headers"         class="block text-slate-500 hover:text-slate-950">Headers</a>
    <a href="#body"            class="block text-slate-500 hover:text-slate-950">Body</a>
    <a href="#prerequisites"   class="block text-slate-500 hover:text-slate-950">Prerrequisitos</a>
    <a href="#request-example" class="block text-slate-500 hover:text-slate-950">Ejemplo</a>
    <a href="#success"         class="block text-slate-500 hover:text-slate-950">Respuesta 200</a>
    <a href="#errors"          class="block text-slate-500 hover:text-slate-950">Errores</a>
@endsection

@section('content')
    <article class="mx-auto max-w-4xl">

        {{-- OVERVIEW --}}
        <section id="overview" class="scroll-mt-24">
            <div class="flex flex-wrap items-center gap-2 text-sm font-semibold">
                <span class="text-cyan-700">Empresas</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-500">Pasar a producci&oacute;n</span>
            </div>
            <h1 class="mt-5 text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl">Pasar empresa a producci&oacute;n</h1>
            <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600">Cambia el entorno de una empresa de <code>demo</code> a <code>production</code>. A partir de este momento, todos los comprobantes emitidos tendr&aacute;n validez legal frente a SUNAT y se descontar&aacute; saldo de la agencia.</p>
            <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm leading-6 text-rose-900">
                <strong class="text-rose-950">Acci&oacute;n irreversible por API:</strong> Una vez que una empresa se pasa a producci&oacute;n, no puede regresar a demo mediante la API. Los comprobantes reales no se pueden anular sin un proceso tributario (nota de cr&eacute;dito o baja).
            </div>
        </section>

        {{-- ENDPOINT --}}
        <section id="endpoint" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Endpoint</h2>
            <div class="mt-5 flex min-w-0 items-center overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <span class="self-stretch bg-emerald-500 px-4 py-4 font-mono text-sm font-bold text-white">POST</span>
                <code class="min-w-0 overflow-x-auto px-4 py-4 text-sm font-semibold text-slate-800">/api/v1/empresa/produccion</code>
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
                        <tr><td class="px-4 py-4 font-mono font-semibold text-slate-900">Content-Type</td><td class="px-4 py-4 font-mono text-slate-600">application/json</td><td class="hidden px-4 py-4 sm:table-cell">S&iacute;</td></tr>
                        <tr><td class="px-4 py-4 font-mono font-semibold text-slate-900">Authorization</td><td class="px-4 py-4 font-mono text-slate-600">Bearer &lt;access_token&gt;</td><td class="hidden px-4 py-4 sm:table-cell">S&iacute;</td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- BODY --}}
        <section id="body" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Body</h2>
            <div class="mt-5 overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-4 py-3">Campo</th><th class="px-4 py-3">Tipo</th><th class="hidden px-4 py-3 md:table-cell">Descripci&oacute;n</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">ruc</code><span class="ml-1 text-rose-600">*</span></td>
                            <td class="px-4 py-4">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. RUC de 11 d&iacute;gitos de la empresa que se pasar&aacute; a producci&oacute;n.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- PRERREQUISITOS --}}
        <section id="prerequisites" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Prerrequisitos</h2>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                    <p class="font-semibold text-slate-950">Para motor PSE</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">La empresa debe haber autorizado a nuestro partner PSE desde su portal de Clave SOL. Al invocar este endpoint, Signia notifica al motor PSE para activar la emisi&oacute;n real.</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                    <p class="font-semibold text-slate-950">Para motor Nativo</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Aseg&uacute;rate de haber subido el certificado digital (.p12 / .pem) usando el endpoint <code>/empresa/certificado</code> antes o inmediatamente despu&eacute;s de este cambio.</p>
                </div>
            </div>
        </section>

        {{-- EJEMPLO --}}
        <section id="request-example" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Ejemplo de solicitud</h2>
            <div class="mt-5">
                <x-docs.code id="prod-request" label="cURL">curl --request POST 'https://signia.kore.pe/api/v1/empresa/produccion' \
  --header 'Accept: application/json' \
  --header 'Content-Type: application/json' \
  --header 'Authorization: Bearer 1|nY2k7...access_token' \
  --data-raw '{
    "ruc": "20100070970"
  }'</x-docs.code>
            </div>
        </section>

        {{-- RESPUESTA 200 --}}
        <section id="success" class="mt-14 scroll-mt-24">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-slate-950">Respuesta exitosa</h2>
                <span class="rounded-full bg-emerald-100 px-2.5 py-1 font-mono text-xs font-bold text-emerald-800">200 OK</span>
            </div>
            <p class="mt-3 leading-7 text-slate-600">Devuelve la confirmaci&oacute;n y el nuevo estado del entorno.</p>
            <div class="mt-5">
                <x-docs.code id="prod-200">{
  "success": true,
  "message": "Empresa actualizada a producción exitosamente.",
  "data": {
    "ruc": "20100070970",
    "environment": "production"
  }
}</x-docs.code>
            </div>
        </section>

        {{-- ERRORES --}}
        <section id="errors" class="mb-20 mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Errores</h2>
            <div class="mt-5 space-y-4">
                
                <details class="group rounded-xl border border-slate-200 p-5" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">400</code> Ya est&aacute; en producci&oacute;n</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">La empresa ya tiene el entorno configurado como producci&oacute;n.</p>
                    <div class="mt-4"><x-docs.code id="prod-400-ya">{
  "success": false,
  "message": "La empresa ya se encuentra en producción."
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">400</code> Error de motor PSE</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">El motor PSE externo rechaz&oacute; el pase a producci&oacute;n. Esto puede ocurrir si falta alg&uacute;n tr&aacute;mite o la configuraci&oacute;n externa es incorrecta.</p>
                    <div class="mt-4"><x-docs.code id="prod-400-pse">{
  "success": false,
  "message": "Error al pasar a producción en el motor PSE.",
  "details": { ... }
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">404</code> RUC no encontrado</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">No se encontr&oacute; la empresa bajo tu cuenta de agencia.</p>
                    <div class="mt-4"><x-docs.code id="prod-404">{
  "success": false,
  "message": "RUC no encontrado en tu cuenta."
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">500</code> Error de ID externo</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Signia no pudo recuperar el ID de la empresa en el motor externo para completar el pase a producci&oacute;n.</p>
                    <div class="mt-4"><x-docs.code id="prod-500">{
  "success": false,
  "message": "No se pudo recuperar el ID externo del Motor PSE."
}</x-docs.code></div>
                </details>
            </div>
        </section>

    </article>
@endsection
