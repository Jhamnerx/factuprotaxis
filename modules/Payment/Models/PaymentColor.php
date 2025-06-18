<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class PaymentColor extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'colorable_id',
        'colorable_type',
        'year',
        'month',
        'color'
    ];

    /**
     * Obtiene el modelo propietario de este color (en este caso, Vehículo).
     */
    public function colorable()
    {
        return $this->morphTo();
    }
}
