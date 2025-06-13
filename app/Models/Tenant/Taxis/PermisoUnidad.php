<?php

namespace App\Models\Tenant\Taxis;

use App\Models\Tenant\User;
use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Taxis\Vehiculos;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant\Taxis\Propietarios;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class PermisoUnidad extends ModelTenant
{
    use UsesTenantConnection;

    protected $table = 'permisos_unidad';

    protected $fillable = [
        'vehiculo_id',
        'propietario',
        'vehiculo',
        'tipo_permiso',
        'fecha_inicio',
        'fecha_fin',
        'motivo',
        'estado',
        'user_id',
        'observaciones',
        'personas_autorizadas',
    ];

    protected $casts = [
        'personas_autorizadas' => 'array',
        'estado' => 'string',
        'tipo_permiso' => 'string',
        'vehiculo' => 'array',
        'propietario' => 'array',
        'fecha_inicio' => 'datetime',
        'fecha_fin' => 'datetime',
    ];

    public function datosVehiculo()
    {
        return $this->belongsTo(Vehiculos::class, 'vehiculo_id', 'id');
    }

    public function getCollectionData()
    {
        $v = $this->datosVehiculo;
        $vehiculo = $v->getCollectionData();

        $data = [
            'id' => $this->id,
            'vehiculo_id' => $this->vehiculo_id,
            'vehiculo' => $vehiculo,
            'propietario' => $this->propietario,
            'tipo_permiso' => $this->tipo_permiso,
            'fecha_inicio' => $this->fecha_inicio->format('Y-m-d'),
            'fecha_fin' => $this->fecha_fin->format('Y-m-d'),
            'motivo' => $this->motivo,
            'estado' => $this->estado,
            'observaciones' => $this->observaciones,
            'personas_autorizadas' => $this->personas_autorizadas,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'user_id' => $this->user_id,
            'creador' => $this->creator ? $this->creator->getCollectionData() : null,
            'created_at_formatted' => $this->created_at ? $this->created_at->format('Y-m-d') : null,
            'updated_at_formatted' => $this->updated_at ? $this->updated_at->format('Y-m-d') : null,
        ];

        return $data;
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function propietario()
    {
        return $this->hasOneThrough(
            Propietarios::class,
            Vehiculos::class,
            'id', // Foreign key en Vehiculos
            'id', // Foreign key en Propietarios
            'vehiculo_id', // Local key en PermisoUnidad
            'propietario_id' // Local key en Vehiculos
        );
    }
}
