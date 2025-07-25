<?php

namespace App\Models\Tenant\Taxis;

use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Taxis\Vehiculos;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant\Taxis\Propietarios;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class Contratos extends ModelTenant
{
    use UsesTenantConnection, SoftDeletes;

    protected $table = 'contratos';

    protected $fillable = [
        'vehiculo_id',
        'propietario_id',
        'vehiculo',
        'propietario',
        'fecha_inicio',
        'fecha_fin',
        'monto_tributo',
        'estado',
        'observaciones',
        'user_id'
    ];

    protected $casts = [
        'vehiculo' => 'array',
        'propietario' => 'array',
    ];

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculos::class);
    }

    public function propietario()
    {
        return $this->belongsTo(Propietarios::class);
    }

    public function user()
    {
        return $this->belongsTo(\App\Models\Tenant\User::class);
    }

    // Scopes
    public function scopeActivos($query)
    {
        return $query->where('estado', 'activo');
    }

    public function scopeFinalizados($query)
    {
        return $query->where('estado', 'finalizado');
    }

    public function scopeCancelados($query)
    {
        return $query->where('estado', 'cancelado');
    }

    public function getCollectionData()
    {
        $data = [
            'id' => $this->id,
            'vehiculo_id' => $this->vehiculo_id,
            'vehiculo' => $this->vehiculo,
            'propietario_id' => $this->propietario_id,
            'propietario' => $this->propietario,
            'fecha_inicio' => $this->fecha_inicio,
            'fecha_fin' => $this->fecha_fin,
            'monto_tributo' => $this->monto_tributo,
            'estado' => $this->estado,
            'observaciones' => $this->observaciones,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];

        return $data;
    }
}
