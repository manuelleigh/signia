@extends('docs.layout')

@section('title', 'Reintentar comprobante')

@section('toc')
    <a href="#overview"        class="block text-slate-500 hover:text-slate-950">Descripci&oacute;n</a>
    <a href="#endpoint"        class="block text-slate-500 hover:text-slate-950">Endpoint</a>
    <a href="#auth"            class="block text-slate-500 hover:text-slate-950">Autenticaci&oacute;n</a>
    <a href="#headers"         class="block text-slate-500 hover:text-slate-950">Headers</a>
    <a href="#body"            class="block text-slate-500 hover:text-slate-950">Body</a>
    <a href="#when-to-use"     class="block text-slate-500 hover:text-slate-950">Cu&aacute;ndo usarlo</a>
    <a href="#formats"         class="block text-slate-500 hover:text-slate-950">Formatos</a>
    <a href="#request-example" class="block text-slate-500 hover:text-slate-950">Ejemplo</a>
    <a href="#success"         class="block text-slate-500 hover:text-slate-950">Respuesta 202</a>
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
                <span class="text-slate-500">Reintentar comprobante</span>
            </div>
            <h1 class="mt-5 text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl">Reintentar un comprobante fallido</h1>
            <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600">Permite reenviar a SUNAT un comprobante que termin&oacute; en estado <code>rejected</code> o <code>exception</code> tras corregir la causa del fallo. El reintento siempre es <strong>as&iacute;ncrono</strong>: encola el documento y devuelve un ticket para consultar con <code>/documents/consult</code>.</p>

            <div class="mt-8 grid gap-3 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Base URL</p>
                    <code class="mt-2 block break-all text-sm font-semibold text-slate-800">https://signia.kore.pe/api/v1</code>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Flujo</p>
                    <p class="mt-2 text-sm font-semibold text-slate-800">Siempre as&iacute;ncrono (202)</p>
                    <p class="mt-1 text-xs leading-5 text-slate-500">El reintento se encola inmediatamente. Usa <code>/consult</code> para verificar el resultado.</p>
                </div>
            </div>

            {{-- Flujo visual --}}
            <div class="mt-8 rounded-xl border border-slate-200 bg-slate-50 p-5">
                <p class="text-xs font-bold uppercase tracking-wider text-slate-400 mb-4">Posici&oacute;n en el ciclo de vida</p>
                <div class="flex flex-wrap items-center gap-2 text-sm">
                    <span class="rounded-lg border border-slate-200 bg-white px-3 py-2 font-mono text-xs text-slate-500">/send</span>
                    <span class="text-slate-300">&rarr;</span>
                    <span class="rounded-lg border border-slate-200 bg-white px-3 py-2 font-mono text-xs text-slate-500">/consult</span>
                    <span class="text-slate-300">&rarr;</span>
                    <div class="flex items-center gap-1.5">
                        <span class="h-2 w-2 rounded-full bg-rose-500"></span>
                        <span class="rounded-lg border border-rose-200 bg-rose-50 px-3 py-2 font-mono text-xs font-semibold text-rose-700">rejected / exception</span>
                    </div>
                    <span class="text-slate-300">&rarr;</span>
                    <span class="rounded-lg border border-cyan-200 bg-cyan-50 px-3 py-2 font-mono text-xs font-bold text-cyan-700">/reintentar</span>
                    <span class="text-slate-300">&rarr;</span>
                    <span class="rounded-lg border border-slate-200 bg-white px-3 py-2 font-mono text-xs text-slate-500">/consult</span>
                </div>
            </div>
        </section>

        {{-- ENDPOINT --}}
        <section id="endpoint" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Endpoint</h2>
            <div class="mt-5 flex min-w-0 items-center overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <span class="self-stretch bg-emerald-500 px-4 py-4 font-mono text-sm font-bold text-white">POST</span>
                <code class="min-w-0 overflow-x-auto px-4 py-4 text-sm font-semibold text-slate-800">/api/v1/documents/reintentar</code>
                <span class="ml-auto mr-4 hidden shrink-0 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 sm:block">Requiere Bearer token</span>
            </div>
        </section>

        {{-- AUTH --}}
        <section id="auth" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Autenticaci&oacute;n</h2>
            <p class="mt-3 leading-7 text-slate-600">Requiere un token Bearer v&aacute;lido obtenido con <a href="{{ route('docs.authentication') }}" class="font-semibold text-cyan-700 underline underline-offset-2 hover:text-cyan-900">POST /api/v1/auth/token</a>. El token expira en 10 minutos.</p>
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
            <p class="mt-3 leading-7 text-slate-600">Env&iacute;a el identificador del comprobante a reintentar y el payload corregido con el que se volver&aacute; a procesar. El payload debe incluir todos los datos del comprobante, no solo los cambios.</p>
            <div class="mt-5 overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-4 py-3">Campo</th><th class="px-4 py-3">Tipo</th><th class="hidden px-4 py-3 md:table-cell">Descripci&oacute;n</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">ruc</code><span class="ml-1 text-rose-600">*</span><p class="mt-1 text-xs text-slate-500 md:hidden">RUC de 11 d&iacute;gitos del emisor.</p></td>
                            <td class="px-4 py-4">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. RUC de 11 d&iacute;gitos del emisor del comprobante fallido.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">serie</code><span class="ml-1 text-rose-600">*</span><p class="mt-1 text-xs text-slate-500 md:hidden">Serie del comprobante (ej. F001).</p></td>
                            <td class="px-4 py-4">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. Serie del comprobante tal como fue enviado originalmente.</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">number</code><span class="ml-1 text-rose-600">*</span><p class="mt-1 text-xs text-slate-500 md:hidden">N&uacute;mero correlativo del comprobante.</p></td>
                            <td class="px-4 py-4">string</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. N&uacute;mero correlativo del comprobante (ej. <code>00000001</code>).</td>
                        </tr>
                        <tr>
                            <td class="px-4 py-4"><code class="font-semibold text-slate-950">payload</code><span class="ml-1 text-rose-600">*</span><p class="mt-1 text-xs text-slate-500 md:hidden">Payload completo corregido del comprobante.</p></td>
                            <td class="px-4 py-4">object</td>
                            <td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. El payload completo del comprobante (misma estructura que <code>/documents/send</code>) con las correcciones aplicadas.</td>
                        </tr>
                    </tbody>
                </table>
            </div>
            <div class="mt-4 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-6 text-slate-700">
                <strong class="text-slate-950">Nota sobre <code>payload</code>:</strong> debe contener el objeto completo tal como se enviar&iacute;a a <code>/documents/send</code> (con <code>company</code>, <code>document</code>, &iacute;tems, totales, etc.), no solo los campos corregidos. El motor reutiliza este payload completo para regenerar y firmar el XML.
            </div>
        </section>

        {{-- CUANDO USARLO --}}
        <section id="when-to-use" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Cu&aacute;ndo usarlo</h2>
            <p class="mt-3 leading-7 text-slate-600">Este endpoint <strong>solo funciona con documentos en estado <code>rejected</code> o <code>exception</code></strong>. Cualquier otro estado produce un 422.</p>

            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-rose-200 bg-rose-50 p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span>
                        <p class="font-semibold text-rose-900"><code>rejected</code> &mdash; Rechazado por SUNAT</p>
                    </div>
                    <p class="text-sm leading-6 text-rose-800">SUNAT rechaz&oacute; el XML por un error en los datos. Causas comunes:</p>
                    <ul class="mt-2 ml-4 list-disc space-y-1 text-sm text-rose-800">
                        <li>IGV calculado incorrectamente.</li>
                        <li>Tipo de documento del cliente inv&aacute;lido.</li>
                        <li>Serie no registrada en SUNAT.</li>
                        <li>Fecha de emisi&oacute;n fuera del rango permitido.</li>
                    </ul>
                    <p class="mt-3 text-sm text-rose-700"><strong>Acci&oacute;n:</strong> corrige los datos en <code>payload</code> y usa este endpoint.</p>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-5">
                    <div class="flex items-center gap-2 mb-3">
                        <span class="h-2.5 w-2.5 rounded-full bg-slate-400"></span>
                        <p class="font-semibold text-slate-900"><code>exception</code> &mdash; Error del motor</p>
                    </div>
                    <p class="text-sm leading-6 text-slate-600">El motor no pudo generar o firmar el XML. Causas comunes:</p>
                    <ul class="mt-2 ml-4 list-disc space-y-1 text-sm text-slate-600">
                        <li>Certificado digital vencido o inv&aacute;lido.</li>
                        <li>Datos de empresa incompletos en Signia.</li>
                        <li>Error en la estructura del payload.</li>
                    </ul>
                    <p class="mt-3 text-sm text-slate-600"><strong>Acci&oacute;n:</strong> corrige la causa ra&iacute;z en el panel de Signia o en el payload, luego usa este endpoint.</p>
                </div>
            </div>

            <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
                <strong>Estados que no permiten reintento:</strong> <code>in_process</code>, <code>accepted</code> y <code>accepted_with_observations</code>. Si el documento est&aacute; en <code>in_process</code>, espera a que finalice antes de reintentar. Si ya est&aacute; aceptado, no se puede ni debe reenviar.
            </div>
        </section>

        {{-- FORMATOS --}}
        <section id="formats" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Formatos y validaciones</h2>
            <ul class="mt-5 space-y-3 rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm leading-6 text-slate-700">
                <li><strong class="text-slate-950">RUC:</strong> exactamente 11 d&iacute;gitos num&eacute;ricos. Debe pertenecer a la agencia autenticada.</li>
                <li><strong class="text-slate-950">Estado requerido:</strong> el documento debe estar en <code>rejected</code> o <code>exception</code>. Cualquier otro estado devuelve 422.</li>
                <li><strong class="text-slate-950">Saldo:</strong> en producci&oacute;n se descuenta un cr&eacute;dito adicional por cada reintento, igual que en el env&iacute;o original. Si no hay saldo, devuelve 400.</li>
                <li><strong class="text-slate-950">Payload:</strong> debe ser un objeto JSON v&aacute;lido con la estructura completa del comprobante. No puede ser null ni un array.</li>
                <li><strong class="text-slate-950">Mismo RUC + serie + number:</strong> el reintento reutiliza el registro existente; no crea un documento nuevo. El ticket anterior queda obsoleto.</li>
            </ul>
        </section>

        {{-- EJEMPLO --}}
        <section id="request-example" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Ejemplo de solicitud</h2>
            <p class="mt-3 leading-7 text-slate-600">Reintento de una factura rechazada por SUNAT tras corregir el IGV.</p>
            <div class="mt-5">
                <x-docs.code id="retry-request" label="cURL">curl --request POST 'https://signia.kore.pe/api/v1/documents/reintentar' \
  --header 'Accept: application/json' \
  --header 'Content-Type: application/json' \
  --header 'Authorization: Bearer 1|nY2k7...access_token' \
  --data-raw '{
    "ruc": "20100070970",
    "serie": "F001",
    "number": "00000001",
    "payload": {
      "company": {
        "ruc": "20100070970",
        "razon_social": "EMPRESA DEMO S.A.C.",
        "ubigeo": "150101",
        "direccion": "Av. Las Flores 123",
        "departamento": "Lima",
        "provincia": "Lima",
        "distrito": "Lima"
      },
      "document": {
        "document_type_id": "01",
        "series": "F001",
        "number": "00000001",
        "fecha_emision": "2026-09-11",
        "hora_emision": "10:00:00",
        "moneda": "PEN",
        "cliente": {
          "tipo_documento": "6",
          "numero_documento": "20123456789",
          "razon_social": "CLIENTE EJEMPLO S.A.C."
        },
        "items": [
          {
            "descripcion": "Servicio de integracion",
            "cantidad": 1,
            "valor_unitario": 100.00,
            "precio_unitario": 118.00,
            "igv": 18.00
          }
        ],
        "totales": {
          "total_gravadas": 100.00,
          "total_igv": 18.00,
          "total": 118.00
        }
      }
    }
  }'</x-docs.code>
            </div>
        </section>

        {{-- RESPUESTA 202 --}}
        <section id="success" class="mt-14 scroll-mt-24">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-slate-950">Respuesta exitosa</h2>
                <span class="rounded-full bg-violet-100 px-2.5 py-1 font-mono text-xs font-bold text-violet-800">202 Accepted</span>
            </div>
            <p class="mt-3 leading-7 text-slate-600">El reintento siempre es as&iacute;ncrono. El documento pasa a estado <code>in_process</code> y se encola con un nuevo ticket. El ticket anterior queda obsoleto; &uacute;salo para rastrear esta nueva solicitud.</p>
            <div class="mt-5">
                <x-docs.code id="retry-202">{
  "success": true,
  "message": "Reintento de envio encolado correctamente.",
  "data": {
    "ticket": "SIG-XYZABCDE5678"
  }
}</x-docs.code>
            </div>
            <div class="mt-5 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-6 text-slate-700">
                <strong class="text-slate-950">Pr&oacute;ximo paso:</strong> guarda el nuevo <code>ticket</code> y consulta el estado con <code>POST /api/v1/documents/consult</code> usando el mismo RUC, con intervalos de 10&ndash;30 segundos, hasta obtener <code>accepted</code>, <code>accepted_with_observations</code> o <code>rejected</code>.
            </div>
        </section>

        {{-- ERRORES --}}
        <section id="errors" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Errores</h2>
            <div class="mt-5 space-y-4">

                <details class="group rounded-xl border border-slate-200 p-5" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">400</code> Saldo insuficiente</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">La agencia no tiene cr&eacute;ditos para este reintento (solo en producci&oacute;n). El documento permanece en su estado anterior (<code>rejected</code> o <code>exception</code>).</p>
                    <div class="mt-4"><x-docs.code id="retry-400">{
  "status": "error",
  "message": "Saldo insuficiente para procesar el comprobante."
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">401</code> No autenticado / sin agencia</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">El token es inv&aacute;lido, ha expirado, o el usuario no tiene agencia vinculada. Renueva el token y reintenta.</p>
                    <div class="mt-4"><x-docs.code id="retry-401">{
  "success": false,
  "message": "Agencia no encontrada."
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-amber-600">404</code> Documento no encontrado</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">No existe ning&uacute;n documento con el RUC, serie y n&uacute;mero indicados bajo la agencia autenticada. Verifica los tres identificadores.</p>
                    <div class="mt-4"><x-docs.code id="retry-404">{
  "success": false,
  "message": "Documento no encontrado."
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-amber-600">422</code> Estado no permite reintento</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">El documento existe pero su estado actual no es <code>rejected</code> ni <code>exception</code>. Solo se pueden reintentar comprobantes que hayan fallado definitivamente.</p>
                    <div class="mt-4">
                        <x-docs.code id="retry-422">{
  "success": false,
  "message": "Solo se pueden reintentar comprobantes fallidos o en excepcion."
}</x-docs.code>
                    </div>
                    <div class="mt-4 overflow-x-auto rounded-xl border border-slate-200">
                        <table class="w-full text-left text-sm">
                            <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-4 py-3">Estado actual</th><th class="px-4 py-3">Permite reintento</th><th class="hidden px-4 py-3 sm:table-cell">Acci&oacute;n recomendada</th></tr></thead>
                            <tbody class="divide-y divide-slate-100">
                                <tr><td class="px-4 py-4"><code class="text-rose-700">rejected</code></td><td class="px-4 py-4 text-emerald-700 font-semibold">S&iacute;</td><td class="hidden px-4 py-4 text-slate-600 sm:table-cell">Corrige el payload y usa este endpoint.</td></tr>
                                <tr><td class="px-4 py-4"><code class="text-slate-600">exception</code></td><td class="px-4 py-4 text-emerald-700 font-semibold">S&iacute;</td><td class="hidden px-4 py-4 text-slate-600 sm:table-cell">Corrige la causa ra&iacute;z (certificado, payload) y usa este endpoint.</td></tr>
                                <tr><td class="px-4 py-4"><code class="text-violet-700">in_process</code></td><td class="px-4 py-4 text-rose-600 font-semibold">No</td><td class="hidden px-4 py-4 text-slate-600 sm:table-cell">Espera con <code>/consult</code> hasta obtener un estado final.</td></tr>
                                <tr><td class="px-4 py-4"><code class="text-emerald-700">accepted</code></td><td class="px-4 py-4 text-rose-600 font-semibold">No</td><td class="hidden px-4 py-4 text-slate-600 sm:table-cell">El comprobante ya est&aacute; aceptado. No se debe reenviar.</td></tr>
                                <tr><td class="px-4 py-4"><code class="text-amber-700">accepted_with_observations</code></td><td class="px-4 py-4 text-rose-600 font-semibold">No</td><td class="hidden px-4 py-4 text-slate-600 sm:table-cell">El comprobante es v&aacute;lido. No se debe reenviar.</td></tr>
                            </tbody>
                        </table>
                    </div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-amber-600">422</code> Validaci&oacute;n de campos fallida</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Falta un campo obligatorio (<code>ruc</code>, <code>serie</code>, <code>number</code> o <code>payload</code>), el RUC no tiene 11 d&iacute;gitos, o el payload no es un objeto.</p>
                    <div class="mt-4"><x-docs.code id="retry-422-validation">{
  "message": "El campo payload es obligatorio.",
  "errors": {
    "payload": ["El campo payload es obligatorio."]
  }
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-violet-600">429</code> Demasiadas solicitudes</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Se super&oacute; el l&iacute;mite de rate del grupo <code>api</code>. Espera el valor de <code>Retry-After</code> antes de reintentar.</p>
                    <div class="mt-4"><x-docs.code id="retry-429">{
  "message": "Too Many Attempts."
}</x-docs.code></div>
                </details>

            </div>
        </section>

        {{-- TIMEOUTS --}}
        <section id="timeouts" class="mb-20 mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Errores de conexi&oacute;n y timeouts</h2>
            <p class="mt-3 leading-7 text-slate-600">El reintento encola el trabajo de forma inmediata y devuelve 202 en milisegundos. El procesamiento real ocurre en background, por lo que los timeouts del cliente no afectan el resultado.</p>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Timeout recomendado</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Configura <strong>10 segundos</strong> para este endpoint. La respuesta 202 llega casi instant&aacute;neamente al solo encolar el trabajo.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Idempotencia y doble cobro</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Cada llamada exitosa a <code>/reintentar</code> <strong>descuenta un cr&eacute;dito</strong> adicional en producci&oacute;n. Verifica el estado con <code>/consult</code> antes de llamar de nuevo para evitar cobros dobles.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Conexi&oacute;n fallida sin respuesta</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Si no recibes respuesta, consulta el estado con <code>/consult</code> antes de reintentar para verificar si el documento ya pas&oacute; a <code>in_process</code> y se est&aacute; procesando.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Cuota de reintentos</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">No hay l&iacute;mite de reintentos por comprobante, pero cada uno descuenta saldo. Si el error persiste tras 2&ndash;3 reintentos, contacta al soporte de Signia.</p>
                </div>
            </div>
        </section>

    </article>
@endsection
