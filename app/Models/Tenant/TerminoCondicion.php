<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class TerminoCondicion extends Model
{
    protected $table = 'terminos_condiciones';
    protected $fillable = [
        'titulo',
        'contenido',
        'version',
        'fecha_actualizacion',
        'estado'
    ];

    protected $casts = [
        'fecha_actualizacion' => 'date',
        'estado' => 'boolean'
    ];
}
