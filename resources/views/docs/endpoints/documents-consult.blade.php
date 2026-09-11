@extends('docs.layout')

@section('title', 'Consultar estado')

@section('toc')
    <a href="#overview"        class="block text-slate-500 hover:text-slate-950">Descripci&oacute;n</a>
    <a href="#endpoint"        class="block text-slate-500 hover:text-slate-950">Endpoint</a>
    <a href="#auth"            class="block text-slate-500 hover:text-slate-950">Autenticaci&oacute;n</a>
    <a href="#headers"         class="block text-slate-500 hover:text-slate-950">Headers</a>
    <a href="#body"            class="block text-slate-500 hover:text-slate-950">Body</a>
    <a href="#formats"         class="block text-slate-500 hover:text-slate-950">Formatos</a>
    <a href="#request-example" class="block text-slate-500 hover:text-slate-950">Ejemplo</a>
    <a href="#responses"       class="block text-slate-500 hover:text-slate-950">Respuestas</a>
    <a href="#polling"         class="block text-slate-500 hover:text-slate-950">Polling recomendado</a>
    <a href="#errors"          class="block text-slate-500 hover:text-slate-950">Errores</a>
    <a href="#timeouts"        class="block text-slate-500 hover:text-slate-950">Conexi&oacute;n y timeouts</a>
@endsection

