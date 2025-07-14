<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;

class PoliticaPrivacidad extends Model
{
    protected $table = 'politicas_privacidad';
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
