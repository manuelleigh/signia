<?php
// 1. Replace qpse->pse in docs (only public-facing strings, not internal code)
$docsFile = 'resources/views/docs.blade.php';
$docs = file_get_contents($docsFile);

// Replace the engine_type description references
$docs = str_replace(
    'engine_type</code></td><td>string</td><td><code>qpse</code> (motor delegado) o <code>native</code> (certificado propio).',
    'engine_type</code></td><td>string</td><td><code>pse</code> (firma delegada) o <code>native</code> (certificado propio).',
    $docs
);
$docs = str_replace(
    '"engine_type": "qpse",',
    '"engine_type": "pse",',
    $docs
);
$docs = str_replace(
    '"engine_type": "qpse"',
    '"engine_type": "pse"',
    $docs
);
$docs = str_replace(
    'engine_type</code></td><td>string</td><td><strong>Sí</strong></td><td><code>qpse</code> para firma delegada o <code>native</code> para certificado propio.',
    'engine_type</code></td><td>string</td><td><strong>Sí</strong></td><td><code>pse</code> para firma delegada por Signia, o <code>native</code> para certificado digital propio.',
    $docs
);
$docs = str_replace(
    '<code>qpse</code> (firma delegada) o <code>native</code> (certificado propio).',
    '<code>pse</code> (firma delegada por Signia) o <code>native</code> (certificado propio).',
    $docs
);
$docs = str_replace('"engine_type": "qpse"', '"engine_type": "pse"', $docs);
$docs = str_replace("engine_type: qpse", "engine_type: pse", $docs);
$docs = str_replace("'qpse'", "'pse'", $docs);
// Remaining occurrences (qpse_username in response examples is fine - it's not QPSE branded, but let's rename to pse_username)
$docs = str_replace('"qpse_username":', '"pse_username":', $docs);

file_put_contents($docsFile, $docs);
echo "Docs: " . substr_count($docs, 'qpse') . " qpse occurrences remaining\n";

// 2. Update CompanyController API to accept 'pse' externally, store 'qpse' internally
$controllerFile = 'app/Http/Controllers/Api/CompanyController.php';
$controller = file_get_contents($controllerFile);

// Change validation to accept 'pse' instead of 'qpse'
$controller = str_replace(
    "'engine_type' => 'required|in:qpse,native'",
    "'engine_type' => 'required|in:pse,native'",
    $controller
);

// Map pse -> qpse internally before storing
$controller = str_replace(
    '$engineType = $request->engine_type;',
    '$engineType = $request->engine_type;' . "\n        " . '// Map public alias to internal engine name' . "\n        " . '$internalEngine = $engineType === \'pse\' ? \'qpse\' : $engineType;',
    $controller
);
// Use $internalEngine for all internal logic
$controller = str_replace('if ($engineType === \'qpse\')', 'if ($internalEngine === \'qpse\')', $controller);
$controller = str_replace("'engine_type' => \$engineType,", "'engine_type' => \$internalEngine,", $controller);
$controller = str_replace("'engine_type' => \$request->engine_type,", "'engine_type' => \$internalEngine,", $controller);

// In responses: map qpse -> pse when returning data
$controller = str_replace(
    "'engine_type' => \$company->engine_type,",
    "'engine_type' => \$company->engine_type === 'qpse' ? 'pse' : \$company->engine_type,",
    $controller
);
$controller = str_replace(
    "'username' => \$company->qpse_username,",
    "'pse_username' => \$company->qpse_username,",
    $controller
);
$controller = str_replace(
    "'password' => \$company->qpse_password",
    "'pse_password' => \$company->qpse_password",
    $controller
);

file_put_contents($controllerFile, $controller);
echo "Controller updated\n";

// 3. Update index() response in CompanyController to mask qpse field names
$controller = file_get_contents($controllerFile);
$controller = str_replace(
    "->select('id', 'ruc', 'business_name', 'environment', 'engine_type', 'qpse_username', 'sol_user', 'created_at')",
    "->select('id', 'ruc', 'business_name', 'environment', 'engine_type', 'qpse_username', 'sol_user', 'created_at')",
    $controller
);

// After the get(), transform to mask internal field names
$controller = str_replace(
    "\$companies = \$agency->companies()\n            ->select('id', 'ruc', 'business_name', 'environment', 'engine_type', 'qpse_username', 'sol_user', 'created_at')\n            ->get();\n\n        return response()->json([\n            'success' => true,\n            'data' => \$companies\n        ]);",
    "\$companies = \$agency->companies()\n            ->select('id', 'ruc', 'business_name', 'environment', 'engine_type', 'qpse_username', 'sol_user', 'created_at')\n            ->get()\n            ->map(function(\$c) {\n                return [\n                    'id' => \$c->id,\n                    'ruc' => \$c->ruc,\n                    'business_name' => \$c->business_name,\n                    'environment' => \$c->environment,\n                    'engine_type' => \$c->engine_type === 'qpse' ? 'pse' : \$c->engine_type,\n                    'pse_username' => \$c->qpse_username,\n                    'sol_user' => \$c->sol_user,\n                    'created_at' => \$c->created_at,\n                ];\n            });\n\n        return response()->json([\n            'success' => true,\n            'data' => \$companies\n        ]);",
    $controller
);

file_put_contents($controllerFile, $controller);
echo "Controller index masked\n";

// 4. Strip BOM if any
foreach ([$docsFile, $controllerFile] as $f) {
    $bytes = file_get_contents($f);
    if (substr($bytes, 0, 3) === "\xEF\xBB\xBF") {
        file_put_contents($f, substr($bytes, 3));
        echo "BOM stripped from $f\n";
    }
}

echo "All done";
