<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class PlanCollection extends ResourceCollection
{
    public function toArray($request)
    {
        return $this->collection->transform(function ($row) {
            return $row->getCollectionData();
        });
    }
}
