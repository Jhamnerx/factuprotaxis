<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class VehicleServiceResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $diasRestantes = $this->diasHastaVencimiento();

        return [
            'id' => $this->id,
            'device_id' => $this->device_id,
            'name' => $this->name,
            'custom_name' => $this->custom_name,
            'name_formatted' => $this->formatearNombreServicio($this->name),
            'trigger_event_left' => $this->trigger_event_left,
            'renew_after_expiration' => $this->renew_after_expiration,
            'expires_date' => $this->expires_date ? $this->expires_date->format('Y-m-d') : null,
            'remind_date' => $this->remind_date ? $this->remind_date->format('Y-m-d') : null,
            'email' => $this->email,
            'mobile_phone' => $this->mobile_phone,
            'description' => $this->description,
            'event_sent' => $this->event_sent,
            'expired' => $this->expired,

            // Información adicional para mostrar
            'vehiculo' => $this->whenLoaded('vehiculo', function () {
                return [
                    'id' => $this->vehiculo->id,
                    'placa' => $this->vehiculo->placa,
                    'numero_interno' => $this->vehiculo->numero_interno,
                    'propietario' => $this->vehiculo->propietario->name ?? 'Sin propietario',
                    'propietario_id' => $this->vehiculo->propietario_id
                ];
            }),

            // Información calculada
            'dias_restantes' => $diasRestantes,
            'propietario_nombre' => $this->nombre_propietario,
            'telefono_propietario' => $this->telefono_propietario,

            // Información para la interfaz
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }

    private function formatearNombreServicio($nombre)
    {
        // Mapeo de nombres comunes de servicios
        $serviciosFormateados = [
            'soat' => 'SOAT',
            'afocat' => 'AFOCAT',
            'revision_tecnica' => 'Revisión Técnica',
            'mantenimiento' => 'Mantenimiento',
            'poliza_seguro' => 'Póliza de Seguro',
            'licencia_conductor' => 'Licencia de Conductor',
            'citv' => 'CITV',
            'inspeccion_tecnica' => 'Inspección Técnica',
            'seguro_obligatorio' => 'Seguro Obligatorio',
            'tarjeta_propiedad' => 'Tarjeta de Propiedad',
        ];

        $nombreLower = strtolower(trim($nombre));

        // Si existe en el mapeo, usar el formato correcto
        if (isset($serviciosFormateados[$nombreLower])) {
            return $serviciosFormateados[$nombreLower];
        }

        // Si no existe en el mapeo, aplicar formato general
        return ucwords(str_replace(['_', '-'], ' ', $nombreLower));
    }
}
