<?php

namespace App\Models\Tenant\Taxis;

use App\Models\Tenant\User;
use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class Declaracion extends ModelTenant
{
    use UsesTenantConnection;

    protected $table = 'declaraciones';

    protected $fillable = [
        'vehiculo_id',
        'vehiculo',
        'propietario',
        'fecha_emision',
        'observaciones',
        'user_id',
    ];

    protected $casts = [
        'vehiculo' => 'array',
        'propietario' => 'array',
        'fecha_emision' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
        'user_id' => 'integer',
    ];

    public function datosVehiculo()
    {
        return $this->belongsTo(Vehiculos::class, 'vehiculo_id');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getCollectionData()
    {
        $vehiculo = $this->datosVehiculo ? $this->datosVehiculo->getCollectionData() : null;

        return [
            'id' => $this->id,
            'vehiculo_id' => $this->vehiculo_id,
            'vehiculo' => $vehiculo,
            'propietario' => $this->propietario,
            'fecha_emision' => $this->fecha_emision ? $this->fecha_emision->format('Y-m-d') : null,
            'observaciones' => $this->observaciones,
            'creador' => $this->creator ? $this->creator->getCollectionData() : null,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d') : null,
        ];
    }
}
