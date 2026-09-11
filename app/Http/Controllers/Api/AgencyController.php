<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AgencyController extends Controller
{
    public function saldo(Request $request)
    {
        $agency = $request->user()->agency;
        if (!$agency) {
            return response()->json(['success' => false, 'message' => 'Agencia no encontrada.'], 401);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'balance_pse'    => $agency->balance_qpse,
                'balance_native' => $agency->balance_native,
            ]
        ]);
    }
}
