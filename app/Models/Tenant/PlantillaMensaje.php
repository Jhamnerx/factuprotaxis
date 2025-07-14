<?php

namespace App\Models\Tenant;

use App\Models\Tenant\ModelTenant;

class PlantillaMensaje extends ModelTenant
{
    protected $table = 'plantilla_mensajes';
    protected $fillable = [
        'tipo',
        'asunto',
        'contenido',
        'estado',
        'descripcion'
    ];
}
