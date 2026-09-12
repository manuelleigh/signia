import re

with open("app/Services/Signia/Engines/NativeSunatEngine.php", "r", encoding="utf-8") as f:
    content = f.read()

old_block = """    public function process(Company $company, array $payload): array
    {
        try {
            $docType = $payload['document']['document_type_id'] ?? '01';"""

new_block = """    public function process(Company $company, array $payload): array
    {
        try {
            // Auto-inyectar los datos de la empresa para evitar errores si el cliente solo envi?? el RUC
            $payload['company'] = array_merge([
                'ruc' => $company->ruc,
                'name' => $company->business_name,
                'trade_name' => $company->business_name,
                'address' => '-',
                'ubigeo' => '150101',
            ], $payload['company'] ?? []);

            $docType = $payload['document']['document_type_id'] ?? '01';"""

content = content.replace(old_block, new_block)

with open("app/Services/Signia/Engines/NativeSunatEngine.php", "w", encoding="utf-8") as f:
    f.write(content)
