import re

with open("app/Services/Signia/Engines/QpseEngine.php", "r", encoding="utf-8") as f:
    content = f.read()

old_str = """                // Sugerencia de matemáticas
                if (isset($item['unit_value']) && isset($item['quantity']) && isset($item['total_value'])) {
                    $expectedTotal = round(($item['unit_value'] * $item['quantity']) + ($item['total_igv'] ?? 0), 2);
                    if (abs($item['total_value'] - $expectedTotal) > 0.5) {
                        $errors[] = "Item [$index]: Matemáticas incorrectas. El 'total_value' que enviaste ({$item['total_value']}) no coincide con (unit_value * quantity) (esperado: {$expectedTotal}).";
                    }
                    $calcTotal += $item['total_value'];
                }"""

# Corrected variables: 
new_str = """                // Sugerencia de matemáticas
                if (isset($item['unit_value']) && isset($item['quantity']) && isset($item['total_value'])) {
                    $expectedValue = round($item['unit_value'] * $item['quantity'], 2);
                    if (abs($item['total_value'] - $expectedValue) > 0.5) {
                        $errors[] = "Item [$index]: Matemáticas incorrectas. El 'total_value' que enviaste ({$item['total_value']}) no coincide con (unit_value * quantity) (esperado: {$expectedValue}).";
                    }
                    $calcTotal += $item['total_value'] + ($item['total_igv'] ?? 0);
                }"""

# Fixing encoding from older file if necessary
old_str_enc = old_str.replace("matemáticas", "matem??ticas").replace("Matemáticas", "Matem??ticas")
new_str_enc = new_str.replace("matemáticas", "matem??ticas").replace("Matemáticas", "Matem??ticas")

if old_str in content:
    content = content.replace(old_str, new_str)
elif old_str_enc in content:
    content = content.replace(old_str_enc, new_str_enc)
else:
    # Manual surgical regex to avoid greedy issues
    content = re.sub(r'\$expectedTotal = round\(\(\$item\[\'unit_value\'\] \* \$item\[\'quantity\'\]\) \+ \(\$item\[\'total_igv\'\] \?\? 0\), 2\);', r'$expectedValue = round($item[\'unit_value\'] * $item[\'quantity\'], 2);', content)
    content = re.sub(r'\(esperado: \{\$expectedTotal\}\)', r'(esperado: {$expectedValue})', content)
    content = re.sub(r'abs\(\$item\[\'total_value\'\] - \$expectedTotal\)', r'abs($item[\'total_value\'] - $expectedValue)', content)
    content = re.sub(r'\$calcTotal \+= \$item\[\'total_value\'\];', r'$calcTotal += $item[\'total_value\'] + ($item[\'total_igv\'] ?? 0);', content)

with open("app/Services/Signia/Engines/QpseEngine.php", "w", encoding="utf-8") as f:
    f.write(content)
