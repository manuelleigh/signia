import glob, re
from collections import defaultdict

data = defaultdict(set)
for f in glob.glob('resources/views/ubl21/*.blade.php'):
    with open(f, 'r', encoding='utf-8') as file:
        content = file.read()
        matches = re.findall(r'\$([a-zA-Z0-9_]+)(?:->|\[[\'"])([a-zA-Z0-9_]+)', content)
        for m in matches:
            data[f].add(f"${m[0]}['{m[1]}']")

for f, vars in data.items():
    print(f"--- {f} ---")
    for v in sorted(vars):
        print(v)
