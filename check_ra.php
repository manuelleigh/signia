<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$docs = \App\Models\Document::where('document_type', 'RA')
    ->where('serie', '20260922')
    ->get(['id', 'company_id', 'serie', 'number', 'status', 'ticket', 'created_at']);
    
// We will also get the error from the ticket or check if there is a sunat response saved somewhere.
// Let's just output the docs first.
echo json_encode($docs, JSON_PRETTY_PRINT);
