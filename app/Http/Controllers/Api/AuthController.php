<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function token(Request $request)
    {
        $request->validate(
            [
                'email' => 'required|email',
                'password' => 'required|string',
                'device_name' => 'required|string|max:80',
            ],
            [
                'email.required' => 'El correo electrónico es obligatorio.',
                'email.email' => 'El correo electrónico debe tener un formato válido.',
                'password.required' => 'La contraseña es obligatoria.',
                'device_name.required' => 'El nombre del dispositivo es obligatorio.',
                'device_name.max' => 'El nombre del dispositivo no debe superar 80 caracteres.',
            ],
        );

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'email' => ['Las credenciales proporcionadas son incorrectas.'],
            ])->status(401);
        }

        $expiresAt = now()->addMinutes(10);
        $token = $user->createToken($request->device_name, ['*'], $expiresAt);

        return response()->json([
            'access_token' => $token->plainTextToken,
            'token_type' => 'Bearer',
            'expires_in' => 600,
            'expires_at' => $expiresAt->toIso8601String(),
        ]);
    }
}
