<?php

namespace App\Models\Tenant\Taxis;

use App\Models\Tenant\ModelTenant;
use App\Models\Tenant\Taxis\Vehiculos;
use Illuminate\Database\Eloquent\Model;
use App\Models\Tenant\Taxis\Propietarios;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Contratos extends ModelTenant
{
    use UsesTenantConnection;

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculos::class);
    }

    public function propietario()
    {
        return $this->belongsTo(Propietarios::class);
    }
}
