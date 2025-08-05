<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\YapeNotification;
use Illuminate\Http\Resources\Json\ResourceCollection;

class YapeNotificationCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function (YapeNotification $row, $key) {
            return $row->getCollectionData();
        });
    }
}
