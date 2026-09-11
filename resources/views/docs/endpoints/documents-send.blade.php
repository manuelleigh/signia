@extends('docs.layout')

@section('title', 'Emitir comprobante')

@section('toc')
    <a href="#overview"        class="block text-slate-500 hover:text-slate-950">Descripci&oacute;n</a>
    <a href="#endpoint"        class="block text-slate-500 hover:text-slate-950">Endpoint</a>
    <a href="#auth"            class="block text-slate-500 hover:text-slate-950">Autenticaci&oacute;n</a>
    <a href="#headers"         class="block text-slate-500 hover:text-slate-950">Headers</a>
    <a href="#body"            class="block text-slate-500 hover:text-slate-950">Body</a>
    <a href="#document-types"  class="block text-slate-500 hover:text-slate-950">Tipos de comprobante</a>
    <a href="#formats"         class="block text-slate-500 hover:text-slate-950">Formatos</a>
    <a href="#request-example" class="block text-slate-500 hover:text-slate-950">Ejemplo</a>
    <a href="#success-200"     class="block text-slate-500 hover:text-slate-950">Respuesta 200</a>
    <a href="#success-202"     class="block text-slate-500 hover:text-slate-950">Respuesta 202</a>
    <a href="#states"          class="block text-slate-500 hover:text-slate-950">Estados</a>
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
                <span class="text-slate-500">Emitir comprobante</span>
            </div>
            <h1 class="mt-5 text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl">Emitir un comprobante</h1>
            <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600">Env&iacute;a un comprobante electr&oacute;nico (factura, boleta, nota de cr&eacute;dito/d&eacute;bito, resumen o baja) a SUNAT a trav&eacute;s del motor de Signia. La respuesta puede ser <strong>s&iacute;ncrona</strong> (200) cuando el motor resuelve en el momento, o <strong>as&iacute;ncrona</strong> (202) cuando el documento se encola para reintentos autom&aacute;ticos.</p>
            <div class="mt-8 grid gap-3 sm:grid-cols-2 scroll-mt-24">
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Base URL</p>
                    <code class="mt-2 block break-all text-sm font-semibold text-slate-800">https://signia.kore.pe/api/v1</code>
                </div>
                <div class="rounded-xl border border-slate-200 bg-slate-50 p-4">
                    <p class="text-xs font-bold uppercase tracking-wider text-slate-400">Entornos</p>
                    <p class="mt-2 text-sm font-semibold text-slate-800">Demo y producci&oacute;n</p>
                    <p class="mt-1 text-xs leading-5 text-slate-500">El entorno se determina por el RUC de la empresa: si est&aacute; en modo <code>demo</code>, no se descuenta saldo y no se valida en SUNAT real.</p>
                </div>
            </div>
        </section>

        {{-- ENDPOINT --}}
        <section id="endpoint" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Endpoint</h2>
            <div class="mt-5 flex min-w-0 items-center overflow-hidden rounded-xl border border-slate-200 bg-white shadow-sm">
                <span class="self-stretch bg-emerald-500 px-4 py-4 font-mono text-sm font-bold text-white">POST</span>
                <code class="min-w-0 overflow-x-auto px-4 py-4 text-sm font-semibold text-slate-800">/api/v1/documents/send</code>
                <span class="ml-auto mr-4 hidden shrink-0 rounded-full bg-amber-100 px-3 py-1 text-xs font-semibold text-amber-700 sm:block">Requiere Bearer token</span>
            </div>
        </section>

        {{-- AUTH --}}
        <section id="auth" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Autenticaci&oacute;n</h2>
            <p class="mt-3 leading-7 text-slate-600">Este endpoint requiere un token Bearer obtenido previamente con <a href="{{ route('docs.authentication') }}" class="font-semibold text-cyan-700 underline underline-offset-2 hover:text-cyan-900">POST /api/v1/auth/token</a>. El token tiene vigencia de 10 minutos.</p>
            <div class="mt-5 rounded-xl border border-amber-200 bg-amber-50 p-4 text-sm leading-6 text-amber-900">
                <strong>Importante:</strong> si el token ha expirado, recibir&aacute;s un <code>401 Unauthenticated</code>. Solicita uno nuevo antes de reintentar.
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
            <p class="mt-3 leading-7 text-slate-600">Env&iacute;a un objeto JSON con dos claves principales: <code>company</code> (datos del emisor) y <code>document</code> (datos del comprobante). Los campos marcados con <span class="text-rose-600 font-bold">*</span> son validados por el servidor; el resto son consumidos directamente por el motor de facturaci&oacute;n.</p>

            <h3 class="mt-8 text-lg font-semibold text-slate-950">Objeto <code>company</code></h3>
            <div class="mt-4 overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-4 py-3">Campo</th><th class="px-4 py-3">Tipo</th><th class="hidden px-4 py-3 md:table-cell">Descripci&oacute;n</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr><td class="px-4 py-4"><code class="font-semibold text-slate-950">ruc</code><span class="ml-1 text-rose-600">*</span><p class="mt-1 text-xs text-slate-500 md:hidden">RUC de 11 d&iacute;gitos del emisor.</p></td><td class="px-4 py-4">string</td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. RUC del emisor registrado en Signia. Exactamente 11 d&iacute;gitos num&eacute;ricos.</td></tr>
                        <tr><td class="px-4 py-4"><code class="font-semibold text-slate-950">razon_social</code><p class="mt-1 text-xs text-slate-500 md:hidden">Raz&oacute;n social del emisor.</p></td><td class="px-4 py-4">string</td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">Raz&oacute;n social del emisor tal como est&aacute; registrada en SUNAT.</td></tr>
                        <tr><td class="px-4 py-4"><code class="font-semibold text-slate-950">nombre_comercial</code><p class="mt-1 text-xs text-slate-500 md:hidden">Nombre comercial (opcional).</p></td><td class="px-4 py-4">string</td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">Nombre comercial del emisor. Puede omitirse.</td></tr>
                        <tr><td class="px-4 py-4"><code class="font-semibold text-slate-950">ubigeo</code><p class="mt-1 text-xs text-slate-500 md:hidden">C&oacute;digo de ubigeo de 6 d&iacute;gitos.</p></td><td class="px-4 py-4">string</td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">C&oacute;digo de ubigeo INEI de 6 d&iacute;gitos (ej. <code>150101</code> para Lima).</td></tr>
                        <tr><td class="px-4 py-4"><code class="font-semibold text-slate-950">direccion</code><p class="mt-1 text-xs text-slate-500 md:hidden">Direcci&oacute;n fiscal.</p></td><td class="px-4 py-4">string</td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">Direcci&oacute;n fiscal del emisor. Se imprime en el comprobante.</td></tr>
                        <tr><td class="px-4 py-4"><code class="font-semibold text-slate-950">departamento / provincia / distrito</code><p class="mt-1 text-xs text-slate-500 md:hidden">Ubicaci&oacute;n geogr&aacute;fica.</p></td><td class="px-4 py-4">string</td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">Campos de ubicaci&oacute;n geogr&aacute;fica del emisor.</td></tr>
                    </tbody>
                </table>
            </div>

            <h3 class="mt-8 text-lg font-semibold text-slate-950">Objeto <code>document</code></h3>
            <p class="mt-2 text-sm leading-6 text-slate-600">Los cuatro campos siguientes son validados por el servidor. El resto del payload (&iacute;tems, impuestos, totales, datos del cliente, etc.) es consumido directamente por el motor y var&iacute;a seg&uacute;n el tipo de comprobante.</p>
            <div class="mt-4 overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-4 py-3">Campo</th><th class="px-4 py-3">Tipo</th><th class="hidden px-4 py-3 md:table-cell">Descripci&oacute;n</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr><td class="px-4 py-4"><code class="font-semibold text-slate-950">document_type_id</code><span class="ml-1 text-rose-600">*</span><p class="mt-1 text-xs text-slate-500 md:hidden">C&oacute;digo SUNAT del tipo de comprobante.</p></td><td class="px-4 py-4">string</td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. C&oacute;digo SUNAT del tipo de comprobante (ver tabla de tipos).</td></tr>
                        <tr><td class="px-4 py-4"><code class="font-semibold text-slate-950">series</code><span class="ml-1 text-rose-600">*</span><p class="mt-1 text-xs text-slate-500 md:hidden">Serie del comprobante (ej. F001).</p></td><td class="px-4 py-4">string</td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. Serie del comprobante. Las facturas usan prefijo <code>F</code>, boletas <code>B</code>.</td></tr>
                        <tr><td class="px-4 py-4"><code class="font-semibold text-slate-950">number</code><span class="ml-1 text-rose-600">*</span><p class="mt-1 text-xs text-slate-500 md:hidden">Correlativo del comprobante.</p></td><td class="px-4 py-4">string</td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">Obligatorio. N&uacute;mero correlativo del comprobante (ej. <code>00000001</code>).</td></tr>
                        <tr><td class="px-4 py-4"><code class="font-semibold text-slate-950">fecha_emision</code><p class="mt-1 text-xs text-slate-500 md:hidden">Fecha en formato YYYY-MM-DD.</p></td><td class="px-4 py-4">string</td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">Fecha de emisi&oacute;n en formato <code>YYYY-MM-DD</code>. Requerido por el motor.</td></tr>
                        <tr><td class="px-4 py-4"><code class="font-semibold text-slate-950">moneda</code><p class="mt-1 text-xs text-slate-500 md:hidden">C&oacute;digo ISO de moneda (PEN / USD).</p></td><td class="px-4 py-4">string</td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">C&oacute;digo ISO 4217 de la moneda: <code>PEN</code> o <code>USD</code>.</td></tr>
                        <tr><td class="px-4 py-4"><code class="font-semibold text-slate-950">items</code><p class="mt-1 text-xs text-slate-500 md:hidden">Array de l&iacute;neas del comprobante.</p></td><td class="px-4 py-4">array</td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">L&iacute;neas de detalle del comprobante. Estructura var&iacute;a por tipo de documento.</td></tr>
                    </tbody>
                </table>
            </div>
            <p class="mt-3 text-sm leading-6 text-slate-500">El esquema completo del payload (cliente, totales, impuestos, observaciones, etc.) depende del tipo de comprobante. Consulta los ejemplos por tipo en la secci&oacute;n de referencia completa.</p>
        </section>

        {{-- TIPOS --}}
        <section id="document-types" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Tipos de comprobante</h2>
            <p class="mt-3 leading-7 text-slate-600">El valor de <code>document.document_type_id</code> determina el flujo de procesamiento y si la respuesta es s&iacute;ncrona o as&iacute;ncrona.</p>
            <div class="mt-5 overflow-x-auto rounded-xl border border-slate-200">
                <table class="w-full text-left text-sm">
                    <thead class="bg-slate-50 text-xs uppercase tracking-wider text-slate-500"><tr><th class="px-4 py-3">C&oacute;digo</th><th class="px-4 py-3">Tipo</th><th class="hidden px-4 py-3 sm:table-cell">Flujo</th><th class="hidden px-4 py-3 md:table-cell">Nota</th></tr></thead>
                    <tbody class="divide-y divide-slate-100">
                        <tr><td class="px-4 py-4 font-mono font-bold text-slate-900">01</td><td class="px-4 py-4">Factura electr&oacute;nica</td><td class="hidden px-4 py-4 sm:table-cell"><span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-800">S&iacute;ncrono</span></td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">Si hay intermitencia, cae a cola (202).</td></tr>
                        <tr><td class="px-4 py-4 font-mono font-bold text-slate-900">03</td><td class="px-4 py-4">Boleta de venta electr&oacute;nica</td><td class="hidden px-4 py-4 sm:table-cell"><span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-800">S&iacute;ncrono</span></td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">Si hay intermitencia, cae a cola (202).</td></tr>
                        <tr><td class="px-4 py-4 font-mono font-bold text-slate-900">07</td><td class="px-4 py-4">Nota de cr&eacute;dito electr&oacute;nica</td><td class="hidden px-4 py-4 sm:table-cell"><span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-800">S&iacute;ncrono</span></td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">Referencia la factura o boleta original.</td></tr>
                        <tr><td class="px-4 py-4 font-mono font-bold text-slate-900">08</td><td class="px-4 py-4">Nota de d&eacute;bito electr&oacute;nica</td><td class="hidden px-4 py-4 sm:table-cell"><span class="rounded-full bg-emerald-100 px-2 py-0.5 text-xs font-semibold text-emerald-800">S&iacute;ncrono</span></td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">Referencia la factura o boleta original.</td></tr>
                        <tr><td class="px-4 py-4 font-mono font-bold text-slate-900">RC</td><td class="px-4 py-4">Resumen diario de boletas</td><td class="hidden px-4 py-4 sm:table-cell"><span class="rounded-full bg-violet-100 px-2 py-0.5 text-xs font-semibold text-violet-800">As&iacute;ncrono</span></td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">Siempre devuelve 202. Consulta con <code>/consult</code>.</td></tr>
                        <tr><td class="px-4 py-4 font-mono font-bold text-slate-900">RA</td><td class="px-4 py-4">Comunicaci&oacute;n de baja</td><td class="hidden px-4 py-4 sm:table-cell"><span class="rounded-full bg-violet-100 px-2 py-0.5 text-xs font-semibold text-violet-800">As&iacute;ncrono</span></td><td class="hidden px-4 py-4 text-slate-600 md:table-cell">Siempre devuelve 202. Consulta con <code>/consult</code>.</td></tr>
                    </tbody>
                </table>
            </div>
        </section>

        {{-- FORMATOS --}}
        <section id="formats" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Formatos y validaciones</h2>
            <ul class="mt-5 space-y-3 rounded-xl border border-slate-200 bg-slate-50 p-5 text-sm leading-6 text-slate-700">
                <li><strong class="text-slate-950">Codificaci&oacute;n:</strong> UTF-8. No se admite multipart ni form-data.</li>
                <li><strong class="text-slate-950">RUC:</strong> exactamente 11 d&iacute;gitos num&eacute;ricos. El RUC debe estar registrado bajo tu agencia en Signia.</li>
                <li><strong class="text-slate-950">Duplicados:</strong> si el documento (RUC + tipo + serie + n&uacute;mero) ya existe con estado distinto de <code>exception</code> o <code>rejected</code>, el servidor devuelve 422 sin volver a procesar ni cobrar.</li>
                <li><strong class="text-slate-950">Saldo:</strong> en entornos de producci&oacute;n se descuenta un cr&eacute;dito antes de procesar. Si no hay saldo, la API devuelve 400 sin emitir el comprobante.</li>
                <li><strong class="text-slate-950">Throttle:</strong> el l&iacute;mite de rate del grupo <code>api</code> aplica a todos los endpoints protegidos. Al excederlo, recibir&aacute;s 429.</li>
                <li><strong class="text-slate-950">Demo:</strong> los RUC registrados en entorno <code>demo</code> no descuentan saldo y no se validan en SUNAT real.</li>
            </ul>
        </section>

        {{-- EJEMPLO --}}
        <section id="request-example" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Ejemplo de solicitud</h2>
            <p class="mt-3 leading-7 text-slate-600">Factura electr&oacute;nica m&iacute;nima. El payload completo (&iacute;tems, impuestos, datos del cliente) var&iacute;a por tipo de comprobante y motor configurado.</p>
            <div class="mt-5">
                <x-docs.code id="send-request" label="cURL">curl --request POST 'https://signia.kore.pe/api/v1/documents/send' \
  --header 'Accept: application/json' \
  --header 'Content-Type: application/json' \
  --header 'Authorization: Bearer 1|nY2k7...access_token' \
  --data-raw '{
    "company": {
      "ruc": "20100070970",
      "razon_social": "EMPRESA DEMO S.A.C.",
      "nombre_comercial": "Empresa Demo",
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
  }'</x-docs.code>
            </div>
        </section>

        {{-- RESPUESTA 200 --}}
        <section id="success-200" class="mt-14 scroll-mt-24">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-slate-950">Respuesta exitosa &mdash; s&iacute;ncrona</h2>
                <span class="rounded-full bg-emerald-100 px-2.5 py-1 font-mono text-xs font-bold text-emerald-800">200 OK</span>
            </div>
            <p class="mt-3 leading-7 text-slate-600">El motor proces&oacute; el comprobante en tiempo real y SUNAT emiti&oacute; el CDR. El documento queda con estado <code>accepted</code> o <code>accepted_with_observations</code>. Los archivos XML, CDR y PDF est&aacute;n disponibles inmediatamente.</p>
            <div class="mt-5">
                <x-docs.code id="send-200">{
  "success": true,
  "message": "Documento procesado correctamente.",
  "status": "accepted",
  "data": {
    "ticket": "1|nY2k7-ticket-sunat",
    "xml_url": "https://signia.kore.pe/storage/documents/20100070970/2026/09/20100070970-01-F001-00000001.xml",
    "cdr_url": "https://signia.kore.pe/storage/documents/20100070970/2026/09/R-20100070970-01-F001-00000001.zip",
    "pdf_url": "https://signia.kore.pe/storage/documents/20100070970/2026/09/20100070970-01-F001-00000001.pdf"
  },
  "xml_base64": "PD94bWwgdmVyc2lvbj0iMS4wIi...",
  "cdr_base64": "UEsDBAoAAAAAAGdX...",
  "pdf_base64": "JVBERi0xLjQK..."
}</x-docs.code>
            </div>
            <div class="mt-5 grid gap-4 sm:grid-cols-3">
                <div class="rounded-xl border border-slate-200 p-4 text-sm"><p class="font-semibold text-slate-950">xml_base64</p><p class="mt-1 leading-5 text-slate-600">XML firmado enviado a SUNAT. Gu&aacute;rdalo en tu sistema.</p></div>
                <div class="rounded-xl border border-slate-200 p-4 text-sm"><p class="font-semibold text-slate-950">cdr_base64</p><p class="mt-1 leading-5 text-slate-600">Constancia de recepci&oacute;n de SUNAT en ZIP. Evidencia legal.</p></div>
                <div class="rounded-xl border border-slate-200 p-4 text-sm"><p class="font-semibold text-slate-950">pdf_base64</p><p class="mt-1 leading-5 text-slate-600">Representaci&oacute;n impresa del comprobante lista para mostrar.</p></div>
            </div>
        </section>

        {{-- RESPUESTA 202 --}}
        <section id="success-202" class="mt-14 scroll-mt-24">
            <div class="flex items-center gap-3">
                <h2 class="text-2xl font-bold text-slate-950">Respuesta as&iacute;ncrona</h2>
                <span class="rounded-full bg-violet-100 px-2.5 py-1 font-mono text-xs font-bold text-violet-800">202 Accepted</span>
            </div>
            <p class="mt-3 leading-7 text-slate-600">Ocurre en dos casos: (1) el tipo de comprobante es <code>RC</code> o <code>RA</code> (siempre as&iacute;ncrono); o (2) el motor s&iacute;ncrono fall&oacute; por intermitencia con SUNAT y el documento fue encolado para reintentos autom&aacute;ticos. El documento queda con estado <code>in_process</code>.</p>
            <div class="mt-5">
                <x-docs.code id="send-202">{
  "success": true,
  "message": "Intermitencia con SUNAT. El documento ha sido encolado para reintentos automaticos. Use el ticket para consultar.",
  "status": "in_process",
  "data": {
    "ticket": "SIG-ABCDEFGH1234"
  }
}</x-docs.code>
            </div>
            <div class="mt-5 rounded-xl border border-slate-200 bg-slate-50 p-4 text-sm leading-6 text-slate-700">
                <strong class="text-slate-950">&iquest;Qu&eacute; hacer con un 202?</strong> Guarda el <code>ticket</code> y consulta el estado peri&oacute;dicamente con <code>POST /api/v1/documents/consult</code> hasta obtener un estado final (<code>accepted</code>, <code>accepted_with_observations</code> o <code>rejected</code>). Los tickets que empiezan con <code>SIG-</code> a&uacute;n no llegaron a SUNAT; los que no, son tickets reales de SUNAT.
            </div>
        </section>

        {{-- ESTADOS --}}
        <section id="states" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Estados del documento</h2>
            <p class="mt-3 leading-7 text-slate-600">El campo <code>status</code> en la respuesta refleja el estado actual del comprobante en Signia. Los estados finales no cambian; los intermedios pueden evolucionar al consultar con el endpoint <code>/consult</code>.</p>
            <div class="mt-5 space-y-4">

                <details class="group rounded-xl border border-slate-200 p-5" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950">
                        <span class="flex items-center gap-3"><span class="h-2.5 w-2.5 rounded-full bg-violet-500"></span><code class="text-violet-700">in_process</code> &mdash; En proceso</span>
                        <span class="text-slate-400 group-open:rotate-45">+</span>
                    </summary>
                    <div class="mt-4 space-y-2 text-sm leading-6 text-slate-600">
                        <p>El comprobante fue recibido y est&aacute; encolado para procesamiento. A&uacute;n no se ha enviado a SUNAT o el resultado est&aacute; pendiente.</p>
                        <ul class="ml-4 list-disc space-y-1">
                            <li>Aparece en la respuesta 202 inmediatamente tras el env&iacute;o.</li>
                            <li>Los tickets <code>SIG-XXXX</code> indican que el worker a&uacute;n no tom&oacute; el documento.</li>
                            <li>Consulta peri&oacute;dicamente con <code>/consult</code> hasta obtener un estado final.</li>
                            <li>Se recomienda reintentar cada 10&ndash;30 segundos, hasta 5 minutos.</li>
                        </ul>
                    </div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950">
                        <span class="flex items-center gap-3"><span class="h-2.5 w-2.5 rounded-full bg-emerald-500"></span><code class="text-emerald-700">accepted</code> &mdash; Aceptado</span>
                        <span class="text-slate-400 group-open:rotate-45">+</span>
                    </summary>
                    <div class="mt-4 space-y-2 text-sm leading-6 text-slate-600">
                        <p>SUNAT acept&oacute; el comprobante sin observaciones. Es un estado final; el CDR est&aacute; disponible.</p>
                        <ul class="ml-4 list-disc space-y-1">
                            <li>Devuelto directamente en la respuesta 200 si el procesamiento fue s&iacute;ncrono.</li>
                            <li>Tambi&eacute;n puede aparecer al consultar con <code>/consult</code> tras un 202.</li>
                            <li>El XML, CDR y PDF est&aacute;n disponibles v&iacute;a las URLs o los campos <code>*_base64</code>.</li>
                        </ul>
                    </div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950">
                        <span class="flex items-center gap-3"><span class="h-2.5 w-2.5 rounded-full bg-amber-400"></span><code class="text-amber-700">accepted_with_observations</code> &mdash; Aceptado con observaciones</span>
                        <span class="text-slate-400 group-open:rotate-45">+</span>
                    </summary>
                    <div class="mt-4 space-y-2 text-sm leading-6 text-slate-600">
                        <p>SUNAT acept&oacute; el comprobante pero report&oacute; observaciones no bloqueantes en el CDR. Es un estado final; el comprobante es v&aacute;lido.</p>
                        <ul class="ml-4 list-disc space-y-1">
                            <li>El comprobante tiene validez tributaria a pesar de las observaciones.</li>
                            <li>Revisa el CDR para identificar los c&oacute;digos de observaci&oacute;n reportados por SUNAT.</li>
                            <li>Las observaciones m&aacute;s comunes son sobre decimales o datos complementarios.</li>
                        </ul>
                    </div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950">
                        <span class="flex items-center gap-3"><span class="h-2.5 w-2.5 rounded-full bg-rose-500"></span><code class="text-rose-700">rejected</code> &mdash; Rechazado por SUNAT</span>
                        <span class="text-slate-400 group-open:rotate-45">+</span>
                    </summary>
                    <div class="mt-4 space-y-2 text-sm leading-6 text-slate-600">
                        <p>SUNAT rechaz&oacute; el comprobante. Es un estado final; el comprobante no tiene validez tributaria.</p>
                        <ul class="ml-4 list-disc space-y-1">
                            <li>El CDR contiene el c&oacute;digo y descripci&oacute;n del rechazo de SUNAT.</li>
                            <li>El documento puede reintentarse con la misma serie/n&uacute;mero si corriges el error usando <code>POST /api/v1/documents/reintentar</code>.</li>
                            <li>Causas comunes: firma inv&aacute;lida, datos del emisor incorrectos, IGV incorrecto.</li>
                        </ul>
                    </div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950">
                        <span class="flex items-center gap-3"><span class="h-2.5 w-2.5 rounded-full bg-slate-400"></span><code class="text-slate-700">exception</code> &mdash; Error interno del motor</span>
                        <span class="text-slate-400 group-open:rotate-45">+</span>
                    </summary>
                    <div class="mt-4 space-y-2 text-sm leading-6 text-slate-600">
                        <p>El motor de Signia no pudo procesar el comprobante por un error grave antes de contactar a SUNAT. El documento no lleg&oacute; a SUNAT.</p>
                        <ul class="ml-4 list-disc space-y-1">
                            <li>Causas t&iacute;picas: certificado digital inv&aacute;lido o vencido, datos de empresa incompletos en Signia, error en el payload enviado.</li>
                            <li>El documento puede reintentarse con <code>POST /api/v1/documents/reintentar</code> tras corregir la causa.</li>
                            <li>Contacta al soporte de Signia si el error persiste con datos correctos.</li>
                        </ul>
                    </div>
                </details>

            </div>
        </section>

        {{-- ERRORES --}}
        <section id="errors" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Errores</h2>
            <div class="mt-5 space-y-4">

                <details class="group rounded-xl border border-slate-200 p-5" open>
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">400</code> Saldo insuficiente</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">La agencia no tiene cr&eacute;ditos suficientes para procesar el comprobante (solo en producci&oacute;n). No se cre&oacute; ni modific&oacute; ning&uacute;n documento.</p>
                    <div class="mt-4"><x-docs.code id="send-400">{
  "status": "error",
  "message": "Saldo insuficiente para procesar el comprobante."
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-rose-600">401</code> No autenticado / sin agencia</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">El token es inv&aacute;lido, ha expirado, o el usuario asociado al token no tiene una agencia vinculada en Signia.</p>
                    <div class="mt-4"><x-docs.code id="send-401">{
  "error": "Agency not found"
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-amber-600">404</code> RUC no registrado</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">El RUC enviado en <code>company.ruc</code> no est&aacute; registrado bajo la agencia autenticada. Crea la empresa primero con el endpoint <code>POST /api/v1/empresa/crear</code>.</p>
                    <div class="mt-4"><x-docs.code id="send-404">{
  "error": "RUC no registrado bajo esta agencia."
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-amber-600">422</code> Documento duplicado o validaci&oacute;n fallida</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Puede ocurrir por dos razones:</p>
                    <ul class="mt-2 ml-4 list-disc space-y-1 text-sm text-slate-600">
                        <li>El documento ya existe con un estado distinto de <code>exception</code> o <code>rejected</code> (ya fue procesado o est&aacute; en cola).</li>
                        <li>Falta un campo requerido o su formato es inv&aacute;lido.</li>
                    </ul>
                    <div class="mt-4"><x-docs.code id="send-422">{
  "error": "El documento ya existe y esta procesado o en cola."
}

// -- o por validacion de campos --

{
  "message": "El campo company.ruc es obligatorio.",
  "errors": {
    "company.ruc": ["El campo company.ruc es obligatorio."]
  }
}</x-docs.code></div>
                </details>

                <details class="group rounded-xl border border-slate-200 p-5">
                    <summary class="flex cursor-pointer list-none items-center justify-between gap-4 font-semibold text-slate-950"><span><code class="mr-2 text-violet-600">429</code> Demasiadas solicitudes</span><span class="text-slate-400 group-open:rotate-45">+</span></summary>
                    <p class="mt-3 text-sm leading-6 text-slate-600">Se super&oacute; el l&iacute;mite de rate del grupo <code>api</code>. Espera el n&uacute;mero de segundos indicado en el header <code>Retry-After</code> antes de reintentar.</p>
                    <div class="mt-4"><x-docs.code id="send-429">{
  "message": "Too Many Attempts."
}</x-docs.code></div>
                </details>

            </div>
        </section>

        {{-- TIMEOUTS --}}
        <section id="timeouts" class="mb-20 mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Errores de conexi&oacute;n y timeouts</h2>
            <p class="mt-3 leading-7 text-slate-600">A diferencia del endpoint de autenticaci&oacute;n, <code>/documents/send</code> realiza operaciones externas (SUNAT, motor de firma). Si el cliente no recibe respuesta antes de su propio timeout, el servidor puede haber encolado el documento igualmente.</p>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Timeout recomendado</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Configura al menos <strong>30 segundos</strong> en el cliente para dar tiempo al procesamiento s&iacute;ncrono con SUNAT. Nunca asumas que un timeout implica que no se proces&oacute;.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Timeout del cliente sin respuesta</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Si tu cliente corta la conexi&oacute;n antes de recibir la respuesta, consulta el estado con <code>/consult</code> usando el mismo RUC + serie + n&uacute;mero para verificar si se proces&oacute;.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Intermitencia con SUNAT</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600">Cuando SUNAT est&aacute; intermitente, el servidor encola el documento y devuelve 202. <strong>No reintentes el env&iacute;o</strong>: el sistema reintentar&aacute; autom&aacute;ticamente hasta que SUNAT responda.</p>
                </div>
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Estados que no aplican en esta respuesta</p>
                    <p class="mt-2 text-sm leading-6 text-slate-600"><code>rejected</code> y <code>exception</code> solo aparecen en el endpoint <code>/consult</code>, no directamente en la respuesta de <code>/send</code>.</p>
                </div>
            </div>
        </section>

    </article>
@endsection
