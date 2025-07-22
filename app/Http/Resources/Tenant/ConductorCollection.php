<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ConductorCollection extends ResourceCollection
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
            $primaryLicense = $row->getPrimaryLicense();
            return [
                'id' => $row->id,
                'name' => $row->name,
                'number' => $row->number,
                'fecha_nacimiento' => $row->fecha_nacimiento ? $row->fecha_nacimiento->format('Y-m-d') : null,
                'fecha_nacimiento_formatted' => $row->fecha_nacimiento ? $row->fecha_nacimiento->format('d/m/Y') : null,
                'edad' => $row->fecha_nacimiento ? $row->fecha_nacimiento->age : null,
                'licencia' => $row->licencia ?? [],
                'primary_license_number' => $primaryLicense['numero'] ?? 'Sin licencia',
                'primary_license_category' => $primaryLicense['categoria'] ?? '',
                'primary_license_status' => $primaryLicense['estado'] ?? '',
                'primary_license_expiration' => $primaryLicense['fecha_vencimiento'] ?? '',
                'primary_license_restrictions' => $primaryLicense['restricciones'] ?? '',
                'address' => $row->address,
                'telephone_1' => $row->telephone_1,
                'telephone_2' => $row->telephone_2,
                'telephone_3' => $row->telephone_3,
                'enabled' => $row->enabled,
                'has_valid_license' => $row->hasValidLicense(),
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
