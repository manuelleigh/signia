<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $agency = $request->user()->agency;
        $companies = $agency ? $agency->companies()->with('certificate')->latest()->get() : [];

        return Inertia::render('Companies/Index', [
            'companies' => $companies
        ]);
    }

    public function create()
    {
        return Inertia::render('Companies/Create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'ruc' => 'required|string|size:11',
            'business_name' => 'required|string|max:255',
            'environment' => 'required|in:demo,production',
            'engine_type' => 'required|in:qpse,native',
            'sol_user' => 'nullable|string',
            'sol_pass' => 'nullable|string',
        ]);

        $agency = $request->user()->agency;
        if (!$agency) {
            return redirect()->back()->withErrors(['error' => 'No tienes una agencia asignada.']);
        }

        $data = $request->all();

        if ($data['engine_type'] === 'qpse') {
            $qpseToken = config('services.qpse.token');
            if (!$qpseToken) {
                return redirect()->back()->withErrors(['error' => 'Token de proveedor de firma no configurado en el sistema.']);
            }

            // 1. Crear empresa en QPSE
            $response = \Illuminate\Support\Facades\Http::withToken($qpseToken)
                ->post('https://cpanel.qpse.pe/api/empresa/crear', [
                    'ruc' => $data['ruc'],
                    'tipo_de_plan' => '01'
                ]);

            if (!$response->successful()) {
                return redirect()->back()->withErrors(['error' => 'Error al registrar la empresa en el proveedor de firma: ' . $response->body()]);
            }

            $qpseData = $response->json();
            $data['qpse_username'] = $qpseData['username'] ?? null;
            $data['qpse_password'] = $qpseData['password'] ?? null;
            $data['qpse_plan_type'] = '01';

            // Wait, we need external_id. The docs say `POST /api/empresa/crear` doesn't return external_id.
            // But we need external_id for production. We can get it from GET /api/empresas.
            $empresasResponse = \Illuminate\Support\Facades\Http::withToken($qpseToken)
                ->get('https://cpanel.qpse.pe/api/empresas');
            
            if ($empresasResponse->successful()) {
                $empresas = $empresasResponse->json()['data'] ?? [];
                $empresa = collect($empresas)->firstWhere('ruc', $data['ruc']);
                if ($empresa) {
                    $data['qpse_external_id'] = $empresa['external_id'];
                }
            }

            // 2. Pasar a producción si aplica
            if ($data['environment'] === 'production' && isset($data['qpse_external_id'])) {
                $prodResponse = \Illuminate\Support\Facades\Http::withToken($qpseToken)
                    ->post('https://cpanel.qpse.pe/api/empresa/produccion', [
                        'external_id' => $data['qpse_external_id'],
                        'plan_type' => '01'
                    ]);
                if (!$prodResponse->successful()) {
                    return redirect()->back()->withErrors(['error' => 'Error al activar producción en el proveedor de firma: ' . $prodResponse->body()]);
                }
            }
        }

        $agency->companies()->create($data);

        return redirect()->route('companies.index')->with('success', 'RUC registrado exitosamente.');
    }
}
