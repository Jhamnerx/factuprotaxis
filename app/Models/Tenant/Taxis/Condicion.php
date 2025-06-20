<?php

namespace App\Models\Tenant\Taxis;

use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\SoftDeletes;

class Condicion extends ModelTenant
{
    use UsesTenantConnection;

    protected $table = 'condiciones';

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public function getCollectionData()
    {
        return [
            'id' => $this->id,
            'descripcion' => $this->descripcion,
            'color' => $this->color,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }
}
