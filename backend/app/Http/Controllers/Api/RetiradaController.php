<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Retirada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Services\LogService;

class RetiradaController extends Controller
{
    public function index()
    {
        try {
            $retiradas = Retirada::with(['vehiculo'])->get();
            return response()->json($retiradas);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al cargar retiradas'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'vehiculo_id' => 'required|exists:vehiculos,id',
                'nombre' => 'required|string|min:3',
                'nif' => 'required|string',
                'domicilio' => 'required|string|min:5',
                'poblacion' => 'required|string|min:2',
                'provincia' => 'required|string|min:2',
                'permiso' => 'required|string',
                'fecha' => 'required|date',
                'agente' => 'required|string',
                'importeretirada' => 'required|numeric',
                'importedeposito' => 'required|numeric',
                'total' => 'required|numeric',
                'opcionespago' => 'required|string',
                'observaciones' => 'nullable|string'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $retirada = Retirada::create($request->all());
            
            // Log retirada creation
            LogService::registrarCreacion('retirada', $retirada->id, $request->all());
            
            return response()->json($retirada, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating retirada: ' . $e->getMessage()], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $retirada = Retirada::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'vehiculo_id' => 'sometimes|required|exists:vehiculos,id',
                'fecha' => 'sometimes|required|date'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $datosAnteriores = $retirada->toArray();
            $retirada->update($request->all());
            
            // Log retirada update
            LogService::registrarActualizacion('retirada', $retirada->id, $datosAnteriores, $request->all());
            
            return response()->json($retirada, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating retirada'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $retirada = Retirada::findOrFail($id);
            $datosEliminados = $retirada->toArray();
            $retirada->delete();
            
            // Log retirada deletion
            LogService::registrarEliminacion('retirada', $id, $datosEliminados);
            
            return response()->json(['message' => 'Retirada deleted successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error deleting retirada'], 500);
        }
    }

    public function generatePDF($id)
    {
        try {
            $retirada = Retirada::with(['vehiculo'])->findOrFail($id);
            // PDF generation logic here
            return response()->json(['message' => 'PDF generated successfully'], 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error generating PDF'], 500);
        }
    }
}
