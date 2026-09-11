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
            ->get();

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
            'engine_type' => 'required|in:qpse,native'
        ]);

        $agency = $request->user()->agency;
        if (!$agency) {
            return response()->json(['success' => false, 'message' => 'Agencia no encontrada.'], 401);
        }

        $ruc = $request->ruc;
        $env = $request->environment ?? 'demo';
        $engineType = $request->engine_type;

        // Validar si ya existe
        if (Company::where('ruc', $ruc)->where('agency_id', $agency->id)->exists()) {
            return response()->json(['success' => false, 'message' => 'El RUC ya se encuentra registrado en tu cuenta.'], 400);
        }

        $data = [
            'ruc' => $ruc,
            'business_name' => $request->business_name,
            'environment' => $env,
            'engine_type' => $engineType,
        ];

        if ($engineType === 'qpse') {
            $qpseToken = config('services.qpse.token');
            if (!$qpseToken) {
                return response()->json(['success' => false, 'message' => 'Error de configuraciÃ³n del sistema (Token faltante).'], 500);
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
                'engine_type' => $company->engine_type,
                'username' => $company->qpse_username,
                'password' => $company->qpse_password
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
