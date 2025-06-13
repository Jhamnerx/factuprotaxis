<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\ResourceCollection;

class ModeloCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return $this->collection->transform(function ($row, $key) {
            return [
                'id' => $row->id,
                'marca_id' => $row->marca_id,
                'marca' => $row->marca,
                'nombre' => $row->nombre,
                'model_make_id' => $row->model_make_id,
                'enabled' => $row->enabled ? 'ACTIVO' : 'INACTIVO',
                'created_at' => $row->created_at->format('Y-m-d H:i:s'),
            ];
        });
    }
}