@section('content')
    <article class="mx-auto max-w-4xl">

        {{-- OVERVIEW --}}
        <section id="overview" class="scroll-mt-24">
            <div class="flex flex-wrap items-center gap-2 text-sm font-semibold">
                <span class="text-cyan-700">Comprobantes</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-500">Consultar estado</span>
            </div>
            <h1 class="mt-5 text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl">Consultar estado de un comprobante</h1>
            <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600">Permite conocer el estado actual de un comprobante a partir de su RUC y ticket. Es el complemento obligatorio del flujo as&iacute;ncrono: cuando <code>/documents/send</code> devuelve un 202, usa este endpoint en polling hasta obtener una respuesta final de SUNAT.</p>

            <div class="mt-8 grid gap-3 sm:grid-cols-2 scroll-mt-24">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Base URL</p>
                    <code class="mt-2 block break-all text-sm font-semibold text-slate-800">https://signia.kore.pe/api/v1</code>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Cu&aacute;ndo usarlo</p>
                    <p class="mt-2 text-sm font-semibold text-slate-800">Tras recibir un 202 de /send</p>
                    <p class="mt-1 text-xs leading-5 text-slate-500">Tambi&eacute;n &uacute;til para re-verificar el estado de cualquier comprobante existente.</p>
                </div>
            </div>
        </section>

        {{-- ENDPOINT --}}
        <section id="endpoint" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Endpoint</h2>
            <div class="mt-5 flex min-w-0 items-center overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <span class="self-stretch bg-emerald-500 px-4 py-4 font-mono text-sm font-bold text-white">POST</span>
                <code class="min-w-0 overflow-x-auto px-4 py-4 text-sm font-semibold text-slate-800">/api/v1/documents/consult</code>
                <span class="ml-auto mr-4 hidden shrink-0 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 sm:block">Requiere Bearer token</span>
            </div>
        </section>

        {{-- AUTH --}}
        <section id="auth" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Autenticaci&oacute;n</h2>
            <p class="mt-3 leading-7 text-slate-600">Requiere un token Bearer v&aacute;lido obtenido con <a href="{{ route('docs.authentication') }}" class="font-semibold text-cyan-700 underline underline-offset-2 hover:text-cyan-900">POST /api/v1/auth/token</a>. El token expira en 10 minutos; si el polling supera ese tiempo, renueva el token antes de continuar.</p>
            <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
                <strong>Importante:</strong> en flujos de polling de larga duraci&oacute;n (resumen diario, baja), el token puede expirar antes de obtener la respuesta final. Impl&eacute;menta renovaci&oacute;n autom&aacute;tica al recibir un 401.
            </div>
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
            <p class="mt-3 leading-7 text-slate-600">Env&iacute;a un objeto JSON con el RUC del emisor y el ticket devuelto por <code>/documents/send</code>.</p>
            <div class="mt-5 overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-4 py-3">Campo</th><th class="px-4 py-3">Tipo</th><th class="hidden px-4 py-3 md:table-cell">Descripci&oacute;n</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">ruc</code><span class="ml-1 text-rose-600">*</span><p class="mt-1 text-xs text-slate-500 md:hidden">RUC de 11 d&iacute;gitos del emisor.</p></td>
                            <td class="px-4 py-4">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. RUC de 11 d&iacute;gitos del emisor del comprobante.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">ticket</code><span class="ml-1 text-rose-600">*</span><p class="mt-1 text-xs text-slate-500 md:hidden">Ticket devuelto por /send.</p></td>
                            <td class="px-4 py-4">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. Ticket devuelto en el campo <code>data.ticket</code> de <code>/documents/send</code>. Puede ser un ticket interno Signia (<code>SIG-XXXX</code>) o un ticket real de SUNAT.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- FORMATOS --}}
        <section id="formats" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Formatos y validaciones</h2>
            <ul class="mt-5 space-y-3 rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm leading-6 text-slate-700">
                <li><strong class="text-slate-950">RUC:</strong> exactamente 11 d&iacute;gitos num&eacute;ricos. Debe pertenecer a la agencia autenticada.</li>
                <li><strong class="text-slate-950">Ticket SIG- vs ticket SUNAT:</strong> los tickets que empiezan con <code>SIG-</code> son internos de Signia y significan que el documento sigue en la cola local. Los tickets sin ese prefijo son tickets reales devueltos por SUNAT (para RC/RA) y se consultan directamente contra el motor.</li>
                <li><strong class="text-slate-950">Estados finales:</strong> si el documento ya tiene estado <code>accepted</code>, <code>accepted_with_observations</code> o <code>rejected</code>, el servidor devuelve directamente los datos almacenados sin llamar al motor externo.</li>
                <li><strong class="text-slate-950">Throttle:</strong> el l&iacute;mite de rate del grupo <code>api</code> aplica. En polling, respeta intervalos m&iacute;nimos para no superar el l&iacute;mite.</li>
            </ul>
        </section>

        {{-- EJEMPLO --}}
        <section id="request-example" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Ejemplo de solicitud</h2>
            <div class="mt-5">
                <x-docs.code id="consult-request" label="cURL">curl --request POST 'https://signia.kore.pe/api/v1/documents/consult' \
  --header 'Accept: application/json' \
  --header 'Content-Type: application/json' \
  --header 'Authorization: Bearer 1|nY2k7...access_token' \
  --data-raw '{
    "ruc": "20100070970",
    "ticket": "SIG-ABCDEFGH1234"
  }'</x-docs.code>
            </div>
        </section>

        {{-- RESPUESTAS --}}
        <section id="responses" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Respuestas</h2>
            <p class="mt-3 leading-7 text-slate-600">Este endpoint siempre devuelve <strong>200 OK</strong> cuando la consulta se procesa correctamente, independientemente del estado del comprobante. El campo <code>status</code> dentro del cuerpo indica el estado real.</p>

            <div class="mt-6 space-y-4">

                {{-- Estado: in_process (cola local) --}}
                <details class="group rounded-xl border border-slate-200 p-5" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950">
                        <span class="flex items-center gap-3">
                            <span class="h-2.5 w-2.5 rounded-full bg-violet-500"></span>
                            <span><span class="rounded-full bg-emerald-100 px-2 py-0.5 font-mono text-xs font-bold text-emerald-800 mr-2">200</span><code class="text-violet-700">in_process</code> &mdash; En cola local (ticket SIG-)</span>
                        </span>
                        <span class="text-slate-400 group-open:rotate-45">+</span>
                    </summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">El worker de Signia todav&iacute;a no ha tomado el documento de la cola. S&eacute;guelo consultando.</p>
                    <div class="mt-4"><x-docs.code id="consult-in-process-local">{
  "success": true,
  "status": "in_process",
  "message": "El documento sigue en cola de procesamiento local."
}</x-docs.code></div>
                </details>

                {{-- Estado: in_process (ticket SUNAT) --}}
                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950">
                        <span class="flex items-center gap-3">
                            <span class="h-2.5 w-2.5 rounded-full bg-violet-500"></span>
                            <span><span class="rounded-full bg-emerald-100 px-2 py-0.5 font-mono text-xs font-bold text-emerald-800 mr-2">200</span><code class="text-violet-700">in_process</code> &mdash; Pendiente en SUNAT (ticket RC/RA)</span>
                        </span>
                        <span class="text-slate-400 group-open:rotate-45">+</span>
                    </summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">El ticket fue enviado a SUNAT (resumen diario o baja), pero SUNAT a&uacute;n no ha procesado la respuesta.</p>
                    <div class="mt-4"><x-docs.code id="consult-in-process-sunat">{
  "success": true,
  "status": "in_process",
  "message": "Consulta realizada.",
  "cdr_base64": null
}</x-docs.code></div>
                </details>

                {{-- Estado: accepted --}}
                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950">
                        <span class="flex items-center gap-3">
                            <span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span>
                            <span><span class="rounded-full bg-emerald-100 px-2 py-0.5 font-mono text-xs font-bold text-emerald-800 mr-2">200</span><code class="text-emerald-700">accepted</code> &mdash; Aceptado por SUNAT</span>
                        </span>
                        <span class="text-slate-400 group-open:rotate-45">+</span>
                    </summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Estado final. SUNAT acept&oacute; el comprobante. Los archivos est&aacute;n disponibles. Ya no es necesario seguir consultando.</p>
                    <div class="mt-4"><x-docs.code id="consult-accepted">{
  "success": true,
  "message": "Consulta procesada. El documento ya cuenta con resolucion final.",
  "status": "accepted",
  "cdr_url": "https://signia.kore.pe/storage/documents/20100070970/2026/09/R-20100070970-01-F001-00000001.zip",
  "xml_url": "https://signia.kore.pe/storage/documents/20100070970/2026/09/20100070970-01-F001-00000001.xml",
  "pdf_url": "https://signia.kore.pe/storage/documents/20100070970/2026/09/20100070970-01-F001-00000001.pdf"
}</x-docs.code></div>
                </details>

                {{-- Estado: accepted_with_observations --}}
                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950">
                        <span class="flex items-center gap-3">
                            <span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span>
                            <span><span class="rounded-full bg-emerald-100 px-2 py-0.5 font-mono text-xs font-bold text-emerald-800 mr-2">200</span><code class="text-amber-700">accepted_with_observations</code> &mdash; Aceptado con observaciones</span>
                        </span>
                        <span class="text-slate-400 group-open:rotate-45">+</span>
                    </summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Estado final. SUNAT acept&oacute; el comprobante con observaciones no bloqueantes. El comprobante tiene validez tributaria. Revisa el CDR para los c&oacute;digos de observaci&oacute;n.</p>
                    <div class="mt-4"><x-docs.code id="consult-obs">{
  "success": true,
  "message": "Consulta procesada. El documento ya cuenta con resolucion final.",
  "status": "accepted_with_observations",
  "cdr_url": "https://signia.kore.pe/storage/documents/20100070970/2026/09/R-20100070970-01-F001-00000001.zip",
  "xml_url": "https://signia.kore.pe/storage/documents/20100070970/2026/09/20100070970-01-F001-00000001.xml",
  "pdf_url": "https://signia.kore.pe/storage/documents/20100070970/2026/09/20100070970-01-F001-00000001.pdf"
}</x-docs.code></div>
                </details>

                {{-- Estado: rejected --}}
                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950">
                        <span class="flex items-center gap-3">
                            <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                            <span><span class="rounded-full bg-emerald-100 px-2 py-0.5 font-mono text-xs font-bold text-emerald-800 mr-2">200</span><code class="text-rose-700">rejected</code> &mdash; Rechazado por SUNAT</span>
                        </span>
                        <span class="text-slate-400 group-open:rotate-45">+</span>
                    </summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Estado final. SUNAT rechaz&oacute; el comprobante. El CDR contiene el c&oacute;digo y descripci&oacute;n del rechazo. Puedes reintentar con <code>POST /api/v1/documents/reintentar</code> tras corregir el error.</p>
                    <div class="mt-4"><x-docs.code id="consult-rejected">{
  "success": true,
  "message": "Consulta procesada. El documento ya cuenta con resolucion final.",
  "status": "rejected",
  "cdr_url": "https://signia.kore.pe/storage/documents/20100070970/2026/09/R-20100070970-01-F001-00000001.zip",
  "xml_url": null,
  "pdf_url": null
}</x-docs.code></div>
                </details>

                {{-- Estado: no permite consulta --}}
                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950">
                        <span class="flex items-center gap-3">
                            <span class="h-2.5 w-2.5 rounded-full bg-slate-400"></span>
                            <span><span class="rounded-full bg-emerald-100 px-2 py-0.5 font-mono text-xs font-bold text-emerald-800 mr-2">200</span>Estado no consultable externamente</span>
                        </span>
                        <span class="text-slate-400 group-open:rotate-45">+</span>
                    </summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">El documento existe pero su estado actual (<code>exception</code> u otro) no permite consulta externa al motor. Consulta el panel de Signia o usa <code>/reintentar</code> para resolver el error.</p>
                    <div class="mt-4"><x-docs.code id="consult-no-external">{
  "success": false,
  "status": "exception",
  "message": "El estado actual no permite consulta externa."
}</x-docs.code></div>
                </details>

            </div>
        </section>

        {{-- POLLING --}}
        <section id="polling" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Polling recomendado</h2>
            <p class="mt-3 leading-7 text-slate-600">El flujo de polling es necesario cuando <code>/documents/send</code> devuelve un 202. La estrategia correcta depende del tipo de comprobante:</p>

            <div class="mt-6 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Facturas, boletas y notas (01 / 03 / 07 / 08)</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Si recibiste un 202 por intermitencia, el worker normalmente resuelve en segundos. Recomendamos:</p>
                    <ul class="mt-2 ml-4 list-disc space-y-1 text-sm text-slate-600">
                        <li>Primera consulta: 5 segundos despu&eacute;s del 202.</li>
                        <li>Consultas siguientes: cada 10 segundos.</li>
                        <li>M&aacute;ximo: 15 intentos (~2,5 minutos).</li>
                    </ul>
                </div>
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Resumen diario y baja (RC / RA)</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">SUNAT puede tardar varios minutos en procesar. Recomendamos:</p>
                    <ul class="mt-2 ml-4 list-disc space-y-1 text-sm text-slate-600">
                        <li>Primera consulta: 30 segundos despu&eacute;s del 202.</li>
                        <li>Consultas siguientes: cada 30&ndash;60 segundos.</li>
                        <li>M&aacute;ximo: 20 intentos (~10&ndash;20 minutos).</li>
                    </ul>
                </div>
            </div>

            <div class="mt-5 rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm leading-6 text-slate-700">
                <p class="font-semibold text-slate-950 mb-2">L&oacute;gica de decisi&oacute;n</p>
                <ul class="space-y-2">
                    <li><span class="inline-block w-5 text-violet-600 font-bold">&#8226;</span> <code>in_process</code> &rarr; vuelve a consultar despu&eacute;s del intervalo.</li>
                    <li><span class="inline-block w-5 text-emerald-600 font-bold">&#8226;</span> <code>accepted</code> o <code>accepted_with_observations</code> &rarr; estado final, detiene el polling.</li>
                    <li><span class="inline-block w-5 text-rose-600 font-bold">&#8226;</span> <code>rejected</code> &rarr; estado final, notifica el rechazo y usa <code>/reintentar</code> si aplica.</li>
                    <li><span class="inline-block w-5 text-slate-500 font-bold">&#8226;</span> <code>success: false</code> &rarr; estado no consultable; revisa el panel o contacta soporte.</li>
                    <li><span class="inline-block w-5 text-amber-600 font-bold">&#8226;</span> 401 durante el polling &rarr; renueva el token y rein&iacute;cia la consulta.</li>
                    <li><span class="inline-block w-5 text-slate-400 font-bold">&#8226;</span> M&aacute;ximo de intentos agotado &rarr; notifica al operador; el sistema sigue procesando en background.</li>
                </ul>
            </div>

            <div class="mt-5">
                <x-docs.code id="consult-pseudocode" label="Pseudoc&oacute;digo">ticket = send_document(payload)  # POST /documents/send

