<?php

namespace App\Models\Tenant\Taxis;

use App\Models\Tenant\ModelTenant;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\SoftDeletes;

class Conductor extends ModelTenant
{
    use UsesTenantConnection;
    use SoftDeletes;

    protected $table = 'conductores';

    protected $fillable = [
        'name',
        'number',
        'licencias',
        'address',
        'telephone_1',
        'telephone_2',
        'telephone_3',
        'enabled',
    ];

    protected $casts = [
        'enabled' => 'boolean',
        'licencias' => 'array',
    ];

    /**
     * Obtener datos para la colección
     */
    public function getCollectionData($withFullAddress = false)
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'number' => $this->number,
            'licencias' => $this->licencias ?? [],
            'primary_license' => $this->getPrimaryLicense(),
            'address' => $this->address,
            'telephone_1' => $this->telephone_1,
            'telephone_2' => $this->telephone_2,
            'telephone_3' => $this->telephone_3,
            'enabled' => (bool)$this->enabled,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
        ];
    }

    /**
     * Obtener la licencia principal (primera licencia vigente)
     */
    public function getPrimaryLicense()
    {
        if (!$this->licencias || !is_array($this->licencias)) {
            return null;
        }

        // Buscar la primera licencia vigente
        foreach ($this->licencias as $licencia) {
            if (isset($licencia['estado']) && strtoupper($licencia['estado']) === 'VIGENTE') {
                return $licencia;
            }
        }

        // Si no hay licencias vigentes, devolver la primera
        return $this->licencias[0] ?? null;
    }

    /**
     * Verificar si tiene licencias vigentes
     */
    public function hasValidLicenses()
    {
        if (!$this->licencias || !is_array($this->licencias)) {
            return false;
        }

        foreach ($this->licencias as $licencia) {
            if (isset($licencia['estado']) && strtoupper($licencia['estado']) === 'VIGENTE') {
                return true;
            }
        }

        return false;
    }

    /**
     * Scope para conductores habilitados
     */
    public function scopeWhereIsEnabled($query)
    {
        return $query->where('enabled', true);
    }

    /**
     * Verificar si el conductor tiene vehículos asignados
     */
    public function hasVehiclesAssigned()
    {
        // Esta función se implementará cuando se tenga la relación con vehículos
        // return $this->vehiculos()->exists();
        return false;
    }
}
