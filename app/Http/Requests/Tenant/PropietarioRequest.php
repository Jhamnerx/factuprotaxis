<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class PropietarioRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'identity_document_type_id' => 'required|string',
            'number' => 'required|string|max:11',
            'name' => 'required|string|max:255',
            'telephone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'country_id' => 'required|string|max:2',
            'department_id' => 'nullable|string|max:2',
            'province_id' => 'nullable|string|max:4',
            'district_id' => 'nullable|string|max:6',
            'address' => 'nullable|string|max:255',
            'enabled' => 'required|boolean',
        ];
    }
}
