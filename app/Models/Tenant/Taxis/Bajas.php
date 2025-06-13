<?php

namespace App\Models\Tenant\Taxis;



use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Taxis\Vehiculos;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Bajas extends ModelTenant
{
    use UsesTenantConnection;

    protected $fillable = ['vehiculo_id', 'motivo', 'fecha'];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculos::class);
    }
}
