<?php

namespace App\Models\Tenant\Taxis;

use App\Models\Tenant\User;
use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Taxis\Solicitud;
use App\Models\Tenant\Taxis\Vehiculos;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class SolicitudDetalle extends ModelTenant
{
    use UsesTenantConnection;

    protected $table = 'solicitudes_detalle';

    protected $fillable = [
        'solicitud_id',
        'vehiculo_id',
        'vehiculo',
        'propietario',
        'correcciones',

    ];

    protected $casts = [
        'vehiculo' => 'array',
        'propietario' => 'array',
        'correcciones' => 'array',
    ];

    protected $with = [
        'infoVehiculo',
    ];

    public function solicitud()
    {
        return $this->belongsTo(Solicitud::class);
    }

    public function infoVehiculo()
    {
        return $this->belongsTo(Vehiculos::class, 'vehiculo_id', 'id');
    }
}
