@extends('docs.layout')

@section('title', 'Obtener token')

@section('toc')
    <a href="#endpoint" class="block text-slate-500 hover:text-slate-950">Endpoint</a>
    <a href="#headers" class="block text-slate-500 hover:text-slate-950">Headers</a>
    <a href="#body" class="block text-slate-500 hover:text-slate-950">Body</a>
    <a href="#formats" class="block text-slate-500 hover:text-slate-950">Formatos</a>
    <a href="#request-example" class="block text-slate-500 hover:text-slate-950">Ejemplo</a>
    <a href="#success" class="block text-slate-500 hover:text-slate-950">Respuesta 200</a>
    <a href="#errors" class="block text-slate-500 hover:text-slate-950">Errores</a>
    <a href="#timeouts" class="block text-slate-500 hover:text-slate-950">Conexión y timeouts</a>
@endsection

@section('content')
    <article class="mx-auto max-w-4xl">
        <section id="overview" class="scroll-mt-24">
            <div class="flex flex-wrap items-center gap-2 text-sm font-semibold">
                <span class="text-cyan-700">Autenticación</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-500">Obtener token</span>
            </div>
            <h1 class="mt-5 text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl">Obtener un token de acceso</h1>
            <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600">Autentica una cuenta de Agencia y genera un token Bearer temporal. El token dura 10 minutos y permite invocar los endpoints protegidos de la API v1.</p>

            <div id="environments" class="mt-8 grid gap-3 sm:grid-cols-2 scroll-mt-24">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Base URL</p>
                    <code class="mt-2 block break-all text-sm font-semibold text-slate-800">https://signia.kore.pe/api/v1</code>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Entornos</p>
                    <p class="mt-2 text-sm font-semibold text-slate-800">Demo y producción</p>
                    <p class="mt-1 text-xs leading-5 text-slate-500">La autenticación es común; el entorno se determina por el RUC usado posteriormente.</p>
                </div>
            </div>
        </section>

        <section id="endpoint" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Endpoint</h2>
            <div class="mt-5 flex min-w-0 items-center overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <span class="self-stretch bg-emerald-500 px-4 py-4 font-mono text-sm font-bold text-white">POST</span>
                <code class="min-w-0 overflow-x-auto px-4 py-4 text-sm font-semibold text-slate-800">/api/v1/auth/token</code>
                <span class="ml-auto mr-4 hidden shrink-0 rounded-full bg-slate-100 px-3 py-1 text-xs font-semibold text-slate-500 sm:block">No requiere token</span>
            </div>
        </section>

        <section id="headers" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Headers</h2>
            <div class="mt-5 overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-4 py-3">Header</th><th class="px-4 py-3">Valor</th><th class="hidden px-4 py-3 sm:table-cell">Requerido</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr><td class="px-4 py-4 font-mono font-semibold text-slate-900">Accept</td><td class="px-4 py-4 font-mono text-slate-600">application/json</td><td class="hidden px-4 py-4 sm:table-cell">Sí</td></tr>
                        <tr><td class="px-4 py-4 font-mono font-semibold text-slate-900">Content-Type</td><td class="px-4 py-4 font-mono text-slate-600">application/json</td><td class="hidden px-4 py-4 sm:table-cell">Sí</td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="body" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Body</h2>
            <p class="mt-3 leading-7 text-slate-600">Envía un objeto JSON con las credenciales de la Agencia y un nombre que identifique al sistema consumidor.</p>
            <div class="mt-5 overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-4 py-3">Campo</th><th class="px-4 py-3">Tipo</th><th class="hidden px-4 py-3 md:table-cell">Reglas</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr><td class="px-4 py-4"><code class="font-semibold text-slate-950">email</code><span class="ml-1 text-rose-600">*</span><p class="mt-1 text-xs text-slate-500 md:hidden">Correo válido de una Agencia.</p></td><td class="px-4 py-4">string</td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. Debe ser un correo válido registrado en Signia.</td></tr>
                        <tr><td class="px-4 py-4"><code class="font-semibold text-slate-950">password</code><span class="ml-1 text-rose-600">*</span><p class="mt-1 text-xs text-slate-500 md:hidden">Contraseña de la cuenta.</p></td><td class="px-4 py-4">string</td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. Se compara de forma segura con la credencial almacenada.</td></tr>
                        <tr><td class="px-4 py-4"><code class="font-semibold text-slate-950">device_name</code><span class="ml-1 text-rose-600">*</span><p class="mt-1 text-xs text-slate-500 md:hidden">Máximo 80 caracteres.</p></td><td class="px-4 py-4">string</td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. Entre 1 y 80 caracteres. Identifica el ERP, servidor o integración.</td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        <section id="formats" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Formatos y validaciones</h2>
            <ul class="mt-5 space-y-3 rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm leading-6 text-slate-700">
                <li><strong class="text-slate-950">Codificación:</strong> UTF-8.</li>
                <li><strong class="text-slate-950">Formato:</strong> JSON válido; no se admite multipart ni form-data.</li>
                <li><strong class="text-slate-950">Vigencia:</strong> el token expira 600 segundos después de emitirse.</li>
                <li><strong class="text-slate-950">Límite:</strong> máximo 6 intentos por minuto. Al excederlo, la API responde 429.</li>
                <li><strong class="text-slate-950">Seguridad:</strong> usa siempre HTTPS y nunca expongas la contraseña o el token en logs, URLs o código frontend público.</li>
            </ul>
        </section>

        <section id="request-example" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Ejemplo de solicitud</h2>
            <div class="mt-5">
                <x-docs.code id="auth-request" label="cURL">curl --request POST 'https://signia.kore.pe/api/v1/auth/token' \
  --header 'Accept: application/json' \
  --header 'Content-Type: application/json' \
  --data-raw '{
    "email": "integraciones@agencia.pe",
    "password": "tu-contraseña-segura",
    "device_name": "ERP Producción"
  }'</x-docs.code>
            </div>
        </section>

        <section id="success" class="mt-14 scroll-mt-24">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-slate-950">Respuesta exitosa</h2>
                <span class="rounded-full bg-emerald-100 px-2.5 py-1 font-mono text-xs font-bold text-emerald-800">200 OK</span>
            </div>
            <p class="mt-3 leading-7 text-slate-600">Guarda el valor de <code>access_token</code> únicamente en el servidor y úsalo como <code>Authorization: Bearer &lt;token&gt;</code> en las siguientes peticiones.</p>
            <div class="mt-5">
                <x-docs.code id="auth-success">{
  "access_token": "1|nY2k7...token_recortado...9Q",
  "token_type": "Bearer",
  "expires_in": 600,
  "expires_at": "2026-09-11T20:40:00+00:00"
}</x-docs.code>
            </div>
            <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-900"><strong>Importante:</strong> el token completo se muestra una sola vez. Solicita uno nuevo cuando expire; no intentes reutilizarlo.</div>
        </section>

        <section id="errors" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Errores</h2>
            <div class="mt-5 space-y-4">
                <details class="group rounded-xl border border-slate-200 p-5" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">401</code> Credenciales incorrectas</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <div class="mt-4"><x-docs.code id="auth-401">{
  "message": "Las credenciales proporcionadas son incorrectas.",
  "errors": {
    "email": ["Las credenciales proporcionadas son incorrectas."]
  }
}</x-docs.code></div>
                </details>
                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-amber-600">422</code> Error de validación</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Ocurre cuando falta un campo o su formato no es válido.</p>
                    <div class="mt-4"><x-docs.code id="auth-422">{
  "message": "El nombre del dispositivo es obligatorio.",
  "errors": {
    "device_name": ["El nombre del dispositivo es obligatorio."]
  }
}</x-docs.code></div>
                </details>
                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-violet-600">429</code> Demasiados intentos</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Espera el número de segundos indicado en el header <code>Retry-After</code> antes de volver a intentar.</p>
                    <div class="mt-4"><x-docs.code id="auth-429">{
  "message": "Too Many Attempts."
}</x-docs.code></div>
                </details>
            </div>
        </section>

        <section id="timeouts" class="mb-20 mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Errores de conexión y timeouts</h2>
            <p class="mt-3 leading-7 text-slate-600">Este endpoint no tiene estados pendiente, en proceso o rechazado: responde inmediatamente con éxito o error. Una desconexión o timeout ocurre antes de recibir una respuesta HTTP y debe manejarse en el cliente.</p>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-200 p-5"><p class="font-semibold text-slate-950">Conexión fallida</p><p class="mt-2 text-sm leading-6 text-slate-600">Verifica DNS, HTTPS y conectividad. No asumas que se creó un token si no recibiste un 200.</p></div>
                <div class="rounded-xl border border-slate-200 p-5"><p class="font-semibold text-slate-950">Timeout recomendado</p><p class="mt-2 text-sm leading-6 text-slate-600">Configura 10 segundos. Reintenta hasta 2 veces con espera incremental y sin ejecutar intentos en paralelo.</p></div>
            </div>
        </section>
    </article>
@endsection
