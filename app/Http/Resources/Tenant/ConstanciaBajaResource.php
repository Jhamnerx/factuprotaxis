<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class ConstanciaBajaResource extends JsonResource
{
    public function toArray($request)
    {
        /** @var \App\Models\Tenant\Taxis\ConstanciaBaja $this */
        return $this->getCollectionData();
    }
}
