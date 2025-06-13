<?php

namespace App\Models\Tenant\Taxis;

use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Taxis\Marca;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Modelo extends ModelTenant
{
    use UsesTenantConnection;

    protected $table = 'modelos';
    protected $fillable = [
        'nombre',
        'marca_id',
        'model_make_id',
        'enabled'
    ];

    protected $casts = [
        'enabled' => 'boolean'
    ];

    public function marca()
    {
        return $this->belongsTo(Marca::class);
    }

    public function scopeActivos($query)
    {
        return $query->where('enabled', true);
    }
}
