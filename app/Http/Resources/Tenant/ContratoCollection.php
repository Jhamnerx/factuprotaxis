<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Taxis\Contratos;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ContratoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function (Contratos $row, $key) {

            return  $row->getCollectionData();
        });
    }
}
