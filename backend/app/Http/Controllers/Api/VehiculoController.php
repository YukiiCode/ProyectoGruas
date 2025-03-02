<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Vehiculo;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Services\LogService;

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
                'fechaentrada' => 'nullable|date',
                'fechasalida' => 'nullable|date',
                'lugar' => 'nullable|string|max:255',
                'direccion' => 'nullable|string|max:255',
                'agente' => 'nullable|string|max:255',
                'matricula' => 'required|string|max:20',
                'marca' => 'required|string|max:100',
                'modelo' => 'required|string|max:100',
                'color' => 'nullable|string|max:50',
                'tipovehiculo' => 'required|string|max:255',
                'estado' => 'required|string|in:En depósito,Retirado|max:50',
                'motivo' => 'nullable|string',
                'grua' => 'nullable|string|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $vehiculo = Vehiculo::create($request->all());
            
            // Log vehicle creation
            LogService::registrarCreacion('vehiculo', $vehiculo->id, $request->all());
            
            return response()->json($vehiculo, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating vehicle: ' . $e->getMessage()], 500);
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

            // Validate all possible fields
            $validator = Validator::make($request->all(), [
                'fechaentrada' => 'nullable|date',
                'fechasalida' => 'nullable|date',
                'lugar' => 'nullable|string|max:255',
                'direccion' => 'nullable|string|max:255',
                'agente' => 'nullable|string|max:255',
                'matricula' => 'required|string|max:20',
                'marca' => 'required|string|max:100',
                'modelo' => 'required|string|max:100',
                'color' => 'nullable|string|max:50',
                'tipovehiculo' => 'required|string|max:255',
                'estado' => 'required|string|in:En depósito,Retirado|max:50',
                'motivo' => 'nullable|string',
                'grua' => 'nullable|string|max:50'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            // Get only the fillable fields from the request
            $fillableData = $request->only([
                'fechaentrada',
                'fechasalida',
                'lugar',
                'direccion',
                'agente',
                'matricula',
                'marca',
                'modelo',
                'color',
                'tipovehiculo',
                'estado',
                'motivo',
                'grua'
            ]);
            
            // Handle null values for nullable fields
            foreach ($fillableData as $key => $value) {
                if ($value === '') {
                    $fillableData[$key] = null;
                }
            }

            $datosAnteriores = $vehiculo->toArray();
            $vehiculo->update($fillableData);
            
            // Log vehicle update
            LogService::registrarActualizacion('vehiculo', $vehiculo->id, $datosAnteriores, $fillableData);
            
            return response()->json($vehiculo, 200);
        } catch (\Exception $e) {
            // Log the actual error for debugging
            return response()->json(['error' => 'Error updating vehicle: ' . $e->getMessage()], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $vehiculo = Vehiculo::findOrFail($id);
            $datosEliminados = $vehiculo->toArray();
            $vehiculo->delete();
            
            // Log vehicle deletion
            LogService::registrarEliminacion('vehiculo', $id, $datosEliminados);
            
            return response()->json(['message' => 'Vehicle deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting vehicle'], 500);
        }
    }
}
