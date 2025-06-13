<?php

namespace App\Models\Tenant\Taxis;

use App\Models\Tenant\ModelTenant;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Cobros extends ModelTenant
{
    use UsesTenantConnection;
}
