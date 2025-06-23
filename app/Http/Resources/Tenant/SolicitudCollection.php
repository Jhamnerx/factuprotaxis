<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Taxis\Solicitud;
use Illuminate\Http\Resources\Json\ResourceCollection;

class SolicitudCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function (Solicitud $row, $key) {
            return  $row->getCollectionData();
        });
    }
}
