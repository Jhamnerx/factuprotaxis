<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class SolicitudCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($row, $key) {
            return [
                'id' => $row->id,
                'tipo' => $row->tipo,
                'tipo_baja' => $row->tipo_baja,
                'tipo_texto' => $this->getTipoTexto($row->tipo),
                'descripcion' => $row->descripcion,
                'motivo' => $row->motivo,
                'vehiculo_id' => $row->vehiculo_id,
                'vehiculo' => $row->vehiculo,
                'placa' => isset($row->vehiculo['placa']) ? $row->vehiculo['placa'] : null,
                'propietario' => $row->propietario,
                'nombre_propietario' => isset($row->propietario['nombre_completo']) ? $row->propietario['nombre_completo'] : null,
                'usuario_id' => $row->usuario_id,
                'observaciones' => $row->observaciones,
                'estado' => $row->estado,
                'estado_texto' => $this->getEstadoTexto($row->estado),
                'fecha' => $row->fecha->format('Y-m-d'),
                'documentos_adjuntos' => $row->documentos_adjuntos,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $row->updated_at->format('Y-m-d H:i:s'),
                'user_name' => $row->creator->name,
                'user_id' => $row->creator->id,
                'cantidad_documentos' => is_array($row->documentos_adjuntos) ? count($row->documentos_adjuntos) : 0,
                'detalle' => $row->detalle->map(function ($detalle) {
                    return [
                        'id' => $detalle->id,
                        'vehiculo_id' => $detalle->vehiculo_id,
                        'placa' => $detalle->vehiculo ? $detalle->vehiculo['placa'] : null,
                        'propietario' => $detalle->propietario ? $detalle->propietario['name'] : null,
                        'correcciones' => $detalle->correcciones,
                    ];
                }),
            ];
        });
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

    private function getEstadoTexto($estado)
    {
        $estados = [
            'pendiente' => 'Pendiente',
            'aprobado' => 'Aprobado',
            'rechazado' => 'Rechazado',
            'en_proceso' => 'En Proceso',
            'completado' => 'Completado',
        ];

        return $estados[$estado] ?? $estado;
    }
}
