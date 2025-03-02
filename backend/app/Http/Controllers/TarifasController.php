<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TarifasController extends Controller
{
    /**
     * Get all tariffs from the database
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            $tarifas = DB::table('tarifas')->get();
            return response()->json($tarifas);
        } catch (\Exception $e) {
            return response()->json(['error' => 'Error al obtener las tarifas: ' . $e->getMessage()], 500);
        }
    }
}