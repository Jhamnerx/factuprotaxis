<?php

namespace App\Models\Tenant\Taxis;

use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Taxis\Vehiculos;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\SoftDeletes;

class SolicitudRegistro extends ModelTenant
{
    use UsesTenantConnection;
    use SoftDeletes;

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculos::class, 'placa', 'placa');
    }
}
