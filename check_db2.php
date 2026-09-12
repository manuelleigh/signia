<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$companies = App\Models\Company::all(['id', 'ruc', 'business_name'])->toArray();
echo json_encode($companies, JSON_PRETTY_PRINT);
