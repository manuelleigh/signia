import re

with open("app/Services/Signia/Engines/QpseEngine.php", "r", encoding="utf-8") as f:
    content = f.read()

# Fix total_taxes -> total_igv
content = content.replace("['total_taxes']", "['total_igv']")
content = content.replace("'total_taxes'", "'total_igv'")
content = content.replace("(Monto total de impuestos de este producto)", "(Monto total del IGV de este producto)")

# Fix item ['total'] -> ['total_value'] and ['unit_price'] logic
content = content.replace("['total']", "['total_value']")
content = content.replace("'total'", "'total_value'")
content = content.replace("(Monto total del producto con IGV incluido)", "(Valor total del producto sin IGV)")
content = content.replace("unit_value * quantity) + impuestos", "unit_value * quantity)")

# But wait! We accidentally replaced $payload['document']['total'] with $payload['document']['total_value']!
# Let's fix document total back to ['total']
content = content.replace("['document']['total_value']", "['document']['total']")
content = content.replace("[total_value]: Falta", "[total]: Falta")
content = content.replace("[total_value]: El total global", "[total]: El total global")

with open("app/Services/Signia/Engines/QpseEngine.php", "w", encoding="utf-8") as f:
    f.write(content)
