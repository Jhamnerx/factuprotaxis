<?php

namespace App\Models\Tenant;

use Carbon\Carbon;
use App\Models\Tenant\ModelTenant;

class VehicleService extends ModelTenant
{
    protected $table = 'vehicle_services';

    protected $fillable = [
        'user_id',
        'device_id',
        'name',
        'expiration_by',
        'interval',
        'last_service',
        'trigger_event_left',
        'renew_after_expiration',
        'expires',
        'expires_date',
        'remind',
        'remind_date',
        'event_sent',
        'expired',
        'email',
        'mobile_phone',
        'description'
    ];

    protected $casts = [
        'expires_date' => 'date',
        'remind_date' => 'date',
        'renew_after_expiration' => 'boolean',
        'event_sent' => 'boolean',
        'expired' => 'boolean'
    ];

    /**
     * Relación con vehículo
     */
    public function vehiculo()
    {
        return $this->belongsTo('App\Models\Tenant\Taxis\Vehiculos', 'device_id');
    }

    /**
     * Relación con usuario
     */
    public function user()
    {
        return $this->belongsTo('App\Models\Tenant\User', 'user_id');
    }

    /**
     * Scopes
     */
    public function scopeProximosAVencer($query, $dias = 30)
    {
        return $query->where('expires_date', '<=', Carbon::now()->addDays($dias))
            ->where('expires_date', '>=', Carbon::now())
            ->where('expired', false);
    }

    public function scopeVencidos($query)
    {
        return $query->where('expires_date', '<', Carbon::now())
            ->where('expired', false);
    }

    public function scopePorTipo($query, $tipo)
    {
        return $query->where('name', $tipo);
    }

    public function scopeConTelefono($query)
    {
        return $query->whereNotNull('mobile_phone')
            ->where('mobile_phone', '!=', '');
    }

    /**
     * Verificar si el servicio está próximo a vencer
     */
    public function estaProximoAVencer($dias = 30)
    {
        if (!$this->expires_date) return false;

        return $this->expires_date->between(
            Carbon::now(),
            Carbon::now()->addDays($dias)
        );
    }

    /**
     * Verificar si el servicio está vencido
     */
    public function estaVencido()
    {
        if (!$this->expires_date) return false;

        return $this->expires_date->isPast();
    }

    /**
     * Marcar como evento enviado
     */
    public function marcarEventoEnviado()
    {
        $this->update(['event_sent' => true]);
    }

    /**
     * Marcar como vencido
     */
    public function marcarVencido()
    {
        $this->update(['expired' => true]);
    }

    /**
     * Obtener días hasta vencimiento
     */
    public function diasHastaVencimiento()
    {
        if (!$this->expires_date) return null;

        return Carbon::now()->diffInDays($this->expires_date, false);
    }

    /**
     * Obtener el nombre del propietario del vehículo
     */
    public function getNombrePropietarioAttribute()
    {
        return $this->vehiculo?->propietario?->name ?? 'Sin propietario';
    }

    /**
     * Obtener el teléfono del propietario del vehículo
     */
    public function getTelefonoPropietarioAttribute()
    {
        return $this->vehiculo?->propietario?->telephone_1 ?? null;
    }

    /**
     * Tipos de servicios
     */
    public static function tiposServicios()
    {
        return [
            'SOAT' => 'Seguro Obligatorio de Accidentes de Tránsito',
            'REVISION_TECNICA' => 'Revisión Técnica Vehicular',
            'LICENCIA_CONDUCTOR' => 'Licencia de Conducir',
            'POLIZA_SEGURO' => 'Póliza de Seguro',
            'MANTENIMIENTO' => 'Mantenimiento Preventivo'
        ];
    }
}
