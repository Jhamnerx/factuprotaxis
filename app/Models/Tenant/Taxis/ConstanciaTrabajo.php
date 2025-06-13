<?php

namespace App\Models\Tenant\Taxis;


use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class ConstanciaTrabajo extends ModelTenant
{
    use UsesTenantConnection;

    public function personal()
    {
        return $this->belongsTo(Personal::class);
    }
}
