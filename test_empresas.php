<?php
require __DIR__.'/vendor/autoload.php';
\ = require_once __DIR__.'/bootstrap/app.php';
\->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

\ = config('services.qpse.token');
if(!\) { echo 'NO TOKEN'; exit; }

\ = Illuminate\Support\Facades\Http::withToken(\)->get('https://cpanel.qpse.pe/api/empresas');
echo json_encode(\->json(), JSON_PRETTY_PRINT);
