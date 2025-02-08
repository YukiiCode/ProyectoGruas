<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    /**
     * Registrar un nuevo usuario.
     */
    public function register(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'nombre' => 'required|string|max:100',
            'usuario' => 'required|string|unique:users|max:50',
            'password' => 'required|string|min:6',
            'rol' => 'nullable|in:admin,operador',
        ]);

        // Crear el usuario en la base de datos
        $user = User::create([
            'nombre' => $request->nombre,
            'usuario' => $request->usuario,
            'password' => Hash::make($request->password),
            'rol' => $request->rol ?? 'operador', // Por defecto, rol "operador"
        ]);

        // Generar un token para el usuario recién registrado
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Usuario registrado correctamente',
            'user' => $user,
            'token' => $token,
        ], 201);
    }

    /**
     * Iniciar sesión y generar un token.
     */
    public function login(Request $request)
    {
        // Validar los datos de entrada
        $request->validate([
            'usuario' => 'required|string|max:50',
            'password' => 'required|string',
        ]);

        // Buscar al usuario por su nombre de usuario
        $user = User::where('usuario', $request->usuario)->first();

        // Verificar si el usuario existe y la contraseña es correcta
        if (!$user || !Hash::check($request->password, $user->password)) {
            throw ValidationException::withMessages([
                'usuario' => ['Las credenciales proporcionadas son incorrectas.'],
            ]);
        }

        // Generar un token para el usuario
        $token = $user->createToken('auth_token')->plainTextToken;

        return response()->json([
            'message' => 'Inicio de sesión exitoso',
            'user' => $user,
            'token' => $token,
        ], 200);
    }

    /**
     * Cerrar sesión (revocar el token actual).
     */
    public function logout(Request $request)
    {
        // Revocar el token actual del usuario
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Cierre de sesión exitoso',
        ], 200);
    }
}