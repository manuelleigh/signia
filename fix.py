with open('app/Services/Signia/Engines/QpseEngine.php', 'r', encoding='utf-8') as f:
    c = f.read()

c = c.replace(
    ' = round(([\'unit_value\'] * [\'quantity\']) + ([\'total_igv\'] ?? 0), 2);',
    ' = round([\'unit_value\'] * [\'quantity\'], 2);'
)
c = c.replace('abs([\'total_value\'] - )', 'abs([\'total_value\'] - )')
c = c.replace('(esperado: {})', '(esperado: {})')
c = c.replace(' += [\'total_value\'];', ' += [\'total_value\'] + ([\'total_igv\'] ?? 0);')

with open('app/Services/Signia/Engines/QpseEngine.php', 'w', encoding='utf-8') as f:
    f.write(c)
