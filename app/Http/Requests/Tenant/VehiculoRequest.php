<?php

namespace App\Http\Requests\Tenant;


use Illuminate\Validation\Rule;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Support\Carbon;

class VehiculoRequest extends FormRequest
{


    public function rules(): array
    {
        $rules = [
            'numero_interno' => [
                'required',
                Rule::unique('tenant.vehiculos')->where(function ($query) {
                    return $query->where('id', '<>', $this->input('id'));
                })
            ],
            'placa' => [
                'required',
                Rule::unique('tenant.vehiculos')->where(function ($query) {
                    return $query->where('id', '<>', $this->input('id'));
                })
            ],
            'propietario_id' => 'required|exists:tenant.propietarios,id',
            'marca_id' => 'required|exists:tenant.marcas,id',
            'modelo_id' => 'required|exists:tenant.modelos,id',
            'color' => 'required|string|max:80',
            'year' => 'required|integer|min:1900|max:' . date('Y'),
            'fecha_ingreso' => 'required|date',
            'estado' => 'required|string',
            'estado_tuc_id' => 'required',
            'largo' => 'required|numeric',
            'ancho' => 'required|numeric',
            'alto' => 'required|numeric',
            'peso' => 'required|numeric',
            'carga_util' => 'required|numeric',
            'ccn' => 'nullable|string|max:50',
            'numero_motor' => 'required|string|max:50',
            'ejes' => 'required|integer',
            'asientos' => 'required|integer',
            'categoria' => 'required|string|max:50',
            'user_id' => 'nullable|exists:users,id',
        ];

        return $rules;
    }

    public function messages()
    {
        $messages = [
            'numero_interno.required' => 'El campo número interno es obligatorio.',
            'numero_interno.unique' => 'El número interno ya está en uso.',
            'placa.required' => 'El campo placa es obligatorio.',
            'placa.unique' => 'La placa ya está registrada.',
            'estado.required' => 'El campo estado es obligatorio.',
            'propietario_id.required' => 'El campo propietario es obligatorio.',
            'propietario_id.exists' => 'El propietario seleccionado no existe.',
            'largo.numeric' => 'El campo largo debe ser un valor numérico.',
            'ancho.numeric' => 'El campo ancho debe ser un valor numérico.',
            'alto.numeric' => 'El campo alto debe ser un valor numérico.',
            'peso.numeric' => 'El campo peso debe ser un valor numérico.',
            'carga_util.numeric' => 'El campo carga útil debe ser un valor numérico.',
            'ejes.max' => 'El campo ejes no debe exceder los 10 caracteres.',
            'asientos.integer' => 'El campo asientos debe ser un número entero.',
            'marca_id.exists' => 'La marca seleccionada no existe.',
            'modelo_id.exists' => 'El modelo seleccionado no existe.',
            'year.integer' => 'El campo año debe ser un número entero.',
            'year.min' => 'El campo año debe ser mayor o igual a 1900.',
            'year.max' => 'El campo año no puede ser mayor al año actual.',
            'fecha_ingreso.date' => 'El campo fecha de ingreso debe ser una fecha válida.',
            'user_id.exists' => 'El usuario seleccionado no existe.',
            'marca_id.required' => 'El campo marca es obligatorio.',
            'modelo_id.required' => 'El campo modelo es obligatorio.',
        ];
        return $messages;
    }
}
