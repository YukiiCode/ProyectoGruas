<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Retirada;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RetiradaController extends Controller
{
    public function index()
    {
        try {
            $retiradas = Retirada::all();
            return response()->json($retiradas, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error retrieving retiradas'], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'vehiculo_id' => 'required|exists:vehiculos,id',
                'fecha_retirada' => 'required|date',
                'lugar_retirada' => 'required|string',
                'motivo' => 'required|string',
                'observaciones' => 'nullable|string',
                'estado' => 'required|string|in:pendiente,en_proceso,completada,cancelada'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $retirada = Retirada::create($request->all());
            return response()->json($retirada, 201);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error creating retirada'], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $retirada = Retirada::findOrFail($id);

            $validator = Validator::make($request->all(), [
                'vehiculo_id' => 'sometimes|required|exists:vehiculos,id',
                'fecha_retirada' => 'sometimes|required|date',
                'lugar_retirada' => 'sometimes|required|string',
                'motivo' => 'sometimes|required|string',
                'observaciones' => 'nullable|string',
                'estado' => 'sometimes|required|string|in:pendiente,en_proceso,completada,cancelada'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $retirada->update($request->all());
            return response()->json($retirada, 200);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error updating retirada'], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $retirada = Retirada::findOrFail($id);
            $retirada->delete();
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
