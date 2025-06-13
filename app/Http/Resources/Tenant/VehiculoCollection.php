<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Taxis\Vehiculos;
use Illuminate\Http\Resources\Json\ResourceCollection;

class VehiculoCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function (Vehiculos $row, $key) {

            return  $row->getCollectionData();
        });
    }
}
