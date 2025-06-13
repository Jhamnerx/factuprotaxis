<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Taxis\Declaracion;
use Illuminate\Http\Resources\Json\ResourceCollection;

class DeclaracionCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->transform(function (Declaracion $row, $key) {

            return  $row->getCollectionData();
        });
    }
}
