<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\ContactMessage;
use Illuminate\Http\Resources\Json\ResourceCollection;

class ContactMessageCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function (ContactMessage $row, $key) {

            return  $row->getCollectionData();
        });
    }
}
