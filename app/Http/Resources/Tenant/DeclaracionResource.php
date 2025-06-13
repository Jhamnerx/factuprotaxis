<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Taxis\Declaracion;
use Illuminate\Http\Resources\Json\JsonResource;

class DeclaracionResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var \App\Models\Tenant\Taxis\Declaracion $this */
        return $this->getCollectionData();
    }
}
