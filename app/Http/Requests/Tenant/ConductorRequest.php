<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class ConductorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */    public function rules()
    {
        $id = $this->input('id');

        return [
            'name' => 'required|string|max:255',
            'number' => 'required|string|min:8|max:20|unique:tenant.conductores,number,' . $id,
            'fecha_nacimiento' => 'nullable|date|before:today',
            'address' => 'nullable|string|max:255',
            'telephone_1' => 'nullable|string|max:20',
            'telephone_2' => 'nullable|string|max:20',
            'telephone_3' => 'nullable|string|max:20',
            'email' => 'nullable|email|unique:tenant.conductores,email,' . $id,
            'password' => 'nullable|string|min:6',
            'licencia' => 'nullable|array',
            'licencia.numero' => 'required_with:licencia|string|max:50',
            'licencia.categoria' => 'required_with:licencia|string|max:50',
            'licencia.fecha_expedicion' => 'required_with:licencia|string|max:50',
            'licencia.fecha_vencimiento' => 'required_with:licencia|string|max:50',
            'licencia.estado' => 'required_with:licencia|string|in:VIGENTE,VENCIDA,SUSPENDIDA',
            'licencia.restricciones' => 'nullable|string|max:255',
            'enabled' => 'boolean',
        ];
    }

    /**
     * Get custom attributes for validator errors.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'nombre',
            'number' => 'número de documento',
            'fecha_nacimiento' => 'fecha de nacimiento',
            'address' => 'dirección',
            'telephone_1' => 'teléfono 1',
            'telephone_2' => 'teléfono 2',
            'telephone_3' => 'teléfono 3',
            'licencia' => 'licencia',
            'licencia.numero' => 'número de licencia',
            'licencia.categoria' => 'categoría de licencia',
            'licencia.fecha_expedicion' => 'fecha de expedición',
            'licencia.fecha_vencimiento' => 'fecha de vencimiento',
            'licencia.estado' => 'estado de licencia',
            'licencia.restricciones' => 'restricciones',
            'enabled' => 'habilitado',
        ];
    }

    /**
     * Get the error messages for the defined validation rules.
     *
     * @return array
     */
    public function messages()
    {
        return [
            'name.required' => 'El campo :attribute es obligatorio.',
            'number.required' => 'El campo :attribute es obligatorio.',
            'number.min' => 'El :attribute debe tener al menos 8 caracteres.',
            'number.max' => 'El :attribute no puede tener más de 20 caracteres.',
            'number.unique' => 'El :attribute ya está en uso.',
            'fecha_nacimiento.date' => 'La :attribute debe ser una fecha válida.',
            'fecha_nacimiento.before' => 'La :attribute debe ser anterior a la fecha actual.',
            'licencia.numero.required_with' => 'El número de licencia es obligatorio.',
            'licencia.categoria.required_with' => 'La categoría de licencia es obligatoria.',
            'licencia.fecha_expedicion.required_with' => 'La fecha de expedición es obligatoria.',
            'licencia.fecha_vencimiento.required_with' => 'La fecha de vencimiento es obligatoria.',
            'licencia.estado.required_with' => 'El estado de la licencia es obligatorio.',
            'licencia.estado.in' => 'El estado debe ser: VIGENTE, VENCIDA o SUSPENDIDA.',
        ];
    }
}
