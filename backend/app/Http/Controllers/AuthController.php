<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use App\Services\LogService;

class AuthController extends Controller
{
    public function login(Request $request)
    {
        try {
            // Validar los datos de entrada
            $request->validate([
                'usuario' => 'required|string',
                'password' => 'required|string',
            ]);

            // Buscar al usuario por su nombre de usuario
            $user = User::where('usuario', $request->usuario)->first();

            // Verificar si el usuario existe y la contraseña es correcta
            if (!$user || !Hash::check($request->password, $user->password)) {
                // Registrar intento de inicio de sesión fallido
                LogService::registrar('login_fallido', ['usuario' => $request->usuario, 'ip' => $request->ip()]);
                return response()->json(['error' => 'Credenciales incorrectas'], 401);
            }

            // Generar un token para el usuario
            $token = $user->createToken('auth_token')->plainTextToken;

            // Registrar inicio de sesión exitoso
            LogService::registrarLogin($user->id, ['ip' => $request->ip()]);

            // Devolver una respuesta exitosa
            return response()->json([
                'message' => 'Inicio de sesión exitoso',
                'user' => $user,
                'token' => $token,
            ], 200);
        } catch (\Exception $e) {
            // Capturar cualquier excepción y devolver un mensaje de error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function logout(Request $request)
    {
        // Registrar cierre de sesión
        LogService::registrarLogout(['ip' => $request->ip()]);
        
        // Revocar el token actual del usuario
        $request->user()->currentAccessToken()->delete();
        
        return response()->json(['message' => 'Sesión cerrada correctamente'], 200);
    }
}
