<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Models\Vehiculo;
use App\Http\Controllers\Controller;

class VehiculoController extends Controller
{

    public function index()
    {
        try {
            // Obtener todos los vehículos
            $vehiculos = Vehiculo::all();

            // Devolver una respuesta JSON
            return response()->json($vehiculos, 200);
        } catch (\Exception $e) {
            // Capturar cualquier excepción y devolver un mensaje de error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            // Validar los datos de entrada
            $request->validate([
                'matricula' => 'required|string|unique:vehiculos',
                'marca' => 'required|string',
                'modelo' => 'required|string',
                'color' => 'nullable|string',
                'tipovehiculo' => 'required|string',
                'estado' => 'required|string',
            ]);

            // Crear un nuevo vehículo
            $vehiculo = Vehiculo::create($request->all());

            // Devolver una respuesta exitosa
            return response()->json(['message' => 'Vehículo creado', 'data' => $vehiculo], 201);
        } catch (\Exception $e) {
            // Capturar cualquier excepción y devolver un mensaje de error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
