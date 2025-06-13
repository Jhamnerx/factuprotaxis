<?php

namespace App\Models\Tenant\Taxis;

use App\Models\Tenant\User;
use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Taxis\Vehiculos;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant\Taxis\Propietarios;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Stancl\Tenancy\Database\Concerns\TenantConnection;

class ConstanciaBaja extends ModelTenant
{
    use UsesTenantConnection;

    use SoftDeletes;

    protected $table = 'constancias_baja';

    protected $fillable = [
        'numero',
        'vehiculo_id',

        'estado',
        'fecha_emision',
        'observaciones',
        'user_id',
    ];

    protected $casts = [
        'fecha_emision' => 'datetime',
        'estado' => 'string',
        'vehiculo_id' => 'integer',
        'user_id' => 'integer',
    ];

    public function datosVehiculo()
    {
        return $this->belongsTo(Vehiculos::class, 'vehiculo_id', 'id');
    }

    public function getCollectionData()
    {
        $v = $this->datosVehiculo;
        $vehiculo = $v->getCollectionData();
        return [
            'id' => $this->id,
            'numero' => $this->numero,
            'vehiculo_id' => $this->vehiculo_id,
            'vehiculo' => $vehiculo,
            'estado' => $this->estado,
            'fecha_emision' => $this->fecha_emision ? $this->fecha_emision->format('Y-m-d') : null,
            'observaciones' => $this->observaciones,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'creador' => $this->creator ? $this->creator->getCollectionData() : null,
        ];
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }
}
