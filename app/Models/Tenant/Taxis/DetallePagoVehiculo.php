<?php

namespace App\Models\Tenant\Taxis;

use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class DetallePagoVehiculo extends ModelTenant
{
    use UsesTenantConnection;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $table = 'detalle_pagos_vehiculos';

    public function pago()
    {
        return $this->belongsTo(PagoVehiculo::class, 'pagos_vehiculo_id', 'id');
    }
}
