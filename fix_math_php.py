import re

with open("app/Services/Signia/Engines/QpseEngine.php", "r", encoding="utf-8") as f:
    content = f.read()

# I will use a simple, robust regex to replace the math block.
pattern = re.compile(r'// Sugerencia de matem.*?\}', re.DOTALL)

new_block = """// Sugerencia de matem??ticas
                if (isset($item['unit_value']) && isset($item['quantity']) && isset($item['total_value'])) {
                    $expectedValue = round($item['unit_value'] * $item['quantity'], 2);
                    if (abs($item['total_value'] - $expectedValue) > 0.5) {
                        $errors[] = "Item [$index]: Matem??ticas incorrectas. El 'total_value' que enviaste ({$item['total_value']}) no coincide con (unit_value * quantity) (esperado: {$expectedValue}).";
                    }
                    $calcTotal += $item['total_value'] + ($item['total_igv'] ?? 0);
                }"""

# Fix any weird backslash escapes I might have introduced
content = re.sub(r'round\(\$item\[\\\'unit_value\\\'\].*?\$calcTotal \+= \$item\[\\\'total_value\\\'\] \+ \(\$item\[\\\'total_igv\\\'\] \?\? 0\);', new_block, content, flags=re.DOTALL)

# Let's just do it broadly:
content = re.sub(r'// Sugerencia de matem.*?\}', new_block, content, flags=re.DOTALL)

with open("app/Services/Signia/Engines/QpseEngine.php", "w", encoding="utf-8") as f:
    f.write(content)
