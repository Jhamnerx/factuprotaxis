<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PlantillaMensajeCollection extends ResourceCollection
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
                'asunto' => $row->asunto,
                'descripcion' => $row->descripcion,
                'estado' => $row->estado ? 'Activa' : 'Inactiva',
                'estado_boolean' => (bool) $row->estado,
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
