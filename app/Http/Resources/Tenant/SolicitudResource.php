<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class SolicitudResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $solicitud = $this->resource;

        return [
            'id' => $solicitud->id,
            'tipo' => $solicitud->tipo,
            'tipo_baja' => $solicitud->tipo_baja,
            'tipo_texto' => $this->getTipoTexto($solicitud->tipo),
            'descripcion' => $solicitud->descripcion,
            'motivo' => $solicitud->motivo,
            'vehiculo_id' => $solicitud->vehiculo_id,
            'vehiculo' => $solicitud->vehiculo,
            'placa' => isset($solicitud->vehiculo['placa']) ? $solicitud->vehiculo['placa'] : null,
            'propietario' => $solicitud->propietario,
            'nombre_propietario' => isset($solicitud->propietario['nombre_completo']) ? $solicitud->propietario['nombre_completo'] : null,
            'usuario_id' => $solicitud->usuario_id ?? null,
            'observaciones' => $solicitud->observaciones,
            'estado' => $solicitud->estado,
            'fecha' => $solicitud->fecha->format('Y-m-d'),
            'documentos_adjuntos' => $solicitud->documentos_adjuntos,
            'created_at' => $solicitud->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $solicitud->updated_at->format('Y-m-d H:i:s'),
            'user_name' => $solicitud->creator->name,
            'user_id' => $solicitud->creator->id,
            'cantidad_documentos' => is_array($solicitud->documentos_adjuntos) ? count($solicitud->documentos_adjuntos) : 0,
            'detalle' => $solicitud->detalle->map(function ($detalle) {
                return [
                    'id' => $detalle->id,
                    'vehiculo_id' => $detalle->vehiculo_id,
                    'placa' => $detalle->vehiculo ? $detalle->vehiculo['placa'] : null,
                    'propietario' => $detalle->propietario ? $detalle->propietario['name'] : null,
                    'correcciones' => $detalle->correcciones,
                ];
            }),
        ];
    }

    private function getTipoTexto($tipo)
    {
        $tipos = [
            'registro' => 'Registro de Unidad',
            'baja' => 'Baja de Unidad',
            'cambio_propietario' => 'Cambio de Propietario',
            'emision' => 'Emisión de Documentos',
            'correccion_datos' => 'Corrección de Datos',
        ];

        return $tipos[$tipo] ?? $tipo;
    }
}
