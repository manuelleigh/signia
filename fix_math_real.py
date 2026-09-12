import re

with open("app/Services/Signia/Engines/QpseEngine.php", "r", encoding="utf-8") as f:
    content = f.read()

old_block = """                // Sugerencia de matem??ticas
                if (isset($item['unit_value']) && isset($item['quantity']) && isset($item['total_value'])) {
                    $expectedTotal = round(($item['unit_value'] * $item['quantity']) + ($item['total_igv'] ?? 0), 2);
                    if (abs($item['total_value'] - $expectedTotal) > 0.5) {
                        $errors[] = "Item [$index]: Matem??ticas incorrectas. El 'total_value' que enviaste ({$item['total_value']}) no coincide con (unit_value * quantity) (esperado: {$expectedTotal}).";
                    }
                    $calcTotal += $item['total_value'];
                }"""

new_block = """                // Sugerencia de matem??ticas
                if (isset($item['unit_value']) && isset($item['quantity']) && isset($item['total_value'])) {
                    $expectedValue = round($item['unit_value'] * $item['quantity'], 2);
                    if (abs($item['total_value'] - $expectedValue) > 0.5) {
                        $errors[] = "Item [$index]: Matem??ticas incorrectas. El 'total_value' que enviaste ({$item['total_value']}) no coincide con (unit_value * quantity) (esperado: {$expectedValue}).";
                    }
                    $calcTotal += $item['total_value'] + ($item['total_igv'] ?? 0);
                }"""

# Since old_block might have encoding differences, I'll use regex.
pattern = re.compile(r'// Sugerencia de matem.*?\}', re.DOTALL)
def repl(m):
    return new_block.replace("??", "á")  # Just in case

# Better yet, regex based replace
content = re.sub(
    r'\$expectedTotal = round\(\(\$item\[\'unit_value\'\] \* \$item\[\'quantity\'\]\) \+ \(\$item\[\'total_igv\'\] \?\? 0\), 2\);.*?\$calcTotal \+= \$item\[\'total_value\'\];',
    r'$expectedValue = round($item[\'unit_value\'] * $item[\'quantity\'], 2);\n                    if (abs($item[\'total_value\'] - $expectedValue) > 0.5) {\n                        $errors[] = "Item [$index]: Matemáticas incorrectas. El \'total_value\' que enviaste ({$item[\'total_value\']}) no coincide con (unit_value * quantity) (esperado: {$expectedValue}).";\n                    }\n                    $calcTotal += $item[\'total_value\'] + ($item[\'total_igv\'] ?? 0);',
    content,
    flags=re.DOTALL
)

with open("app/Services/Signia/Engines/QpseEngine.php", "w", encoding="utf-8") as f:
    f.write(content)
