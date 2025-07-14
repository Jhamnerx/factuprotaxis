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
            'number' => 'required|string|min:3|max:20|unique:conductores,number,' . $id,
            'licencias' => 'nullable|array',
            'licencias.*.numero' => 'required_with:licencias|string|max:50',
            'licencias.*.categoria' => 'required_with:licencias|string|max:50',
            'licencias.*.fecha_expedicion' => 'required_with:licencias|date_format:d/m/Y',
            'licencias.*.fecha_vencimiento' => 'required_with:licencias|date_format:d/m/Y|after:licencias.*.fecha_expedicion',
            'licencias.*.estado' => 'required_with:licencias|string|in:VIGENTE,VENCIDA,SUSPENDIDA',
            'licencias.*.restricciones' => 'nullable|string|max:255',
            'address' => 'nullable|string|max:255',
            'telephone_1' => 'nullable|string|max:20',
            'telephone_2' => 'nullable|string|max:20',
            'telephone_3' => 'nullable|string|max:20',
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
            'licencias' => 'licencias',
            'licencias.*.numero' => 'número de licencia',
            'licencias.*.categoria' => 'categoría de licencia',
            'licencias.*.fecha_expedicion' => 'fecha de expedición',
            'licencias.*.fecha_vencimiento' => 'fecha de vencimiento',
            'licencias.*.estado' => 'estado de licencia',
            'licencias.*.restricciones' => 'restricciones',
            'address' => 'dirección',
            'telephone_1' => 'teléfono 1',
            'telephone_2' => 'teléfono 2',
            'telephone_3' => 'teléfono 3',
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
            'number.min' => 'El :attribute debe tener al menos 3 caracteres.',
            'number.max' => 'El :attribute no puede tener más de 20 caracteres.',
            'number.unique' => 'El :attribute ya está en uso.',
            'licencias.*.numero.required_with' => 'El número de licencia es obligatorio.',
            'licencias.*.categoria.required_with' => 'La categoría de licencia es obligatoria.',
            'licencias.*.fecha_expedicion.required_with' => 'La fecha de expedición es obligatoria.',
            'licencias.*.fecha_expedicion.date_format' => 'La fecha de expedición debe tener el formato dd/mm/yyyy.',
            'licencias.*.fecha_vencimiento.required_with' => 'La fecha de vencimiento es obligatoria.',
            'licencias.*.fecha_vencimiento.date_format' => 'La fecha de vencimiento debe tener el formato dd/mm/yyyy.',
            'licencias.*.fecha_vencimiento.after' => 'La fecha de vencimiento debe ser posterior a la fecha de expedición.',
            'licencias.*.estado.required_with' => 'El estado de la licencia es obligatorio.',
            'licencias.*.estado.in' => 'El estado debe ser: VIGENTE, VENCIDA o SUSPENDIDA.',
        ];
    }
}
