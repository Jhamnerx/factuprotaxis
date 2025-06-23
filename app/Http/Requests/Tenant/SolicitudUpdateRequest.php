<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class SolicitudUpdateRequest extends FormRequest
{
    public function authorize()
    {
        // Puedes personalizar la autorización si es necesario
        return true;
    }

    public function rules()
    {
        return [
            'tipo' => 'required|string',
            'descripcion' => 'nullable|string',
            'motivo' => 'nullable|string',
            'tipo_baja' => 'nullable|string',
            'constancia_id' => 'required_if:tipo_baja,constancia',
            'observaciones' => 'nullable|string',
            'estado' => 'nullable|string',
            'fecha' => 'nullable|date',
            'documentos_adjuntos' => 'nullable',
            'detalles' => 'nullable',
        ];
    }

    public function messages()
    {
        return [
            'tipo.required' => 'El campo tipo es obligatorio.',
            'descripcion.string' => 'La descripción debe ser una cadena de texto.',
            'motivo.string' => 'El motivo debe ser una cadena de texto.',
            'tipo_baja.string' => 'El tipo de baja debe ser una cadena de texto.',
            'observaciones.string' => 'Las observaciones deben ser una cadena de texto.',
            'estado.string' => 'El estado debe ser una cadena de texto.',
            'fecha.date' => 'La fecha debe ser una fecha válida.',
            'documentos_adjuntos.array' => 'Los documentos adjuntos deben ser un arreglo.',
            'detalles.array' => 'Los detalles deben ser un arreglo.',
            'constancia_id.required_if' => 'El campo constancia_id es obligatorio cuando el tipo de baja es constancia.',
        ];
    }
}
