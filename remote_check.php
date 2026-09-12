<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$company = App\Models\Company::where('ruc', '10759145335')->first();
if (!$company) { echo "No company"; exit; }
$docs = App\Models\Document::where('company_id', $company->id)->where('serie', 'B001')->get(['id', 'document_type', 'serie', 'number', 'status', 'created_at'])->toArray();
echo json_encode($docs, JSON_PRETTY_PRINT);
