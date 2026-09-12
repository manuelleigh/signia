@extends('docs.layout')

@section('title', 'Modelos JSON')

@section('toc')
    <a href="#overview"        class="block text-slate-500 hover:text-slate-950">Descripci&oacute;n</a>
    <a href="#factura"         class="block text-slate-500 hover:text-slate-950">Factura Electr&oacute;nica</a>
    <a href="#boleta"          class="block text-slate-500 hover:text-slate-950">Boleta de Venta</a>
    <a href="#nota-credito"    class="block text-slate-500 hover:text-slate-950">Nota de Cr&eacute;dito</a>
    <a href="#catalogs"        class="block text-slate-500 hover:text-slate-950">Cat&aacute;logos SUNAT</a>
@endsection

@section('content')
    <article class="mx-auto max-w-4xl">

        {{-- OVERVIEW --}}
        <section id="overview" class="scroll-mt-24">
            <div class="flex flex-wrap items-center gap-2 text-sm font-semibold">
                <span class="text-cyan-700">Comprobantes</span>
                <span class="text-slate-300">/</span>
                <span class="text-slate-500">Modelos JSON</span>
            </div>
            <h1 class="mt-5 text-4xl font-bold tracking-tight text-slate-950 sm:text-5xl">Modelos de Comprobantes</h1>
            <p class="mt-5 max-w-3xl text-lg leading-8 text-slate-600">Referencia completa de los payloads requeridos por el endpoint <code>/documents/send</code>. La estructura del objeto JSON var&iacute;a dependiendo del tipo de comprobante que desees emitir.</p>
        </section>

        {{-- FACTURA --}}
        <section id="factura" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Factura Electr&oacute;nica (Tipo 01)</h2>
            <p class="mt-3 leading-7 text-slate-600">Ejemplo completo para emitir una Factura con operaciones gravadas e IGV (18%).</p>
            <div class="mt-5">
                <x-docs.code id="json-factura">{
  "company": {
    "ruc": "20100070970"
  },
  "document": {
    "document_type_id": "01",
    "series": "F001",
    "number": "123",
    "date_of_issue": "2026-09-11",
    "time_of_issue": "15:30:00",
    "currency_type_id": "PEN",
    "total_taxed": 100.00,
    "total_igv": 18.00,
    "total_value": 100.00,
    "total": 118.00
  },
  "customer": {
    "identity_document_type_id": "6",
    "number": "20500000001",
    "name": "CLIENTE EMPRESA S.A.C."
  },
  "items": [
    {
      "internal_id": "PROD-001",
      "description": "Servicio de Desarrollo Web",
      "unit_type_id": "ZZ",
      "quantity": 1,
      "unit_value": 100.00,
      "unit_price": 118.00,
      "total_base_igv": 100.00,
      "percentage_igv": 18,
      "total_igv": 18.00,
      "total_value": 100.00,
      "affectation_igv_type_id": "10"
    }
  ]
}</x-docs.code>
            </div>
        </section>

        {{-- BOLETA --}}
        <section id="boleta" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Boleta de Venta (Tipo 03)</h2>
            <p class="mt-3 leading-7 text-slate-600">Ejemplo para emitir una Boleta de Venta a un cliente con DNI.</p>
            <div class="mt-5">
                <x-docs.code id="json-boleta">{
  "company": {
    "ruc": "20100070970"
  },
  "document": {
    "document_type_id": "03",
    "series": "B001",
    "number": "456",
    "date_of_issue": "2026-09-11",
    "time_of_issue": "16:00:00",
    "currency_type_id": "PEN",
    "total_taxed": 50.00,
    "total_igv": 9.00,
    "total_value": 50.00,
    "total": 59.00
  },
  "customer": {
    "identity_document_type_id": "1",
    "number": "12345678",
    "name": "JUAN PEREZ"
  },
  "items": [
    {
      "internal_id": "PROD-002",
      "description": "Licencia de Software",
      "unit_type_id": "NIU",
      "quantity": 1,
      "unit_value": 50.00,
      "unit_price": 59.00,
      "total_base_igv": 50.00,
      "percentage_igv": 18,
      "total_igv": 9.00,
      "total_value": 50.00,
      "affectation_igv_type_id": "10"
    }
  ]
}</x-docs.code>
            </div>
        </section>

        {{-- NOTA DE CREDITO --}}
        <section id="nota-credito" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Nota de Cr&eacute;dito (Tipo 07)</h2>
            <p class="mt-3 leading-7 text-slate-600">Ejemplo para emitir una Nota de Cr&eacute;dito que anula por completo una factura anterior. Se debe enviar el nodo <code>note</code> con la referencia al comprobante afectado. Usa los mismos campos de totales que una Factura.</p>
            <div class="mt-5">
                <x-docs.code id="json-nc">{
  "company": {
    "ruc": "20100070970"
  },
  "document": {
    "document_type_id": "07",
    "series": "FC01",
    "number": "1",
    "date_of_issue": "2026-09-12",
    "time_of_issue": "09:00:00",
    "currency_type_id": "PEN",
    "total_taxed": 100.00,
    "total_igv": 18.00,
    "total_value": 100.00,
    "total": 118.00
  },
  "customer": {
    "identity_document_type_id": "6",
    "number": "20500000001",
    "name": "CLIENTE EMPRESA S.A.C."
  },
  "note": {
    "note_credit_type_id": "01",
    "note_description": "Anulación de la operación",
    "affected_document": {
      "document_type_id": "01",
      "series": "F001",
      "number": "123"
    }
  },
  "items": [
    {
      "internal_id": "PROD-001",
      "description": "Servicio de Desarrollo Web",
      "unit_type_id": "ZZ",
      "quantity": 1,
      "unit_value": 100.00,
      "unit_price": 118.00,
      "total_base_igv": 100.00,
      "percentage_igv": 18,
      "total_igv": 18.00,
      "total_value": 100.00,
      "affectation_igv_type_id": "10"
    }
  ]
}</x-docs.code>
            </div>
        </section>

        {{-- CATALOGS --}}
        <section id="catalogs" class="mb-20 mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Cat&aacute;logos SUNAT frecuentes</h2>
            <div class="mt-5 space-y-8">
                <div>
                    <h3 class="font-semibold text-slate-900">Tipo de Documento (<code>document_type_id</code>)</h3>
                    <ul class="mt-2 list-inside list-disc text-sm text-slate-600">
                        <li><code>01</code> - Factura</li>
                        <li><code>03</code> - Boleta de Venta</li>
                        <li><code>07</code> - Nota de Cr&eacute;dito</li>
                        <li><code>08</code> - Nota de D&eacute;bito</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Tipo de Documento de Identidad (<code>identity_document_type_id</code>)</h3>
                    <ul class="mt-2 list-inside list-disc text-sm text-slate-600">
                        <li><code>1</code> - DNI</li>
                        <li><code>4</code> - Carnet de Extranjer&iacute;a</li>
                        <li><code>6</code> - RUC</li>
                    </ul>
                </div>
                <div>
                    <h3 class="font-semibold text-slate-900">Tipo de Afectaci&oacute;n del IGV (<code>affectation_igv_type_id</code>)</h3>
                    <ul class="mt-2 list-inside list-disc text-sm text-slate-600">
                        <li><code>10</code> - Gravado - Operaci&oacute;n Onerosa</li>
                        <li><code>20</code> - Exonerado - Operaci&oacute;n Onerosa</li>
                        <li><code>30</code> - Inafecto - Operaci&oacute;n Onerosa</li>
                    </ul>
                </div>
            </div>
        </section>

    </article>
@endsection
