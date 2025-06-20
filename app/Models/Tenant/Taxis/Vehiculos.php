<?php

namespace App\Models\Tenant\Taxis;

use Modules\Payment\Models\Plan;
use App\Enums\EstadoVehiculoEnum;
use App\Models\Tenant\ModelTenant;
use App\Enums\EstadoTucVehiculoEnum;
use App\Traits\HasPlanSubscriptions;
use App\Models\Tenant\Taxis\Condicion;
use App\Models\Tenant\Taxis\Contratos;
use App\Models\Tenant\Taxis\Solicitud;
use Illuminate\Database\Eloquent\Model;
use Modules\Payment\Models\PaymentColor;
use Modules\Payment\Models\Subscription;
use App\Models\Tenant\Taxis\Propietarios;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Factories\HasFactory;


class Vehiculos extends ModelTenant
{
    use UsesTenantConnection;
    use HasPlanSubscriptions;

    protected $table = 'vehiculos';

    protected $fillable = [
        'numero_interno',
        'placa',
        'largo',
        'ancho',
        'alto',
        'peso',
        'carga_util',
        'ccn',
        'numero_motor',
        'ejes',
        'asientos',
        'categoria',
        'marca_id',
        'modelo_id',
        'color',
        'year',
        'fecha_ingreso',
        'estado_tuc_id',
        'estado',
        'propietario_id',
        'plan_id',
        'subscription_id',
        'user_id'
    ];

    protected $casts = [
        'fecha_ingreso' => 'datetime',
        'estado' => 'string',
        'estado_tuc_id' => 'integer',
        'largo' => 'float',
        'ancho' => 'float',
        'alto' => 'float',
        'peso' => 'float',
        'carga_util' => 'float',
        'ejes' => 'integer',
        'asientos' => 'integer',
        'year' => 'integer',
        'propietario_id' => 'integer',
        'marca_id' => 'integer',
        'modelo_id' => 'integer',
        'plan_id' => 'integer',
        'subscription_id' => 'integer',
        'user_id' => 'integer'
    ];

    // Scope local de activo
    public function scopeEstado($query, $status)
    {
        return $query->where('estado', $status);
    }

    public function scopeEstadoTuc($query, $status)
    {
        return $query->where('estado_tuc', $status);
    }

    /**
     * @param Builder $query
     *
     * @return Builder
     */
    public function scopeWhereIsActive($query)
    {
        return $query->where('estado', 'ACTIVO');
    }

    public function scopeNotActive($query)
    {
        return $query->where('estado', '!=', 'ACTIVO');
    }

    public function suscripciones()
    {
        return $this->morphMany(Subscription::class, 'vehiculo');
    }

    public function subscription()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id');
    }

    public function plan()
    {
        return $this->belongsTo(Plan::class, 'plan_id', 'id');
    }

    public function contratos()
    {
        return $this->hasMany(Contratos::class);
    }

    public function propietario()
    {
        return $this->belongsTo(Propietarios::class, 'propietario_id', 'id')->withTrashed();
    }

    public function marca()
    {
        return $this->belongsTo(Marca::class, 'marca_id', 'id');
    }

    public function modelo()
    {
        return $this->belongsTo(Modelo::class, 'modelo_id', 'id');
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class, 'vehiculo_id', 'id');
    }
    public function paymentColors()
    {
        return $this->morphMany(PaymentColor::class, 'colorable');
    }

    public function permisosUnidad()
    {
        return $this->hasMany(PermisoUnidad::class, 'vehiculo_id', 'id');
    }

    public function estadoTuc()
    {
        return $this->belongsTo(Condicion::class, 'estado_tuc_id', 'id');
    }

    /**
     * Retorna un standar de nomenclatura para el modelo
     *
     * @param bool $withFullAddress
     * @param bool $childrens
     *
     * @return array
     */
    public function getCollectionData($withFullAddress = false, $childrens = false, $servers = false)
    {
        /** @var \App\Models\Tenant\Taxis\Propietarios  $propietario */
        $propietario = \App\Models\Tenant\Taxis\Propietarios::find($this->propietario_id);
        if (!empty($propietario)) {
            $propietario = [
                "id" => $propietario->id,
                "name" => $propietario->name,
                "active" => $propietario->enabled,
            ];
        }

        $marca = null;
        if (!empty($this->marca_id)) {
            $marca = $this->marca()->first();
            if ($marca) {
                $marca = [
                    'id' => $marca->id,
                    'nombre' => $marca->nombre
                ];
            }
        }

        $modelo = null;
        if (!empty($this->modelo_id)) {
            $modelo = $this->modelo()->first();
            if ($modelo) {
                $modelo = [
                    'id' => $modelo->id,
                    'nombre' => $modelo->nombre,
                    'marca_id' => $modelo->marca_id
                ];
            }
        }
        $data = [
            'id' => $this->id,
            'numero_interno' => $this->numero_interno,
            'placa' => $this->placa,
            'largo' => $this->largo,
            'ancho' => $this->ancho,
            'alto' => $this->alto,
            'peso' => $this->peso,
            'carga_util' => $this->carga_util,
            'ccn' => $this->ccn,
            'numero_motor' => $this->numero_motor,
            'ejes' => $this->ejes,
            'asientos' => $this->asientos,
            'categoria' => $this->categoria,
            'marca_id' => $this->marca_id,
            'modelo_id' => $this->modelo_id,
            'marca' => $marca,
            'modelo' => $modelo,
            'color' => $this->color,
            'year' => $this->year,
            'fecha_ingreso' => ($this->fecha_ingreso) ? $this->fecha_ingreso->format('Y-m-d') : null,
            'estado_tuc_id' => $this->estado_tuc_id,
            'estadoTuc' => $this->estadoTuc,
            'estado' => $this->estado,
            'propietario_id' => $this->propietario_id,
            'propietario' => $propietario,
            'plan_id' => $this->plan_id,
            'subscription_id' => $this->subscription_id,
            'subscription' => $this->subscription ? $this->subscription->getCollectionData() : null,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];

        return $data;
    }
}
