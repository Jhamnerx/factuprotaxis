<?php

namespace App\Models\Tenant\Taxis;


use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Taxis\Cobros;
use App\Models\Tenant\Taxis\Contratos;
use App\Models\Tenant\Taxis\Solicitud;
use App\Models\Tenant\Taxis\Vehiculos;
use App\Models\Tenant\Catalogs\Country;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant\Catalogs\District;
use App\Models\Tenant\Catalogs\Province;
use App\Models\Tenant\Catalogs\Department;
use App\Models\Tenant\Taxis\ConstanciaBaja;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Tenant\Catalogs\IdentityDocumentType;

class Propietarios extends ModelTenant
{
    use UsesTenantConnection;

    use SoftDeletes;

    protected $table = 'propietarios';
    protected $fillable = [
        'identity_document_type_id',
        'number',
        'name',
        'trade_name',
        'country_id',
        'department_id',
        'province_id',
        'district_id',
        'address_type_id',
        'address',
        'telephone',
        'email',
        'enabled',
        'website',
        'user_id'
    ];

    protected $with = [
        'identity_document_type',
        'country',
        'department',
        'province',
        'district'
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function identity_document_type()
    {
        return $this->belongsTo(IdentityDocumentType::class, 'identity_document_type_id');
    }
    public function country()
    {
        return $this->belongsTo(Country::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function province()
    {
        return $this->belongsTo(Province::class, 'province_id', 'id');
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function cliente()
    {
        return $this->belongsTo(\App\Models\Tenant\Person::class, 'person_id');
    }
    /**
     * @param $query
     *
     * @return mixed
     */
    public function scopeWhereIsEnabled($query)
    {
        return $query->where('enabled', true);
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
        $addresses = $this->addresses;
        if ($withFullAddress) {
            $addresses = collect($addresses)->transform(function ($row) {
                return $row->getCollectionData();
            });
        }

        /** @var \App\Models\Tenant\Catalogs\Department  $department */
        $department = \App\Models\Tenant\Catalogs\Department::find($this->department_id);
        if (!empty($department)) {
            $department = [
                "id" => $department->id,
                "description" => $department->description,
                "active" => $department->active,
            ];
        }

        $location_id = [];
        /** @var \App\Models\Tenant\Catalogs\Department  $department */
        $department = \App\Models\Tenant\Catalogs\Department::find($this->department_id);
        if (!empty($department)) {
            $department = [
                "id" => $department->id,
                "description" => $department->description,
                "active" => $department->active,
            ];
            array_push($location_id, $department['id']);
        }
        $province = \App\Models\Tenant\Catalogs\Province::find($this->province_id);

        if (!empty($province)) {
            $province = [
                "id" => $province->id,
                "description" => $province->description,
                "active" => $province->active,
            ];
            array_push($location_id, $province['id']);
        }
        $district = \App\Models\Tenant\Catalogs\District::find($this->district_id);

        if (!empty($district)) {
            $district = [
                "id" => $district->id,
                "description" => $district->description,
                "active" => $district->active,
            ];
            array_push($location_id, $district['id']);
        }

        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'number' => $this->number,
            'enabled' => (bool)$this->enabled,
            'identity_document_type_id' => $this->identity_document_type_id,
            'identity_document_type_code' => $this->identity_document_type->code,
            'document_type' => $this->identity_document_type->description,
            'address' => $this->address,
            'country_id' => $this->country_id,
            'department_id' => $department['id'] ?? null,
            'department' => $department,

            'province_id' => $province['id'] ?? null,
            'province' => $province,
            'district_id' => $district['id'] ?? null,
            'district' => $district,
            'telephone' => $this->telephone,
            'email' => $this->email,
            'addresses' => $addresses,
            'location_id' => $location_id,

        ];

        if ($childrens) {
            $data['childrens'] = $this->unidades->transform(function ($row) {
                return $row->getCollectionData();
            });
        }

        return $data;
    }

    public function getAddressFullAttribute()
    {
        $address = trim($this->address);
        $address = ($address === '-' || $address === '') ? '' : $address . ' ,';
        if ($address === '') {
            return '';
        }
        return "{$address} {$this->department->description} - {$this->province->description} - {$this->district->description}";
    }


    public function unidades()
    {
        return $this->hasMany(Vehiculos::class);
    }

    public function cartaPoder()
    {
        return $this->hasOne(CartaPoder::class);
    }
    public function cobros()
    {
        return $this->hasMany(Cobros::class);
    }

    public function constancias()
    {
        return $this->hasMany(ConstanciaBaja::class);
    }

    public function solicitudes()
    {
        return $this->hasMany(Solicitud::class);
    }

    public function contratos()
    {
        return $this->hasMany(Contratos::class, 'propietario_id', 'id');
    }
    public function vehiculos()
    {
        return $this->hasMany(Vehiculos::class, 'propietario_id', 'id');
    }
    public function permisosUnidad()
    {
        return $this->hasManyThrough(
            PermisoUnidad::class,
            Vehiculos::class,
            'propietario_id', // Foreign key en Vehiculos
            'vehiculo_id', // Foreign key en PermisoUnidad
            'id', // Local key en Propietarios
            'id' // Local key en Vehiculos
        );
    }
}
