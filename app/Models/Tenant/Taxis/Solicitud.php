<?php

namespace App\Models\Tenant\Taxis;

use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Taxis\Vehiculos;
use App\Models\Tenant\User;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Models\Tenant\Taxis\SolicitudDetalle;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Solicitud extends ModelTenant
{
    use UsesTenantConnection;
    use SoftDeletes;

    protected $table = 'solicitudes';

    protected $with = [
        'detalle',
        'creator',
    ];
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */

    protected $fillable = [
        'tipo',
        'tipo_baja',
        'descripcion',
        'motivo',
        'observaciones',
        'estado',
        'fecha',
        'documentos_adjuntos',
        'user_id',
    ];

    protected $casts = [
        'documentos_adjuntos' => 'array',
        'fecha' => 'datetime',
    ];

    public function detalle()
    {
        return $this->hasMany(SolicitudDetalle::class);
    }


    public function creator()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    // Relación con detalles, no más relación directa con vehiculo_id
}
