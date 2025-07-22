<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PropietarioRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');

        return [
            'identity_document_type_id' => 'required|string',
            'number' => [
                'required',
                Rule::unique('tenant.propietarios')->where(function ($query) use ($id) {
                    return $query->where('id', '<>', $id);
                })
            ],
            'name' => [
                'required',
                Rule::unique('tenant.propietarios')->where(function ($query) use ($id) {
                    return $query->where('id', '<>', $id);
                })
            ],
            'fecha_nacimiento' => 'nullable|date|before:today',
            'telephone_1' => 'nullable|string|max:20',
            'telephone_2' => 'nullable|string|max:20',
            'telephone_3' => 'nullable|string|max:20',
            'email' => [
                'nullable',
                'email',
                Rule::unique('tenant.propietarios')->where(function ($query) use ($id) {
                    return $query->where('id', '<>', $id);
                })
            ],
            'password' => 'nullable|string|min:6',
            'location_id' => 'required',
            'country_id' => [
                'required',
            ],
            'department_id' => 'nullable|string|max:2',
            'province_id' => 'nullable|string|max:4',
            'district_id' => 'nullable|string|max:6',
            'address' => 'required|string|max:255',
            'enabled' => 'required|boolean',
        ];
    }
}
