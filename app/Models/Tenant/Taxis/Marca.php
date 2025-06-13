<?php

namespace App\Models\Tenant\Taxis;

use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Taxis\Modelo;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Marca extends ModelTenant
{
    use UsesTenantConnection;

    protected $table = 'marcas';
    protected $fillable = [
        'marca_id',
        'nombre',
        'make_country',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean'
    ];

    public function modelos()
    {
        return $this->hasMany(Modelo::class);
    }

    public function scopeActivas($query)
    {
        return $query->where('enabled', true);
    }
}
