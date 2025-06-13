<?php

namespace App\Models\Tenant\Taxis;

use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use App\Models\Tenant\Taxis\ConstanciaTrabajo;

class Personal extends ModelTenant
{
    use UsesTenantConnection;

    public function constancias()
    {
        return $this->hasMany(ConstanciaTrabajo::class);
    }
}
