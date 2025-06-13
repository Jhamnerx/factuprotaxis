<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Taxis\PermisoUnidad;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PermisoUnidadCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->transform(function (PermisoUnidad $row, $key) {

            return  $row->getCollectionData();
        });
    }
}
