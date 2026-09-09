<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function token(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json(['message' => 'Credenciales incorrectas'], 401);
        }

        // Crear token temporal (expira en 10 minutos = 600 segundos)
        $token = $user->createToken('temporal-api-token', ['*'], now()->addMinutes(10));

        return response()->json([
            'access_token' => $token->plainTextToken,
            'expires_in' => 600
        ]);
    }
}
