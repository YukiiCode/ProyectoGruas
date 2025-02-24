<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Vehiculo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;

class VehiculoController extends Controller
{
    public function index()
    {
        try {
            $vehiculos = Vehiculo::all();
            return response()->json($vehiculos, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error retrieving vehicles'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'matricula' => 'required|string|unique:vehiculos',
                'marca' => 'required|string',
                'modelo' => 'required|string',
                'color' => 'nullable|string',
                'tipovehiculo' => 'required|string',
                'estado' => 'required|string|in:disponible,retirado,en_proceso'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $vehiculo = Vehiculo::create($request->all());
            return response()->json($vehiculo, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating vehicle'], 500);
        }
    }

    public function show($id)
    {
        try {
            $vehiculo = Vehiculo::findOrFail($id);
            return response()->json($vehiculo, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Vehicle not found'], 404);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $vehiculo = Vehiculo::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'matricula' => 'sometimes|required|string|unique:vehiculos,matricula,' . $id,
                'marca' => 'sometimes|required|string',
                'modelo' => 'sometimes|required|string',
                'color' => 'nullable|string',
                'tipovehiculo' => 'sometimes|required|string',
                'estado' => 'sometimes|required|string|in:disponible,retirado,en_proceso'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $vehiculo->update($request->all());
            return response()->json($vehiculo, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating vehicle'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $vehiculo = Vehiculo::findOrFail($id);
            $vehiculo->delete();
            return response()->json(['message' => 'Vehicle deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting vehicle'], 500);
        }
    }
}
