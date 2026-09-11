@extends('docs.layout')

@section('title', 'Listar empresas')

@section('toc')
    <a href="#overview"        class="block text-slate-500 hover:text-slate-950">Descripci&oacute;n</a>
    <a href="#endpoint"        class="block text-slate-500 hover:text-slate-950">Endpoint</a>
    <a href="#auth"            class="block text-slate-500 hover:text-slate-950">Autenticaci&oacute;n</a>
    <a href="#headers"         class="block text-slate-500 hover:text-slate-950">Headers</a>
    <a href="#success"         class="block text-slate-500 hover:text-slate-950">Respuesta 200</a>
    <a href="#fields"          class="block text-slate-500 hover:text-slate-950">Campos de respuesta</a>
    <a href="#errors"          class="block text-slate-500 hover:text-slate-950">Errores</a>
@endsection

@section('content')
    <article class="mx-auto max-w-4xl">

        {{-- OVERVIEW --}}
        <section id="overview" class="scroll-mt-24">
            <div class="flex flex-wrap items-center gap-2 text-sm font-semibold">
                <span class="text-cyan-700">Empresas</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-500">Listar empresas</span>
            </div>
            <h1 class="mt-5 text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl">Listar empresas vinculadas</h1>
            <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600">Obtiene la lista completa de empresas (emisores) que est&aacute;n registradas y vinculadas a la agencia autenticada. No requiere p&aacute;ginaci&oacute;n.</p>
        </section>

        {{-- ENDPOINT --}}
        <section id="endpoint" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Endpoint</h2>
            <div class="mt-5 flex min-w-0 items-center overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <span class="self-stretch bg-blue-500 px-4 py-4 font-mono text-sm font-bold text-white">GET</span>
                <code class="min-w-0 overflow-x-auto px-4 py-4 text-sm font-semibold text-slate-800">/api/v1/empresas</code>
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

        {{-- RESPUESTA 200 --}}
        <section id="success" class="mt-14 scroll-mt-24">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-slate-950">Respuesta exitosa</h2>
                <span class="rounded-full bg-emerald-100 px-2.5 py-1 font-mono text-xs font-bold text-emerald-800">200 OK</span>
            </div>
            <p class="mt-3 leading-7 text-slate-600">Devuelve un arreglo <code>data</code> con la informaci&oacute;n de las empresas registradas.</p>
            <div class="mt-5">
                <x-docs.code id="empresas-200">{
  "success": true,
  "data": [
    {
      "id": 1,
      "ruc": "20100070970",
      "business_name": "EMPRESA DEMO S.A.C.",
      "environment": "demo",
      "engine_type": "pse",
      "pse_username": "DEMO20100070970",
      "sol_user": null,
      "created_at": "2026-09-11T20:00:00.000000Z"
    }
  ]
}</x-docs.code>
            </div>
        </section>

        {{-- CAMPOS --}}
        <section id="fields" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Campos de respuesta</h2>
            <div class="mt-5 overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-4 py-3">Campo</th><th class="px-4 py-3">Tipo</th><th class="hidden px-4 py-3 md:table-cell">Descripci&oacute;n</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">ruc</code></td>
                            <td class="px-4 py-4">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">RUC de 11 d&iacute;gitos de la empresa.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">business_name</code></td>
                            <td class="px-4 py-4">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Raz&oacute;n social de la empresa.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">environment</code></td>
                            <td class="px-4 py-4">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Entorno actual (<code>demo</code> o <code>production</code>).</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">engine_type</code></td>
                            <td class="px-4 py-4">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Motor de env&iacute;o asignado (<code>pse</code> o <code>native</code>).</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- ERRORES --}}
        <section id="errors" class="mb-20 mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Errores</h2>
            <div class="mt-5 space-y-4">
                <details class="group rounded-xl border border-slate-200 p-5" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">401</code> No autenticado / sin agencia</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">El token es inv&aacute;lido, ha expirado, o el usuario no tiene agencia vinculada.</p>
                    <div class="mt-4"><x-docs.code id="empresas-401">{
  "success": false,
  "message": "Agencia no encontrada."
}</x-docs.code></div>
                </details>
            </div>
        </section>

    </article>
@endsection
