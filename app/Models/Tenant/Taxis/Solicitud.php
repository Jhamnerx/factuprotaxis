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
        'constancia_id',
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

    public function constanciaBaja()
    {
        return $this->belongsTo(ConstanciaBaja::class, 'constancia_id', 'id');
    }

    public function getDownloadSolicitudAttribute()
    {
        return route('tenant.pdf.solicitud', ['solicitud' => $this]);
    }

    public function getCollectionData()
    {
        return [
            'id' => $this->id,
            'tipo' => $this->tipo,
            'tipo_baja' => $this->tipo_baja,
            'constancia_id' => $this->constancia_id,
            'tipo_texto' => $this->getTipoTexto($this->tipo),
            'descripcion' => $this->descripcion,
            'motivo' => $this->motivo,
            'usuario_id' => $this->usuario_id,
            'observaciones' => $this->observaciones,
            'estado' => $this->estado,
            'estado_texto' => $this->getEstadoTexto($this->estado),
            'fecha' => $this->fecha->format('Y-m-d'),
            'documentos_adjuntos' => $this->documentos_adjuntos,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
            'user_name' => $this->creator->name,
            'user_id' => $this->creator->id,
            'creator' => $this->creator,
            'cantidad_documentos' => is_array($this->documentos_adjuntos) ? count($this->documentos_adjuntos) : 0,
            'download_solicitud' => $this->download_solicitud,
            'cantidad_vehiculos' => $this->detalle->count(),
            'detalle' => $this->detalle->map(function ($detalle) {
                return [
                    'id' => $detalle->id,
                    'vehiculo_id' => $detalle->vehiculo_id,
                    'placa' => $detalle->infoVehiculo ? $detalle->infoVehiculo->placa : null,
                    'propietario' => $detalle->propietario ? $detalle->propietario['name'] : null,
                    'correcciones' => $detalle->correcciones,
                ];
            }),
        ];
    }

    private function getTipoTexto($tipo)
    {
        $tipos = [
            'registro' => 'Registro de Unidad',
            'baja' => 'Baja de Unidad',
            'cambio_propietario' => 'Cambio de Propietario',
            'emision' => 'Emisión de Documentos',
            'correccion_datos' => 'Corrección de Datos',
        ];

        return $tipos[$tipo] ?? $tipo;
    }
    private function getEstadoTexto($estado)
    {
        $estados = [
            'pendiente' => 'Pendiente',
            'aceptada' => 'Aceptada',
            'rechazada' => 'Rechazada'
        ];

        return $estados[$estado] ?? $estado;
    }
}
