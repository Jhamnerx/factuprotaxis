<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class ModeloResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'marca_id' => $this->marca_id,
            'nombre' => $this->nombre,
            'model_make_id' => $this->model_make_id,
            'marca' => $this->marca->nombre, // Nombre correcto de la propiedad en la relación
            'enabled' => $this->enabled,
        ];
    }
}
