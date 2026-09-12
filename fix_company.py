import re

with open("app/Http/Controllers/Api/CompanyController.php", "r", encoding="utf-8") as f:
    content = f.read()

old_block = """        if ($internalEngine === 'qpse') {
            $qpseToken = config('services.qpse.token');
            if (!$qpseToken) {
                return response()->json(['success' => false, 'message' => 'Error de configuraci????n del sistema (Token faltante).'], 500);
            }

            // 1. Crear
            $resCrear = Http::withToken($qpseToken)->post('https://cpanel.qpse.pe/api/empresa/crear', [
                'ruc' => $ruc,
                'tipo_de_plan' => '01'
            ]);

            if (!$resCrear->successful()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Error al registrar en el motor PSE.', 
                    'details' => $resCrear->json()
                ], 400);
            }

            $qpseData = $resCrear->json();
            $data['qpse_username'] = $qpseData['username'] ?? null;
            $data['qpse_password'] = $qpseData['password'] ?? null;
            $data['qpse_plan_type'] = '01';

            // 2. Obtener external_id
            $resLista = Http::withToken($qpseToken)->get('https://cpanel.qpse.pe/api/empresas');
            if ($resLista->successful()) {
                $empresas = $resLista->json()['data'] ?? [];
                $empresa = collect($empresas)->firstWhere('ruc', $ruc);
                if ($empresa) {
                    $data['qpse_external_id'] = $empresa['external_id'];
                }
            }"""

# Correct encoding issues caused by terminal output
old_block = old_block.replace("configuraci????n", "configuración")

new_block = """        if ($internalEngine === 'qpse') {
            $qpseToken = config('services.qpse.token');
            if (!$qpseToken) {
                return response()->json(['success' => false, 'message' => 'Error de configuración del sistema (Token faltante).'], 500);
            }

            // 1. Recuperar lista del proveedor PSE PRIMERO para evitar conflictos de "ya existe"
            $resLista = Http::withToken($qpseToken)->get('https://cpanel.qpse.pe/api/empresas');
            $empresas = $resLista->successful() ? ($resLista->json()['data'] ?? []) : [];
            $empresaExistente = collect($empresas)->firstWhere('ruc', $ruc);

            if ($empresaExistente) {
                // La empresa ya estaba en el PSE. Solo la recuperamos y la enlazamos localmente.
                $data['qpse_username'] = $empresaExistente['username'] ?? ($empresaExistente['usuario'] ?? null);
                // NOTA: Algunas APIs devuelven password, clave o contrasena. Si la API de listado no lo devuelve, 
                // se guardar?? null temporalmente, pero permitir?? crearla localmente para que puedan emitir (si el PSE autentica con token global o si envian las credenciales manualmente).
                $data['qpse_password'] = $empresaExistente['password'] ?? ($empresaExistente['clave'] ?? null);
                $data['qpse_external_id'] = $empresaExistente['external_id'] ?? null;
                $data['qpse_plan_type'] = '01';
            } else {
                // 2. No existe en el PSE, procedemos a crearla normalmente
                $resCrear = Http::withToken($qpseToken)->post('https://cpanel.qpse.pe/api/empresa/crear', [
                    'ruc' => $ruc,
                    'tipo_de_plan' => '01'
                ]);

                if (!$resCrear->successful()) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'Error al registrar en el motor PSE.', 
                        'details' => $resCrear->json()
                    ], 400);
                }

                $qpseData = $resCrear->json();
                $data['qpse_username'] = $qpseData['username'] ?? null;
                $data['qpse_password'] = $qpseData['password'] ?? null;
                $data['qpse_external_id'] = $qpseData['external_id'] ?? null;
                $data['qpse_plan_type'] = '01';

                // Si crear no devolvió external_id, volvemos a listar solo para sacarlo
                if (empty($data['qpse_external_id'])) {
                    $resLista2 = Http::withToken($qpseToken)->get('https://cpanel.qpse.pe/api/empresas');
                    if ($resLista2->successful()) {
                        $empresas2 = $resLista2->json()['data'] ?? [];
                        $empresaNueva = collect($empresas2)->firstWhere('ruc', $ruc);
                        if ($empresaNueva) {
                            $data['qpse_external_id'] = $empresaNueva['external_id'] ?? null;
                        }
                    }
                }
            }"""

if old_block in content:
    content = content.replace(old_block, new_block)
    with open("app/Http/Controllers/Api/CompanyController.php", "w", encoding="utf-8") as f:
        f.write(content)
    print("REPLACED SUCCESSFULLY")
else:
    print("COULD NOT FIND BLOCK. TRYING REGEX FALLBACK.")
    # Fallback if there are minor formatting differences
    import re
    pattern = re.compile(r"if \(\$internalEngine === 'qpse'\) \{.*?// 3\. Pasar a produccion si se pidio", re.DOTALL)
    content = pattern.sub(new_block + "\n\n            // 3. Pasar a produccion si se pidio", content)
    with open("app/Http/Controllers/Api/CompanyController.php", "w", encoding="utf-8") as f:
        f.write(content)
    print("REPLACED VIA REGEX")

