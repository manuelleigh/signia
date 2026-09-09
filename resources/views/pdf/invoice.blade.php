<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <title>Factura Electrónica</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        .header { text-align: center; margin-bottom: 20px; }
        .company-name { font-size: 18px; font-weight: bold; }
        .document-type { border: 1px solid #000; padding: 10px; font-size: 16px; font-weight: bold; margin-top: 10px; }
        .table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        .table th, .table td { border: 1px solid #ccc; padding: 8px; text-align: left; }
        .totals { margin-top: 20px; text-align: right; }
        .watermark { position: absolute; top: 30%; left: 10%; font-size: 60px; color: rgba(200, 200, 200, 0.3); transform: rotate(-45deg); }
    </style>
</head>
<body>
    <div class="watermark">Signia B2B API</div>
    
    <div class="header">
        <div class="company-name">{{ $company['trade_name'] ?? $company['name'] }}</div>
        <div>RUC: {{ $company['ruc'] }}</div>
        <div>{{ $company['address'] ?? '' }}</div>
        <div class="document-type">
            FACTURA ELECTRÓNICA<br>
            {{ $document['series'] }} - {{ $document['number'] }}
        </div>
    </div>

    <div>
        <strong>Cliente:</strong> {{ $customer['name'] }}<br>
        <strong>RUC/DNI:</strong> {{ $customer['number'] }}<br>
        <strong>Fecha Emisión:</strong> {{ $document['date_of_issue'] }}<br>
        <strong>Moneda:</strong> {{ $document['currency_type_id'] }}
    </div>

    <table class="table">
        <thead>
            <tr>
                <th>Cant.</th>
                <th>Descripción</th>
                <th>P. Unitario</th>
                <th>Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $item)
            <tr>
                <td>{{ $item['quantity'] }}</td>
                <td>{{ $item['description'] }}</td>
                <td>{{ $item['unit_price'] }}</td>
                <td>{{ $item['total_value'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="totals">
        <p><strong>OP. GRAVADAS:</strong> {{ $document['total_taxed'] }}</p>
        <p><strong>IGV (18%):</strong> {{ $document['total_igv'] }}</p>
        <p><strong>TOTAL A PAGAR:</strong> {{ $document['total'] }}</p>
    </div>
</body>
</html>
