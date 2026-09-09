<?php

namespace App\Http\Controllers;

use App\Models\Company;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class CertificateController extends Controller
{
    public function store(Request $request, Company $company)
    {
        $request->validate([
            'certificate' => 'required|file|mimes:pfx,p12',
            'password' => 'required|string',
            'sol_user' => 'required|string',
            'sol_password' => 'required|string',
        ]);

        if ($company->agency_id !== $request->user()->agency->id) {
            abort(403);
        }

        $pfxContent = file_get_contents($request->file('certificate')->getRealPath());
        
        $certs = [];
        if (!openssl_pkcs12_read($pfxContent, $certs, $request->password)) {
            return back()->withErrors(['certificate' => 'La contraseña del certificado es incorrecta o el archivo es inválido.']);
        }

        $pemContent = $certs['cert'] . "\n" . $certs['pkey'];
        $path = $request->file('certificate')->store('certificates', 'local');

        $company->certificate()->updateOrCreate(
            ['company_id' => $company->id],
            [
                'file_path' => $path,
                'file_content' => $pemContent,
                'password' => encrypt($request->password),
                'sol_user' => $request->sol_user,
                'sol_password' => encrypt($request->sol_password),
            ]
        );

        return back()->with('success', 'Certificado configurado exitosamente.');
    }
}
