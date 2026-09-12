import re

with open("app/Services/Signia/Engines/NativeSunatEngine.php", "r", encoding="utf-8") as f:
    content = f.read()

content = content.replace("'ruc' => $company->ruc,", "'ruc' => $company->ruc,\n                'number' => $company->ruc,")

with open("app/Services/Signia/Engines/NativeSunatEngine.php", "w", encoding="utf-8") as f:
    f.write(content)
