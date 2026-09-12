@extends('docs.layout')

@section('title', 'Dar de baja (Anular)')

@section('toc')
    <a href="#overview"        class="block text-slate-500 hover:text-slate-950">Descripci&oacute;n</a>
    <a href="#endpoint"        class="block text-slate-500 hover:text-slate-950">Endpoint</a>
    <a href="#auth"            class="block text-slate-500 hover:text-slate-950">Autenticaci&oacute;n</a>
    <a href="#body"            class="block text-slate-500 hover:text-slate-950">Body (JSON)</a>
    <a href="#request-example" class="block text-slate-500 hover:text-slate-950">Ejemplo</a>
    <a href="#success"         class="block text-slate-500 hover:text-slate-950">Respuesta 202</a>
    <a href="#errors"          class="block text-slate-500 hover:text-slate-950">Errores</a>
@endsection

@section('content')
    <article class="mx-auto max-w-4xl">

        {{-- OVERVIEW --}}
        <section id="overview" class="scroll-mt-24">
            <div class="flex flex-wrap items-center gap-2 text-sm font-semibold">
                <span class="text-cyan-700">Comprobantes</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-500">Baja</span>
            </div>
            <h1 class="mt-5 text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl">Dar de baja un comprobante</h1>
            <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600">Este endpoint genera autom&aacute;ticamente el documento UBL <strong>Comunicaci&oacute;n de Baja (RA)</strong> y lo env&iacute;a a SUNAT o PSE de forma as&iacute;ncrona. Solo necesitas enviarnos cu&aacute;l es el comprobante afectado y el motivo.</p>
        </section>

        {{-- ENDPOINT --}}
        <section id="endpoint" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Endpoint</h2>
            <div class="mt-5 flex min-w-0 items-center overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <span class="self-stretch bg-emerald-500 px-4 py-4 font-mono text-sm font-bold text-white">POST</span>
                <code class="min-w-0 overflow-x-auto px-4 py-4 text-sm font-semibold text-slate-800">/api/v1/documents/void</code>
                <span class="ml-auto mr-4 hidden shrink-0 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 sm:block">Requiere Bearer token</span>
            </div>
        </section>

        {{-- AUTH --}}
        <section id="auth" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Autenticaci&oacute;n</h2>
            <p class="mt-3 leading-7 text-slate-600">Requiere un token Bearer v&aacute;lido obtenido con <a href="{{ route('docs.authentication') }}" class="font-semibold text-cyan-700 underline underline-offset-2 hover:text-cyan-900">POST /api/v1/auth/token</a>.</p>
        </section>

        {{-- BODY --}}
        <section id="body" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Cuerpo de la petici&oacute;n</h2>
            <div class="mt-5 overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-4 py-3">Campo</th><th class="px-4 py-3">Tipo</th><th class="hidden px-4 py-3 sm:table-cell">Reglas</th><th class="px-4 py-3">Descripci&oacute;n</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">company.ruc</code></td>
                            <td class="px-4 py-4 font-mono text-xs text-slate-500">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 sm:table-cell">Requerido, 11 d&iacute;gitos</td>
                            <td class="px-4 py-4 text-slate-600">RUC de la empresa emisora que realizar&aacute; la baja.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">document.document_type_id</code></td>
                            <td class="px-4 py-4 font-mono text-xs text-slate-500">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 sm:table-cell">Requerido, exacto</td>
                            <td class="px-4 py-4 text-slate-600">Tipo del comprobante a anular (ej. <code>01</code> para Factura).</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">document.series</code></td>
                            <td class="px-4 py-4 font-mono text-xs text-slate-500">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 sm:table-cell">Requerido</td>
                            <td class="px-4 py-4 text-slate-600">Serie del comprobante afectado (ej. <code>F001</code>).</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">document.number</code></td>
                            <td class="px-4 py-4 font-mono text-xs text-slate-500">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 sm:table-cell">Requerido</td>
                            <td class="px-4 py-4 text-slate-600">N&uacute;mero correlativo del comprobante afectado.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">document.reason</code></td>
                            <td class="px-4 py-4 font-mono text-xs text-slate-500">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 sm:table-cell">Requerido, max:100</td>
                            <td class="px-4 py-4 text-slate-600">Motivo formal por el cual se est&aacute; dando de baja el documento.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- EJEMPLO --}}
        <section id="request-example" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Ejemplo de solicitud</h2>
            <div class="mt-5">
                <x-docs.code id="void-request" label="cURL">curl --request POST 'https://signia.kore.pe/api/v1/documents/void' \
  --header 'Content-Type: application/json' \
  --header 'Accept: application/json' \
  --header 'Authorization: Bearer 1|nY2k7...access_token' \
  --data '{
    "company": {
        "ruc": "20100070970"
    },
    "document": {
        "document_type_id": "01",
        "series": "F001",
        "number": "123",
        "reason": "Error en la digitaciÃ³n del cliente"
    }
  }'</x-docs.code>
            </div>
        </section>

        {{-- RESPUESTA --}}
        <section id="success" class="mt-14 scroll-mt-24">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-slate-950">Respuesta exitosa</h2>
                <span class="rounded-full bg-emerald-100 px-2.5 py-1 font-mono text-xs font-bold text-emerald-800">202 Accepted</span>
            </div>
            <p class="mt-3 leading-7 text-slate-600">Dado que las bajas o res&uacute;menes toman tiempo en SUNAT, Signia la encola autom&aacute;ticamente y devuelve un ticket para su posterior consulta.</p>
            <div class="mt-5">
                <x-docs.code id="void-202">{
  "success": true,
  "message": "ComunicaciÃ³n de Baja generada y encolada.",
  "data": {
    "ticket": "SIG-X9K2PL10M"
  }
}</x-docs.code>
            </div>
        </section>

        {{-- ERRORES --}}
        <section id="errors" class="mb-20 mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Errores</h2>
            <div class="mt-5 space-y-4">
                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">422</code> Error de validaci&oacute;n</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Alguno de los campos requeridos est&aacute; vac&iacute;o o el motivo excede los 100 caracteres permitidos por SUNAT.</p>
                </details>
                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">404</code> Empresa no encontrada</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">El RUC proporcionado no est&aacute; vinculado a tu cuenta de agencia.</p>
                </details>
            </div>
        </section>

    </article>
@endsection

