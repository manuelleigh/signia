@extends('docs.layout')

@section('title', 'Eliminar empresa')

@section('toc')
    <a href="#overview"        class="block text-slate-500 hover:text-slate-950">Descripci&oacute;n</a>
    <a href="#endpoint"        class="block text-slate-500 hover:text-slate-950">Endpoint</a>
    <a href="#auth"            class="block text-slate-500 hover:text-slate-950">Autenticaci&oacute;n</a>
    <a href="#headers"         class="block text-slate-500 hover:text-slate-950">Headers</a>
    <a href="#parameters"      class="block text-slate-500 hover:text-slate-950">Par&aacute;metros de URL</a>
    <a href="#request-example" class="block text-slate-500 hover:text-slate-950">Ejemplo</a>
    <a href="#success"         class="block text-slate-500 hover:text-slate-950">Respuesta 200</a>
    <a href="#errors"          class="block text-slate-500 hover:text-slate-950">Errores</a>
    <a href="#considerations"  class="block text-slate-500 hover:text-slate-950">Consideraciones</a>
@endsection

@section('content')
    <article class="mx-auto max-w-4xl">

        {{-- OVERVIEW --}}
        <section id="overview" class="scroll-mt-24">
            <div class="flex flex-wrap items-center gap-2 text-sm font-semibold">
                <span class="text-cyan-700">Empresas</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-500">Eliminar empresa</span>
            </div>
            <h1 class="mt-5 text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl">Eliminar empresa</h1>
            <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600">Elimina o suspende temporalmente el acceso de una empresa a Signia API. Tras invocar este endpoint, no se podr&aacute;n emitir ni consultar m&aacute;s comprobantes para el RUC indicado bajo tu agencia.</p>
        </section>

        {{-- ENDPOINT --}}
        <section id="endpoint" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Endpoint</h2>
            <div class="mt-5 flex min-w-0 items-center overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <span class="self-stretch bg-rose-500 px-4 py-4 font-mono text-sm font-bold text-white">DELETE</span>
                <code class="min-w-0 overflow-x-auto px-4 py-4 text-sm font-semibold text-slate-800">/api/v1/empresa/{ruc}</code>
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

        {{-- PARAMETERS --}}
        <section id="parameters" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Par&aacute;metros de URL</h2>
            <div class="mt-5 overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-4 py-3">Par&aacute;metro</th><th class="px-4 py-3">Tipo</th><th class="hidden px-4 py-3 md:table-cell">Descripci&oacute;n</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">ruc</code><span class="ml-1 text-rose-600">*</span></td>
                            <td class="px-4 py-4">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. RUC de 11 d&iacute;gitos de la empresa a eliminar. Debe ser parte del path de la URL.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <p class="mt-4 text-sm text-slate-600">Este endpoint <strong>no requiere Body</strong>.</p>
        </section>

        {{-- EJEMPLO --}}
        <section id="request-example" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Ejemplo de solicitud</h2>
            <div class="mt-5">
                <x-docs.code id="del-request" label="cURL">curl --request DELETE 'https://signia.kore.pe/api/v1/empresa/20100070970' \
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
            <p class="mt-3 leading-7 text-slate-600">Devuelve un mensaje de confirmaci&oacute;n. A partir de este momento, cualquier intento de emitir con este RUC devolver&aacute; un error.</p>
            <div class="mt-5">
                <x-docs.code id="del-200">{
  "success": true,
  "message": "Empresa eliminada/suspendida correctamente."
}</x-docs.code>
            </div>
        </section>

        {{-- ERRORES --}}
        <section id="errors" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Errores</h2>
            <div class="mt-5 space-y-4">
                
                <details class="group rounded-xl border border-slate-200 p-5" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">404</code> RUC no encontrado</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">No se encontr&oacute; la empresa bajo tu cuenta de agencia.</p>
                    <div class="mt-4"><x-docs.code id="del-404">{
  "success": false,
  "message": "RUC no encontrado en tu cuenta."
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">401</code> No autenticado</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Falta token o agencia vinculada.</p>
                    <div class="mt-4"><x-docs.code id="del-401">{
  "message": "Unauthenticated."
}</x-docs.code></div>
                </details>

            </div>
        </section>

        {{-- CONSIDERACIONES --}}
        <section id="considerations" class="mb-20 mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Consideraciones importantes</h2>
            <div class="mt-5 rounded-xl border border-rose-200 bg-rose-50 p-5">
                <div class="flex items-center gap-2 mb-3">
                    <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                    <p class="font-semibold text-rose-900">Documentos en proceso</p>
                </div>
                <p class="text-sm leading-6 text-rose-800">
                    Si eliminas una empresa mientras a&uacute;n tiene comprobantes en estado <code>in_process</code>, la consulta de su estado fallar&aacute; de inmediato (ya que la empresa dejar&aacute; de existir en tu cuenta). 
                    <strong>Es recomendable asegurar que no existan documentos pendientes de procesamiento antes de eliminar la empresa.</strong>
                </p>
            </div>
        </section>

    </article>
@endsection
