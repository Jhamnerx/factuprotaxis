<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class AjusteWeb extends Model
{
    protected $table = 'ajustes_web';
    protected $fillable = [
        'nombre',
        'valor',
        'descripcion',
        'estado'
    ];
}
