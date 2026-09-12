import re

with open("app/Http/Controllers/Api/DocumentController.php", "r", encoding="utf-8") as f:
    content = f.read()

pattern = re.compile(r'catch \(\\Exception \$engineEx\) \{.*?(?=public function )', re.DOTALL)
match = pattern.search(content)
if match:
    print(match.group(0))
else:
    print("NOT FOUND")
