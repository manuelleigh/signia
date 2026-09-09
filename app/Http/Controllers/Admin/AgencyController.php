<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agency;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AgencyController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()->is_admin) {
            abort(403, 'Acceso Denegado');
        }

        $agencies = Agency::with('user')->withCount('companies')->latest()->get();

        return Inertia::render('Admin/Agencies', [
            'agencies' => $agencies
        ]);
    }

    public function addBalance(Request $request, Agency $agency)
    {
        if (!$request->user()->is_admin) {
            abort(403, 'Acceso Denegado');
        }

        $request->validate([
            'amount' => 'required|integer|min:1',
            'engine_type' => 'required|in:qpse,native'
        ]);

        $balanceField = $request->engine_type === 'qpse' ? 'balance_qpse' : 'balance_native';
        $agency->increment($balanceField, $request->amount);

        return back()->with('success', 'Saldo recargado exitosamente.');
    }
}
