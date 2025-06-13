<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Taxis\ConstanciaBaja;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ConstanciaBajaCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->transform(function (ConstanciaBaja $row, $key) {

            return  $row->getCollectionData();
        });
    }
}
