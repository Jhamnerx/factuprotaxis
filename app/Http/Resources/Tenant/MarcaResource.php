<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class MarcaResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'marca_id' => $this->marca_id,
            'nombre' => $this->nombre,
            'make_country' => $this->make_country,
            'enabled' => $this->enabled,
        ];
    }
}
