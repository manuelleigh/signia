<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $agency = $request->user()->agency;
        if (!$agency) {
            return response()->json(['success' => false, 'message' => 'Agencia no encontrada.'], 401);
        }

        $companies = $agency->companies()
            ->select('id', 'ruc', 'business_name', 'environment', 'engine_type', 'qpse_username', 'sol_user', 'created_at')
            ->get()
            ->map(function($c) {
                return [
                    'id' => $c->id,
                    'ruc' => $c->ruc,
                    'business_name' => $c->business_name,
                    'environment' => $c->environment,
                    'engine_type' => $c->engine_type === 'qpse' ? 'pse' : $c->engine_type,
                    'pse_username' => $c->qpse_username,
                    'sol_user' => $c->sol_user,
                    'created_at' => $c->created_at,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $companies
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'ruc' => 'required|string|size:11',
            'business_name' => 'required|string|max:255',
            'environment' => 'nullable|in:demo,production',
            'engine_type' => 'required|in:pse,native'
        ]);

        $agency = $request->user()->agency;
        if (!$agency) {
            return response()->json(['success' => false, 'message' => 'Agencia no encontrada.'], 401);
        }

        $ruc = $request->ruc;
        $env = $request->environment ?? 'demo';
        $engineType = $request->engine_type;
        // Map public alias to internal engine name
        $internalEngine = $engineType === 'pse' ? 'qpse' : $engineType;

        // Validar si ya existe
        if (Company::where('ruc', $ruc)->where('agency_id', $agency->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'El RUC ya se encuentra registrado en tu cuenta.'], 400);
        }

        $data = [
            'ruc' => $ruc,
            'business_name' => $request->business_name,
            'environment' => $env,
            'engine_type' => $internalEngine,
        ];

                if ($internalEngine === 'qpse') {
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
            }

            // 3. Pasar a produccion si se pidio
            if ($env === 'production' && isset($data['qpse_external_id'])) {
                $resProd = Http::withToken($qpseToken)->post('https://cpanel.qpse.pe/api/empresa/produccion', [
                    'external_id' => $data['qpse_external_id'],
                    'plan_type' => '01'
                ]);
                if (!$resProd->successful()) {
                    return response()->json([
                        'success' => false, 
                        'message' => 'RUC registrado, pero fallÃ³ el pase a ProducciÃ³n.',
                        'details' => $resProd->json()
                    ], 400);
                }
            }
        }

        $company = $agency->companies()->create($data);

        return response()->json([
            'success' => true,
            'message' => 'Empresa registrada satisfactoriamente',
            'data' => [
                'ruc' => $company->ruc,
                'business_name' => $company->business_name,
                'environment' => $company->environment,
                'engine_type' => $company->engine_type === 'qpse' ? 'pse' : $company->engine_type,
                'pse_username' => $company->qpse_username,
                'pse_password' => $company->qpse_password
            ]
        ], 201);
    }

    public function toProduction(Request $request)
    {
        $request->validate([
            'ruc' => 'required|string|size:11'
        ]);

        $agency = $request->user()->agency;
        if (!$agency) {
            return response()->json(['success' => false, 'message' => 'Agencia no encontrada.'], 401);
        }

        $company = Company::where('ruc', $request->ruc)->where('agency_id', $agency->id)->first();
        if (!$company) {
            return response()->json(['success' => false, 'message' => 'RUC no encontrado en tu cuenta.'], 404);
        }

        if ($company->environment === 'production') {
            return response()->json(['success' => false, 'message' => 'La empresa ya se encuentra en producciÃ³n.'], 400);
        }

        if ($company->engine_type === 'qpse') {
            $qpseToken = config('services.qpse.token');
            if (!$company->qpse_external_id) {
                // Recuperar external_id si no lo tiene
                $resLista = Http::withToken($qpseToken)->get('https://cpanel.qpse.pe/api/empresas');
                if ($resLista->successful()) {
                    $empresas = $resLista->json()['data'] ?? [];
                    $empresaData = collect($empresas)->firstWhere('ruc', $company->ruc);
                    if ($empresaData) {
                        $company->qpse_external_id = $empresaData['external_id'];
                        $company->save();
                    }
                }
            }

            if (!$company->qpse_external_id) {
                return response()->json(['success' => false, 'message' => 'No se pudo recuperar el ID externo del Motor PSE.'], 500);
            }

            $resProd = Http::withToken($qpseToken)->post('https://cpanel.qpse.pe/api/empresa/produccion', [
                'external_id' => $company->qpse_external_id,
                'plan_type' => '01'
            ]);

            if (!$resProd->successful()) {
                return response()->json([
                    'success' => false, 
                    'message' => 'Error al pasar a producciÃ³n en el motor PSE.',
                    'details' => $resProd->json()
                ], 400);
            }
        }

        $company->environment = 'production';
        $company->save();

        return response()->json([
            'success' => true,
            'message' => 'Empresa actualizada a producciÃ³n exitosamente.',
            'data' => [
                'ruc' => $company->ruc,
                'environment' => $company->environment
            ]
        ]);
    }

    public function uploadCertificate(Request $request)
    {
        $request->validate([
            'ruc' => 'required|string|size:11',
            'certificate' => 'required|file|mimes:p12,pem',
            'password' => 'required|string'
        ]);

        $agency = $request->user()->agency;
        $company = Company::where('ruc', $request->ruc)->where('agency_id', $agency->id)->first();
        
        if (!$company) {
            return response()->json(['success' => false, 'message' => 'RUC no encontrado en tu cuenta.'], 404);
        }

        $file = $request->file('certificate');
        $path = $file->storeAs("certificates/{$company->ruc}", $file->getClientOriginalName(), 'local');

        $certificate = $company->certificate()->updateOrCreate(
            ['company_id' => $company->id],
            [
                'path' => $path,
                'password' => encrypt($request->password), // Guardar contraseña encriptada
                'status' => 'active'
            ]
        );

        return response()->json([
            'success' => true,
            'message' => 'Certificado subido y configurado correctamente.',
            'data' => [
                'ruc' => $company->ruc,
                'certificate_path' => $path
            ]
        ]);
    }

    public function destroy(Request $request, $ruc)
    {
        $agency = $request->user()->agency;
        $company = Company::where('ruc', $ruc)->where('agency_id', $agency->id)->first();
        
        if (!$company) {
            return response()->json(['success' => false, 'message' => 'RUC no encontrado en tu cuenta.'], 404);
        }

        // Aquí podrías agregar lógica para inactivar en QPSE si fuera necesario
        
        $company->delete(); // Soft delete o Hard delete según migración

        return response()->json([
            'success' => true,
            'message' => 'Empresa eliminada/suspendida correctamente.'
        ]);
    }
}
