import re
with open("app/Http/Controllers/Api/DocumentController.php", "r", encoding="utf-8") as f:
    content = f.read()

pattern = re.compile(r'\$engine = \$this->engineRouter->resolve.*?// Guardar archivos', re.DOTALL)
match = pattern.search(content)
if match:
    print(match.group(0))
else:
    print("NOT FOUND")
