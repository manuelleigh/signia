@extends('docs.layout')

@section('title', 'Registrar empresa')

@section('toc')
    <a href="#overview"        class="block text-slate-500 hover:text-slate-950">Descripci&oacute;n</a>
    <a href="#endpoint"        class="block text-slate-500 hover:text-slate-950">Endpoint</a>
    <a href="#auth"            class="block text-slate-500 hover:text-slate-950">Autenticaci&oacute;n</a>
    <a href="#headers"         class="block text-slate-500 hover:text-slate-950">Headers</a>
    <a href="#body"            class="block text-slate-500 hover:text-slate-950">Body</a>
    <a href="#engines"         class="block text-slate-500 hover:text-slate-950">Motores disponibles</a>
    <a href="#formats"         class="block text-slate-500 hover:text-slate-950">Formatos</a>
    <a href="#request-example" class="block text-slate-500 hover:text-slate-950">Ejemplo</a>
    <a href="#success"         class="block text-slate-500 hover:text-slate-950">Respuesta 201</a>
    <a href="#errors"          class="block text-slate-500 hover:text-slate-950">Errores</a>
@endsection

@section('content')
    <article class="mx-auto max-w-4xl">

        {{-- OVERVIEW --}}
        <section id="overview" class="scroll-mt-24">
            <div class="flex flex-wrap items-center gap-2 text-sm font-semibold">
                <span class="text-cyan-700">Empresas</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-500">Registrar empresa</span>
            </div>
            <h1 class="mt-5 text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl">Registrar nueva empresa</h1>
            <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600">Registra un nuevo emisor (RUC) bajo tu agencia y opcionalmente lo vincula con el motor PSE externo. Este es el primer paso antes de poder enviar comprobantes a SUNAT para una nueva empresa.</p>
        </section>

        {{-- ENDPOINT --}}
        <section id="endpoint" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Endpoint</h2>
            <div class="mt-5 flex min-w-0 items-center overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <span class="self-stretch bg-emerald-500 px-4 py-4 font-mono text-sm font-bold text-white">POST</span>
                <code class="min-w-0 overflow-x-auto px-4 py-4 text-sm font-semibold text-slate-800">/api/v1/empresa/crear</code>
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
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. RUC de 11 d&iacute;gitos de la empresa.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">business_name</code><span class="ml-1 text-rose-600">*</span></td>
                            <td class="px-4 py-4">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. Raz&oacute;n social del emisor (m&aacute;x. 255 caracteres).</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">engine_type</code><span class="ml-1 text-rose-600">*</span></td>
                            <td class="px-4 py-4">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. El motor de env&iacute;o a usar. Valores permitidos: <code>pse</code> o <code>native</code>.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">environment</code></td>
                            <td class="px-4 py-4">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Opcional. Valores: <code>demo</code> o <code>production</code>. Por defecto es <code>demo</code>.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- MOTORES --}}
        <section id="engines" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Motores disponibles</h2>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Motor PSE (<code>pse</code>)</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Al seleccionar este motor, Signia realizar&aacute; una llamada externa al motor PSE autorizado  para crear la cuenta de la empresa y gestionar&aacute; autom&aacute;ticamente la firma y comunicaci&oacute;n con SUNAT como Proveedor de Servicios Electr&oacute;nicos.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Motor Nativo (<code>native</code>)</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Crea el registro solo en Signia. Este modo requiere que subas el certificado digital de la empresa usando el endpoint <code>/empresa/certificado</code> y permite a la empresa firmar y enviar directamente a SUNAT sin intermediar un PSE.</p>
                </div>
            </div>
        </section>

        {{-- FORMATOS --}}
        <section id="formats" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Formatos y validaciones</h2>
            <ul class="mt-5 space-y-3 rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm leading-6 text-slate-700">
                <li><strong class="text-slate-950">RUC:</strong> exactamente 11 d&iacute;gitos num&eacute;ricos.</li>
                <li><strong class="text-slate-950">RUC &Uacute;nico:</strong> Un RUC solo puede estar registrado una vez por agencia.</li>
                <li><strong class="text-slate-950">Pase a producci&oacute;n autom&aacute;tico:</strong> Si se env&iacute;a <code>environment: 'production'</code> con motor <code>pse</code>, Signia llamar&aacute; autom&aacute;ticamente al webhook del PSE para pasar la cuenta a producci&oacute;n en un solo paso.</li>
            </ul>
        </section>

        {{-- EJEMPLO --}}
        <section id="request-example" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Ejemplo de solicitud</h2>
            <div class="mt-5">
                <x-docs.code id="crear-request" label="cURL">curl --request POST 'https://signia.kore.pe/api/v1/empresa/crear' \
  --header 'Accept: application/json' \
  --header 'Content-Type: application/json' \
  --header 'Authorization: Bearer 1|nY2k7...access_token' \
  --data-raw '{
    "ruc": "20100070970",
    "business_name": "EMPRESA DEMO S.A.C.",
    "engine_type": "pse",
    "environment": "demo"
  }'</x-docs.code>
            </div>
        </section>

        {{-- RESPUESTA 201 --}}
        <section id="success" class="mt-14 scroll-mt-24">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-slate-950">Respuesta exitosa</h2>
                <span class="rounded-full bg-emerald-100 px-2.5 py-1 font-mono text-xs font-bold text-emerald-800">201 Created</span>
            </div>
            <p class="mt-3 leading-7 text-slate-600">En caso de &eacute;xito, devuelve los datos de la empresa. Si el motor es <code>pse</code>, tambi&eacute;n devuelve las credenciales de acceso de la cuenta externa.</p>
            <div class="mt-5">
                <x-docs.code id="crear-201">{
  "success": true,
  "message": "Empresa registrada satisfactoriamente",
  "data": {
    "ruc": "20100070970",
    "business_name": "EMPRESA DEMO S.A.C.",
    "environment": "demo",
    "engine_type": "pse",
    "pse_username": "DEMO20100070970",
    "pse_password": "abc123xyz"
  }
}</x-docs.code>
            </div>
            <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 p-4 text-sm leading-6 text-rose-900">
                <strong class="text-rose-950">Advertencia sobre seguridad:</strong> La contrase&ntilde;a (<code>pse_password</code>) <strong>solo se mostrar&aacute; una vez</strong> en la respuesta de creaci&oacute;n. Gu&aacute;rdala de forma segura.
            </div>
        </section>

        {{-- ERRORES --}}
        <section id="errors" class="mb-20 mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Errores</h2>
            <div class="mt-5 space-y-4">

                <details class="group rounded-xl border border-slate-200 p-5" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">400</code> RUC ya registrado</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">El RUC ya existe bajo tu agencia.</p>
                    <div class="mt-4"><x-docs.code id="crear-400-dup">{
  "success": false,
  "message": "El RUC ya se encuentra registrado en tu cuenta."
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">400</code> Error de motor PSE</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">El motor de env&iacute;os PSE rechaz&oacute; la creaci&oacute;n. El campo <code>details</code> contendr&aacute; la respuesta exacta del motor.</p>
                    <div class="mt-4"><x-docs.code id="crear-400-pse">{
  "success": false,
  "message": "Error al registrar en el motor PSE.",
  "details": { ... }
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">401</code> No autenticado</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Falta token o agencia vinculada.</p>
                    <div class="mt-4"><x-docs.code id="crear-401">{
  "success": false,
  "message": "Agencia no encontrada."
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-amber-600">422</code> Validaci&oacute;n fallida</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">El RUC no tiene 11 d&iacute;gitos, falta el nombre o el motor es inv&aacute;lido.</p>
                    <div class="mt-4"><x-docs.code id="crear-422">{
  "message": "The ruc field is required.",
  "errors": {
    "ruc": ["The ruc field is required."]
  }
}</x-docs.code></div>
                </details>

            </div>
        </section>

    </article>
@endsection

