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
    "total_op_taxed": 100.00,
    "total_igv": 18.00,
    "total_taxes": 18.00,
    "total_value": 100.00,
    "total_document": 118.00
  },
  "customer": {
    "identity_document_type_id": "6",
    "number": "20500000001",
    "name": "CLIENTE EMPRESA S.A.C.",
    "address": "Av. Los Negocios 123",
    "email": "facturacion@cliente.com"
  },
  "items": [
    {
      "internal_id": "PROD-001",
      "description": "Servicio de Desarrollo Web",
      "unit_type_id": "ZZ",
      "quantity": 1,
      "unit_value": 100.00,
      "unit_price": 118.00,
      "affectation_igv_type_id": "10",
      "total_base_igv": 100.00,
      "percentage_igv": 18,
      "total_igv": 18.00,
      "total_taxes": 18.00,
      "total_value": 100.00,
      "total_item": 118.00
    }
  ]
}</x-docs.code>
            </div>
            <ul class="mt-5 space-y-2 text-sm text-slate-600 list-disc ml-4">
                <li><code>document_type_id: "01"</code> indica que es una Factura.</li>
                <li>El cliente debe tener <code>identity_document_type_id: "6"</code> (RUC).</li>
                <li><code>affectation_igv_type_id: "10"</code> indica una operaci&oacute;n Gravada - Operaci&oacute;n Onerosa.</li>
            </ul>
        </section>

        {{-- BOLETA --}}
        <section id="boleta" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Boleta de Venta (Tipo 03)</h2>
            <p class="mt-3 leading-7 text-slate-600">Ejemplo para emitir una Boleta de venta a un cliente final (DNI).</p>
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
    "time_of_issue": "10:15:00",
    "currency_type_id": "PEN",
    "total_op_taxed": 50.00,
    "total_igv": 9.00,
    "total_taxes": 9.00,
    "total_value": 50.00,
    "total_document": 59.00
  },
  "customer": {
    "identity_document_type_id": "1",
    "number": "45678912",
    "name": "JUAN PEREZ",
    "address": "Calle Las Rosas 456"
  },
  "items": [
    {
      "internal_id": "PROD-002",
      "description": "Producto de prueba",
      "unit_type_id": "NIU",
      "quantity": 1,
      "unit_value": 50.00,
      "unit_price": 59.00,
      "affectation_igv_type_id": "10",
      "total_base_igv": 50.00,
      "percentage_igv": 18,
      "total_igv": 9.00,
      "total_taxes": 9.00,
      "total_value": 50.00,
      "total_item": 59.00
    }
  ]
}</x-docs.code>
            </div>
            <ul class="mt-5 space-y-2 text-sm text-slate-600 list-disc ml-4">
                <li><code>document_type_id: "03"</code> indica que es una Boleta.</li>
                <li>El cliente usa <code>identity_document_type_id: "1"</code> (DNI). Si es venta menor, se puede usar "0" (Doc.trib.no.dom.sin.ruc) u "8" (Extranjeria).</li>
            </ul>
        </section>

        {{-- NOTA DE CREDITO --}}
        <section id="nota-credito" class="mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Nota de Cr&eacute;dito (Tipo 07)</h2>
            <p class="mt-3 leading-7 text-slate-600">Ejemplo para emitir una Nota de Cr&eacute;dito que anula por completo una factura anterior. Se debe enviar el nodo <code>note</code> con la referencia al comprobante afectado.</p>
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
    "total_op_taxed": 100.00,
    "total_igv": 18.00,
    "total_taxes": 18.00,
    "total_value": 100.00,
    "total_document": 118.00
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
      "affectation_igv_type_id": "10",
      "total_base_igv": 100.00,
      "percentage_igv": 18,
      "total_igv": 18.00,
      "total_taxes": 18.00,
      "total_value": 100.00,
      "total_item": 118.00
    }
  ]
}</x-docs.code>
            </div>
            <ul class="mt-5 space-y-2 text-sm text-slate-600 list-disc ml-4">
                <li><code>document_type_id: "07"</code> indica Nota de Cr&eacute;dito.</li>
                <li>La serie debe iniciar con "F" o "B" dependiendo de si afecta a una Factura o Boleta (ej. <code>FC01</code> para anular una factura, <code>BC01</code> para una boleta).</li>
                <li>El objeto <code>note</code> es obligatorio y debe especificar el tipo de nota (Cat&aacute;logo 09 de SUNAT, donde "01" es Anulaci&oacute;n de operaci&oacute;n) y el comprobante afectado.</li>
            </ul>
        </section>

        {{-- CATALOGOS --}}
        <section id="catalogs" class="mb-20 mt-14 scroll-mt-24">
            <h2 class="text-2xl font-bold text-slate-950">Cat&aacute;logos SUNAT de uso frecuente</h2>
            <div class="mt-5 grid gap-4 sm:grid-cols-2">
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Tipos de Documento (<code>document_type_id</code>)</p>
                    <ul class="mt-2 text-sm leading-6 text-slate-600">
                        <li><strong>01</strong>: Factura</li>
                        <li><strong>03</strong>: Boleta de Venta</li>
                        <li><strong>07</strong>: Nota de Cr&eacute;dito</li>
                        <li><strong>08</strong>: Nota de D&eacute;bito</li>
                    </ul>
                </div>
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Tipos de Afectaci&oacute;n IGV (<code>affectation_igv_type_id</code>)</p>
                    <ul class="mt-2 text-sm leading-6 text-slate-600">
                        <li><strong>10</strong>: Gravado - Operaci&oacute;n Onerosa</li>
                        <li><strong>20</strong>: Exonerado - Operaci&oacute;n Onerosa</li>
                        <li><strong>30</strong>: Inafecto - Operaci&oacute;n Onerosa</li>
                    </ul>
                </div>
                <div class="rounded-xl border border-slate-200 p-5">
                    <p class="font-semibold text-slate-950">Docs. de Identidad (<code>identity_document_type_id</code>)</p>
                    <ul class="mt-2 text-sm leading-6 text-slate-600">
                        <li><strong>0</strong>: Doc.trib.no.dom.sin.ruc</li>
                        <li><strong>1</strong>: DNI</li>
                        <li><strong>4</strong>: Carnet de Extranjer&iacute;a</li>
                        <li><strong>6</strong>: RUC</li>
                    </ul>
                </div>
            </div>
        </section>

    </article>
@endsection
