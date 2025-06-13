<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Taxis\Propietarios;
use Illuminate\Http\Resources\Json\ResourceCollection;

class PropietarioCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->transform(function (Propietarios $row, $key) {

            return  $row->getCollectionData();
        });
    }
}