if response.status == 200:
    # Procesado sincronamente
    handle_success(response)
elif response.status == 202:
    # Flujo asincrono
    for attempt in range(1, max_attempts + 1):
        sleep(interval_seconds)
        result = consult(ruc, ticket)  # POST /documents/consult

        if result.status in ("accepted", "accepted_with_observations"):
            handle_success(result)
            break
        elif result.status == "rejected":
            handle_rejection(result)
            break
        elif not result.success:
            handle_non_consultable(result)
            break
        # else: in_process -> continuar polling

    else:
        notify_operator("Timeout de polling agotado", ticket)</x-docs.code>
            </div>
        </section>

        {{-- ERRORES --}}
        <section id="errors" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Errores</h2>
            <div class="mt-5 space-y-4">

                <details class="group rounded-xl border border-slate-200 p-5" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">401</code> No autenticado / sin agencia</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">El token es inv&aacute;lido, ha expirado, o el usuario no tiene agencia vinculada. Renueva el token y reintenta.</p>
                    <div class="mt-4"><x-docs.code id="consult-401">{
  "success": false,
  "message": "Agencia no encontrada."
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-amber-600">404</code> Ticket no encontrado</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">No se encontr&oacute; ning&uacute;n documento que coincida con el RUC y el ticket bajo esta agencia. Verifica que el RUC y el ticket sean correctos.</p>
                    <div class="mt-4"><x-docs.code id="consult-404">{
  "success": false,
  "message": "Ticket no encontrado."
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-amber-600">422</code> Validaci&oacute;n fallida</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Falta el campo <code>ruc</code> o <code>ticket</code>, o el RUC no tiene exactamente 11 caracteres.</p>
                    <div class="mt-4"><x-docs.code id="consult-422">{
  "message": "El campo ruc es obligatorio.",
  "errors": {
    "ruc": ["El campo ruc es obligatorio."]
  }
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">500</code> Error al consultar motor</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">El motor de facturaci&oacute;n devolver&oacute; una excepci&oacute;n al intentar consultar el ticket real en SUNAT (solo aplica a tickets RC/RA no resueltos). Reintenta en unos segundos.</p>
                    <div class="mt-4"><x-docs.code id="consult-500">{
  "success": false,
  "message": "Error al consultar motor: [detalle del error]"
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-violet-600">429</code> Demasiadas solicitudes</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Se super&oacute; el l&iacute;mite de rate del grupo <code>api</code>. Espera el valor de <code>Retry-After</code> antes de la siguiente consulta de polling.</p>
                    <div class="mt-4"><x-docs.code id="consult-429">{
  "message": "Too Many Attempts."
}</x-docs.code></div>
                </details>

            </div>
        </section>

        {{-- TIMEOUTS --}}
        <section id="timeouts" class="mb-20 mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Errores de conexi&oacute;n y timeouts</h2>
            <p class="mt-3 leading-7 text-slate-600">Cuando el ticket es real de SUNAT (RC/RA), el servidor realiza una llamada externa al motor de consulta. El tiempo de respuesta puede variar seg&uacute;n la disponibilidad de SUNAT.</p>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Timeout recomendado</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Configura <strong>15 segundos</strong> de timeout en el cliente para consultas. Si no recibes respuesta, reintenta despu&eacute;s del intervalo de polling.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Idempotencia</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Este endpoint es de <strong>solo lectura</strong>: consultar el mismo ticket m&uacute;ltiples veces nunca duplica cobros ni modifica el estado del documento (solo lo actualiza si SUNAT ya resolvi&oacute;).</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Estados que no aplican aqu&iacute;</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Este endpoint <strong>no emite ni reintenta</strong> comprobantes. Si necesitas reenviar un comprobante rechazado o en excepci&oacute;n, usa <code>POST /api/v1/documents/reintentar</code>.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Conexi&oacute;n fallida en polling</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Un timeout del cliente durante el polling no afecta el procesamiento en el servidor. Reintenta la consulta despu&eacute;s del intervalo sin modificar el ticket ni crear uno nuevo.</p>
                </div>
            </div>
        </section>

    </article>
@endsection
