<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Retirada;

class RetiradaController extends Controller
{
    public function index()
    {
        try {
            // Obtener todos los vehículos
            $retiradas = Retirada::all();

            // Devolver una respuesta JSON
            return response()->json($retiradas, 200);
        } catch (\Exception $e) {
            // Capturar cualquier excepción y devolver un mensaje de error
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
