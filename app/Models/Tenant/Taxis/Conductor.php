<?php

namespace App\Models\Tenant\Taxis;

use App\Models\Tenant\ModelTenant;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Hash;

class Conductor extends Authenticatable
{
    use UsesTenantConnection;
    use SoftDeletes;
    use Notifiable;

    protected $fillable = [
        'name',
        'number',
        'fecha_nacimiento',
        'licencia',
        'address',
        'telephone_1',
        'telephone_2',
        'telephone_3',
        'email',
        'password',
        'enabled'
    ];

    protected $dates = [
        'fecha_nacimiento'
    ];

    protected $casts = [
        'licencia' => 'array',
        'enabled' => 'boolean',
        'email_verified_at' => 'datetime',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $table = 'conductores';

    /**
     * Mutator para hashear la contraseña
     */
    public function setPasswordAttribute($value)
    {
        if ($value) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    /**
     * Obtener el campo de email para autenticación
     */
    public function getEmailForPasswordReset()
    {
        return $this->email;
    }

    /**
     * Obtener el tipo de usuario para guards
     */
    public function getUserTypeAttribute()
    {
        return 'conductor';
    }

    /**
     * Scope para conductores activos
     */
    public function scopeWhereEnabled($query)
    {
        return $query->where('enabled', true);
    }

    /**
     * Obtener información para la colección de datos
     */
    public function getCollectionData()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'number' => $this->number,
            'fecha_nacimiento' => $this->fecha_nacimiento ? $this->fecha_nacimiento->format('Y-m-d') : null,
            'fecha_nacimiento_formatted' => $this->fecha_nacimiento ? $this->fecha_nacimiento->format('d/m/Y') : null,
            'edad' => $this->fecha_nacimiento ? $this->fecha_nacimiento->diffInYears(now()) : null,
            'address' => $this->address,
            'telephone_1' => $this->telephone_1,
            'telephone_2' => $this->telephone_2,
            'telephone_3' => $this->telephone_3,
            'email' => $this->email,
            'licencia' => $this->licencia ?? [],
            'primary_license' => $this->getPrimaryLicense(),
            'enabled' => $this->enabled,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at
        ];
    }

    /**
     * Obtener la licencia principal del conductor
     */
    public function getPrimaryLicense()
    {
        return $this->licencia;
    }

    /**
     * Verificar si el conductor tiene una licencia válida
     */
    public function hasValidLicense()
    {
        if (!$this->licencia || !is_array($this->licencia)) {
            return false;
        }

        return isset($this->licencia['estado']) && strtoupper($this->licencia['estado']) === 'VIGENTE';
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
        return $this->vehiculo()->exists();
    }

    /**
     * Obtener el nombre completo del conductor
     */
    public function getFullNameAttribute()
    {
        return $this->name;
    }

    /**
     * Obtener la información de la licencia formateada
     */
    public function getFormattedLicenseAttribute()
    {
        if (!$this->licencia) {
            return 'Sin licencia';
        }

        $licencia = $this->licencia;
        $numero = $licencia['numero'] ?? 'No especificado';
        $estado = $licencia['estado'] ?? 'No especificado';
        $vigencia = $licencia['vigencia'] ?? 'No especificado';

        return "Licencia: {$numero} - Estado: {$estado} - Vigencia: {$vigencia}";
    }

    /**
     * Scope para buscar conductores por DNI
     */
    public function scopeWhereSearchDni($query, $number)
    {
        if (!$number) {
            return $query;
        }

        return $query->where('number', 'like', '%' . $number . '%');
    }

    /**
     * Scope para buscar conductores por nombre
     */
    public function scopeWhereSearchName($query, $name)
    {
        if (!$name) {
            return $query;
        }

        return $query->where('name', 'like', '%' . $name . '%');
    }

    public function vehiculo()
    {
        return $this->hasOne(Vehiculos::class, 'conductor_id');
    }
}
