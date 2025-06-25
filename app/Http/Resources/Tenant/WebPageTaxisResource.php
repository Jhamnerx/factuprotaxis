<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class WebPageTaxisResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        // Si el recurso está vacío, devolver un array vacío
        if (!$this->resource) {
            return [];
        }

        // Llamamos al método getCollectionData del modelo
        return $this->resource->getCollectionData();
    }
}
