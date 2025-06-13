<?php

namespace Modules\Payment\Models;

use Illuminate\Database\Eloquent\Model;

class PaymentColor extends Model
{
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
