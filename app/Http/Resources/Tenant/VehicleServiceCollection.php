<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Http\Controllers\Tenant\VehicleServiceController;

class VehicleServiceCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $vehicleServiceController = new VehicleServiceController();

        return $this->collection->transform(function ($service) use ($vehicleServiceController) {
            $diasRestantes = $service->diasHastaVencimiento();

            return [
                'id' => $service->id,
                'vehiculo_placa' => $service->vehiculo->placa ?? 'Sin placa',
                'vehiculo_numero_interno' => $service->vehiculo->numero_interno ?? '',
                'name' => $service->name,
                'custom_name' => $service->custom_name,
                'expires_date' => $service->expires_date ? $service->expires_date->format('d/m/Y') : '',
                'dias_restantes' => $diasRestantes,
                'dias_restantes_badge' => $vehicleServiceController->getDiasRestantesBadge($diasRestantes),
                'mobile_phone' => $service->mobile_phone,
                'email' => $service->email,
                'event_sent' => $service->event_sent ? 'Sí' : 'No',
                'event_sent_boolean' => $service->event_sent,
                'expired' => $service->expired ? 'Sí' : 'No',
                'expired_boolean' => $service->expired,
                'description' => $service->description,
                'propietario_nombre' => $service->nombre_propietario,
                'created_at' => $service->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $service->updated_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
