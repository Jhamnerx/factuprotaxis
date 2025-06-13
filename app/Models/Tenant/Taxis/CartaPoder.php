<?php

namespace App\Models\Tenant\Taxis;


use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Taxis\Vehiculos;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant\Taxis\Propietarios;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class CartaPoder extends ModelTenant
{
    use UsesTenantConnection;

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculos::class, 'placa', 'placa');
    }

    public function propietario()
    {
        return $this->hasOneThrough(Propietarios::class, Vehiculos::class, 'placa', 'id', 'placa', 'propietario_id');
    }
}
