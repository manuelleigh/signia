@extends('docs.layout')

@section('title', 'Consultar saldo')

@section('toc')
    <a href="#overview"        class="block text-slate-500 hover:text-slate-950">Descripci&oacute;n</a>
    <a href="#endpoint"        class="block text-slate-500 hover:text-slate-950">Endpoint</a>
    <a href="#auth"            class="block text-slate-500 hover:text-slate-950">Autenticaci&oacute;n</a>
    <a href="#headers"         class="block text-slate-500 hover:text-slate-950">Headers</a>
    <a href="#request-example" class="block text-slate-500 hover:text-slate-950">Ejemplo</a>
    <a href="#success"         class="block text-slate-500 hover:text-slate-950">Respuesta 200</a>
    <a href="#consumption"     class="block text-slate-500 hover:text-slate-950">Consumo de saldo</a>
    <a href="#errors"          class="block text-slate-500 hover:text-slate-950">Errores</a>
@endsection

@section('content')
    <article class="mx-auto max-w-4xl">

        {{-- OVERVIEW --}}
        <section id="overview" class="scroll-mt-24">
            <div class="flex flex-wrap items-center gap-2 text-sm font-semibold">
                <span class="text-cyan-700">Agencia</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-500">Consultar saldo</span>
            </div>
            <h1 class="mt-5 text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl">Consultar saldo de cr&eacute;ditos</h1>
            <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600">Devuelve el balance de cr&eacute;ditos disponibles para tu agencia. Signia maneja dos bolsas de saldo independientes seg&uacute;n el motor de env&iacute;o (PSE o Nativo).</p>
        </section>

        {{-- ENDPOINT --}}
        <section id="endpoint" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Endpoint</h2>
            <div class="mt-5 flex min-w-0 items-center overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <span class="self-stretch bg-blue-500 px-4 py-4 font-mono text-sm font-bold text-white">GET</span>
                <code class="min-w-0 overflow-x-auto px-4 py-4 text-sm font-semibold text-slate-800">/api/v1/saldo</code>
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
                        <tr><td class="px-4 py-4 font-mono font-semibold text-slate-900">Authorization</td><td class="px-4 py-4 font-mono text-slate-600">Bearer &lt;access_token&gt;</td><td class="hidden px-4 py-4 sm:table-cell">S&iacute;</td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- EJEMPLO --}}
        <section id="request-example" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Ejemplo de solicitud</h2>
            <div class="mt-5">
                <x-docs.code id="saldo-request" label="cURL">curl --request GET 'https://signia.kore.pe/api/v1/saldo' \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer 1|nY2k7...access_token'</x-docs.code>
            </div>
        </section>

        {{-- RESPUESTA 200 --}}
        <section id="success" class="mt-14 scroll-mt-24">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-slate-950">Respuesta exitosa</h2>
                <span class="rounded-full bg-emerald-100 px-2.5 py-1 font-mono text-xs font-bold text-emerald-800">200 OK</span>
            </div>
            <p class="mt-3 leading-7 text-slate-600">Devuelve el saldo de ambas bolsas en n&uacute;meros enteros (cantidad de comprobantes).</p>
            <div class="mt-5">
                <x-docs.code id="saldo-200">{
  "success": true,
  "data": {
    "balance_pse": 150,
    "balance_native": 75
  }
}</x-docs.code>
            </div>
            <div class="mt-5 overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-4 py-3">Campo</th><th class="px-4 py-3">Tipo</th><th class="hidden px-4 py-3 md:table-cell">Descripci&oacute;n</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">balance_pse</code></td>
                            <td class="px-4 py-4">integer</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Cr&eacute;ditos para emitir bajo el motor PSE (firmado delegado).</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">balance_native</code></td>
                            <td class="px-4 py-4">integer</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Cr&eacute;ditos para emitir bajo el motor Nativo (firmado local con tu certificado).</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- CONSUMO --}}
        <section id="consumption" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">C&oacute;mo se consumen los cr&eacute;ditos</h2>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Reglas de descuento</p>
                    <ul class="mt-2 ml-4 list-disc space-y-1 text-sm text-slate-600">
                        <li>Cada llamada a <code>/documents/send</code> descuenta 1 cr&eacute;dito de la bolsa correspondiente.</li>
                        <li>Cada llamada a <code>/documents/reintentar</code> descuenta 1 cr&eacute;dito.</li>
                        <li>El entorno <code>demo</code> <strong>no consume</strong> cr&eacute;ditos.</li>
                    </ul>
                </div>
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Agotamiento de saldo</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Si la bolsa correspondiente llega a 0, los endpoints de env&iacute;o y reintento devolver&aacute;n un error <code>400 Bad Request</code>. Los cr&eacute;ditos son recargados por los administradores de Signia desde el panel web.</p>
                </div>
            </div>
        </section>

        {{-- ERRORES --}}
        <section id="errors" class="mb-20 mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Errores</h2>
            <div class="mt-5 space-y-4">
                <details class="group rounded-xl border border-slate-200 p-5" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">401</code> No autenticado</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Falta token o la cuenta no tiene agencia vinculada.</p>
                    <div class="mt-4"><x-docs.code id="saldo-401">{
  "success": false,
  "message": "Agencia no encontrada."
}</x-docs.code></div>
                </details>
            </div>
        </section>

    </article>
@endsection
