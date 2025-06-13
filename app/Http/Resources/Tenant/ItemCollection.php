<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;
use App\Models\Tenant\Configuration;

class ItemCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function toArray($request)
    {

        $configuration =  Configuration::first();

        return $this->collection->transform(function ($row, $key) use ($configuration) {
            /** @var \App\Models\Tenant\Item  $row */

            return $row->getCollectionData($configuration);
            /** Se ha movido la salida, al modelo */
        });
    }
}
