<?php

namespace App\Services;

use App\Models\Log;
use Illuminate\Support\Facades\Auth;

class LogService
{
    /**
     * Registra una acción en el sistema de logs
     *
     * @param string $accion Descripción de la acción realizada
     * @param string|array|null $detalles Detalles adicionales de la acción
     * @param int|null $usuarioId ID del usuario que realiza la acción (opcional)
     * @return \App\Models\Log
     */
    public static function registrar($accion, $detalles = null, $usuarioId = null)
    {
        // Si no se proporciona un ID de usuario, intentar obtenerlo del usuario autenticado
        if ($usuarioId === null && Auth::check()) {
            $usuarioId = Auth::id();
        }
        
        // Si los detalles son un array, convertirlos a JSON
        if (is_array($detalles)) {
            $detalles = json_encode($detalles, JSON_UNESCAPED_UNICODE);
        }
        
        return Log::registrarAccion($usuarioId, $accion, $detalles);
    }
    
    /**
     * Registra una acción de inicio de sesión
     *
     * @param int $usuarioId ID del usuario
     * @param string|null $detalles Detalles adicionales
     * @return \App\Models\Log
     */
    public static function registrarLogin($usuarioId, $detalles = null)
    {
        return self::registrar('login', $detalles, $usuarioId);
    }
    
    /**
     * Registra una acción de cierre de sesión
     *
     * @param string|null $detalles Detalles adicionales
     * @return \App\Models\Log
     */
    public static function registrarLogout($detalles = null)
    {
        return self::registrar('logout', $detalles);
    }
    
    /**
     * Registra una acción de creación
     *
     * @param string $entidad Nombre de la entidad creada
     * @param int $entidadId ID de la entidad creada
     * @param array|null $datos Datos de la entidad
     * @return \App\Models\Log
     */
    public static function registrarCreacion($entidad, $entidadId, $datos = null)
    {
        $detalles = [
            'entidad_id' => $entidadId,
            'datos' => $datos
        ];
        
        return self::registrar("crear_{$entidad}", $detalles);
    }
    
    /**
     * Registra una acción de actualización
     *
     * @param string $entidad Nombre de la entidad actualizada
     * @param int $entidadId ID de la entidad actualizada
     * @param array|null $datosAnteriores Datos anteriores de la entidad
     * @param array|null $datosNuevos Datos nuevos de la entidad
     * @return \App\Models\Log
     */
    public static function registrarActualizacion($entidad, $entidadId, $datosAnteriores = null, $datosNuevos = null)
    {
        $detalles = [
            'entidad_id' => $entidadId,
            'datos_anteriores' => $datosAnteriores,
            'datos_nuevos' => $datosNuevos
        ];
        
        return self::registrar("actualizar_{$entidad}", $detalles);
    }
    
    /**
     * Registra una acción de eliminación
     *
     * @param string $entidad Nombre de la entidad eliminada
     * @param int $entidadId ID de la entidad eliminada
     * @param array|null $datos Datos de la entidad eliminada
     * @return \App\Models\Log
     */
    public static function registrarEliminacion($entidad, $entidadId, $datos = null)
    {
        $detalles = [
            'entidad_id' => $entidadId,
            'datos' => $datos
        ];
        
        return self::registrar("eliminar_{$entidad}", $detalles);
    }
}