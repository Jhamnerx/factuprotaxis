<?php

namespace App\Models\Tenant;

use App\Models\Tenant\ModelTenant;

class PlantillaMensaje extends ModelTenant
{
    protected $table = 'plantilla_mensajes';
    protected $fillable = [
        'tipo',
        'asunto',
        'contenido',
        'estado',
        'descripcion'
    ];

    protected $casts = [
        'estado' => 'boolean'
    ];

    /**
     * Scopes
     */
    public function scopeActiva($query)
    {
        return $query->where('estado', true);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('tipo', $tipo);
    }

    /**
     * Obtener plantilla por tipo
     *
     * @param string $tipo
     * @return PlantillaMensaje|null
     */
    public static function obtenerPorTipo($tipo)
    {
        return static::activa()->porTipo($tipo)->first();
    }

    /**
     * Reemplazar variables en el contenido de la plantilla
     *
     * @param array $variables
     * @return string
     */
    public function procesarContenido($variables = [])
    {
        $contenido = $this->contenido;

        foreach ($variables as $variable => $valor) {
            $contenido = str_replace("[{$variable}]", $valor, $contenido);
        }

        return $contenido;
    }

    /**
     * Tipos de plantillas disponibles
     *
     * @return array
     */
    public static function tiposDisponibles()
    {
        return [
            'bienvenida' => 'Mensaje de Bienvenida',
            'registro' => 'Registro Completado',
            'cumpleanos_propietario' => 'Cumpleaños Propietario',
            'cumpleanos_conductor' => 'Cumpleaños Conductor',
            'cumpleanos_personal' => 'Cumpleaños Personal',
            'vencimiento_licencia_conductor' => 'Vencimiento Licencia Conductor',
            'vencimiento_soat' => 'Vencimiento SOAT',
            'vencimiento_revision_tecnica' => 'Vencimiento Revisión Técnica',
            'regalo_mes' => 'Regalo del Mes'
        ];
    }
}
