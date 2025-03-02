<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Services\LogService;

class UserController extends Controller
{
    public function index(Request $request)
    {
        try {
            // Check if user has admin role
            if ($request->user()->rol !== 'admin') {
                return response()->json(['error' => 'Unauthorized. Admin role required.'], 403);
            }
            
            $users = User::all();
            return response()->json($users, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error retrieving users: ' . $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'nombre' => 'required|string|max:255',
                'usuario' => 'required|string|max:50|unique:usuarios,usuario',
                'password' => 'required|string|min:8',
                'rol' => 'required|in:admin,operador',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $user = User::create([
                'nombre' => $request->nombre,
                'usuario' => $request->usuario,
                'password' => Hash::make($request->password),
                'rol' => $request->rol,
            ]);

            // Log user creation
            LogService::registrarCreacion('usuario', $user->id, $request->except('password'));

            return response()->json($user, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating user: ' . $e->getMessage()], 500);
        }
    }

    public function show(Request $request, $id)
    {
        try {
            // Check if user has admin role
            if ($request->user()->rol !== 'admin') {
                return response()->json(['error' => 'Unauthorized. Admin role required.'], 403);
            }
            
            $user = User::findOrFail($id);
            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'User not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $user = User::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'nombre' => 'sometimes|required|string|max:255',
                'usuario' => [
                    'sometimes',
                    'required',
                    'string',
                    'max:50',
                    Rule::unique('usuarios', 'usuario')->ignore($user->id),
                ],
                'password' => 'sometimes|required|string|min:8',
                'rol' => 'sometimes|required|in:admin,operador',
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            if ($request->has('nombre')) {
                $user->nombre = $request->nombre;
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

            $datosAnteriores = $user->toArray();
            $user->save();

            // Log user update
            LogService::registrarActualizacion('usuario', $user->id, $datosAnteriores, $request->except('password'));

            return response()->json($user, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating user: ' . $e->getMessage()], 500);
        }
    }

    public function destroy(Request $request, $id)
    {
        try {
            // Check if user has admin role
            if ($request->user()->rol !== 'admin') {
                return response()->json(['error' => 'Unauthorized. Admin role required.'], 403);
            }
            
            $user = User::findOrFail($id);
            $datosEliminados = $user->toArray();
            $user->delete();

            // Log user deletion
            LogService::registrarEliminacion('usuario', $id, $datosEliminados);

            return response()->json(['message' => 'User deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting user: ' . $e->getMessage()], 500);
        }
    }
}