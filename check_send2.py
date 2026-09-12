import re

with open("app/Http/Controllers/Api/DocumentController.php", "r", encoding="utf-8") as f:
    content = f.read()

pattern = re.compile(r'(public function send\(Request \$request\).*?)(?=public function )', re.DOTALL)
match = pattern.search(content)
if match:
    print(match.group(1))
