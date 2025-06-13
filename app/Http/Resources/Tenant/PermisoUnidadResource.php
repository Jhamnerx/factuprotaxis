<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class PermisoUnidadResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var \App\Models\Tenant\Taxis\PermisoUnidad $this */
        return $this->getCollectionData();
    }
}
