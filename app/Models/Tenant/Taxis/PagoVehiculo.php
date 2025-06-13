<?php

namespace App\Models\Tenant\Taxis;

use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Taxis\Vehiculos;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Models\Tenant\Taxis\DetallePagoVehiculo;

class PagoVehiculo extends ModelTenant
{
    use UsesTenantConnection;

    protected $guarded = ['id', 'created_at', 'updated_at'];
    protected $table = 'pagos_vehiculos';

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculos::class);
    }

    public function detalle()
    {
        return $this->hasMany(DetallePagoVehiculo::class, 'pagos_vehiculo_id', 'id');
    }
}
