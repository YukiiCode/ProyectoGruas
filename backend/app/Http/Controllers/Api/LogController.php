<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Log;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    /**
     * Display a listing of the logs.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index()
    {
        try {
            // Join logs with usuarios to get user names
            $logs = DB::table('logs')
                ->leftJoin('usuarios', 'logs.usuario_id', '=', 'usuarios.id')
                ->select(
                    'logs.id',
                    'logs.usuario_id',
                    'logs.accion',
                    'logs.detalles',
                    'logs.fecha_hora as created_at',
                    'usuarios.nombre as user_name',
                    DB::raw("CASE 
                        WHEN logs.accion LIKE '%error%' THEN 'ERROR'
                        WHEN logs.accion LIKE '%warning%' OR logs.accion LIKE '%advertencia%' THEN 'WARNING'
                        ELSE 'INFO'
                    END as type")
                )
                ->orderBy('logs.fecha_hora', 'desc')
                ->get();

            return response()->json($logs);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Error al cargar los logs',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}