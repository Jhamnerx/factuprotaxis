<?php

namespace App\Http\Resources\Tenant;

use App\Models\Tenant\Taxis\Marca;
use Illuminate\Http\Resources\Json\ResourceCollection;

class MarcaCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function (Marca $row, $key) {

            return [
                'id' => $row->id,
                'nombre' => $row->nombre,
                'marca_id' => $row->marca_id,
                'make_country' => $row->make_country,
                'enabled' => $row->enabled ? 'ACTIVO' : 'INACTIVO',
                'created_at' => $row->created_at ? $row->created_at->format('Y-m-d H:i:s') : null,
            ];
        });
    }
}
