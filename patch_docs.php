<?php
$file = 'resources/views/docs.blade.php';
$content = file_get_contents($file);

// The marker left in place from previous patch
$needle = '<!-- B2B EMPRESAS (REEMPLAZADO) -->';

// Fallback: still look for the original marker from first patch
if (strpos($content, $needle) === false) {
    $needle = '<!-- B2B EMPRESAS -->';
}

// Find from needle up to <!-- CATALOGOS SECTION -->
$start = strpos($content, $needle);
$end   = strpos($content, '<!-- CATALOGOS SECTION -->');

if ($start === false || $end === false) {
    die("Markers not found. start=" . ($start === false ? 'NOT FOUND' : $start) . " end=" . ($end === false ? 'NOT FOUND' : $end));
}

$newSections = file_get_contents('docs/b2b_full.html');
$before = substr($content, 0, $start);
$after  = substr($content, $end);

$content = $before . $newSections . "\n                " . $after;
file_put_contents($file, $content);
echo "Done";
