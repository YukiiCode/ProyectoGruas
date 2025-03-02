<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Services\LogService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function __construct()
    {
        // Authentication should be handled in routes or using a middleware
        // For example, in routes/api.php:
        // Route::middleware('auth:api')->group(function () { ... });
    }

    public function index()
    {
        return User::all();
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'usuario' => 'required|string|unique:users,usuario',
            'password' => 'required|string|min:6',
            'rol' => 'required|string|in:admin,operario'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $user = User::create([
            'usuario' => $request->usuario,
            'password' => Hash::make($request->password),
            'rol' => $request->rol
        ]);

        // Log user creation
        LogService::registrarCreacion('usuario', $user->id, $request->except('password'));

        return response()->json($user, 201);
    }

    public function update(Request $request, User $user)
    {
        $validator = Validator::make($request->all(), [
            'usuario' => 'string|unique:users,usuario,' . $user->id,
            'password' => 'string|min:6',
            'rol' => 'string|in:admin,operario'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        if ($request->has('usuario')) {
            $user->usuario = $request->usuario;
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->has('rol')) {
            $user->rol = $request->rol;
        }

        $user->save();

        $datosAnteriores = $user->getOriginal();
        // Log user update
        LogService::registrarActualizacion('usuario', $user->id, $datosAnteriores, $request->except('password'));

        return response()->json($user);
    }

    public function destroy(User $user)
    {
        $datosAnteriores = $user->toArray();
        // Log user deletion
        LogService::registrarCreacion('usuario_eliminado', $user->id, $datosAnteriores);

        $user->delete();
        return response()->json(null, 204);
    }
}