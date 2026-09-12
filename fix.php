<?php
$content = file_get_contents('app/Services/Signia/Engines/QpseEngine.php');
$content = str_replace(
    '$expectedTotal = round(($item[\'unit_value\'] * $item[\'quantity\']) + ($item[\'total_igv\'] ?? 0), 2);',
    '$expectedValue = round($item[\'unit_value\'] * $item[\'quantity\'], 2);',
    $content
);
$content = str_replace(
    'abs($item[\'total_value\'] - $expectedTotal)',
    'abs($item[\'total_value\'] - $expectedValue)',
    $content
);
$content = str_replace(
    '(esperado: {$expectedTotal})',
    '(esperado: {$expectedValue})',
    $content
);
$content = str_replace(
    '$calcTotal += $item[\'total_value\'];',
    '$calcTotal += $item[\'total_value\'] + ($item[\'total_igv\'] ?? 0);',
    $content
);
file_put_contents('app/Services/Signia/Engines/QpseEngine.php', $content);
echo "DONE";
