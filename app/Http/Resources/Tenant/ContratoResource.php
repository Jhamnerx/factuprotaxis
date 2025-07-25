<?php

namespace App\Http\Resources\Tenant;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class ContratoResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        /** @var \App\Models\Tenant\Taxis\Contratos $this */
        return $this->getCollectionData();
    }
}
