<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();
$company = \App\Models\Company::where('ruc', '10759145335')->first();
if(!$company) { echo "NO COMPANY"; exit; }
$docs = \App\Models\Document::where('company_id', $company->id)
    ->orderBy('id', 'desc')->take(10)
    ->get(['id', 'document_type', 'serie', 'number', 'status', 'created_at']);
echo json_encode($docs, JSON_PRETTY_PRINT);
