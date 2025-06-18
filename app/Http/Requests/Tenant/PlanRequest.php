<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class PlanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->input('id');
        return [
            'name'                    => 'required|string|max:255',
            'description'             => 'nullable|string',
            'price'                   => 'required|numeric|min:0',
            'slug'                    => [
                'required',
                Rule::unique('tenant.plans')->where(function ($query) use ($id) {
                    return $query->where('id', '<>', $id);
                })
            ],
            'signup_fee'              => 'required|numeric|min:0',
            'currency'                => 'required|string',
            'invoice_period'          => 'required|integer|min:1',
            'invoice_interval'        => 'required|string',
            'active_subscribers_limit' => 'nullable|integer|min:0',
            'features'                => 'array',
            'features.*.name'         => 'required|string|max:255',
            'features.*.value'        => 'nullable|numeric',
            'discounts'               => 'array',
            'discounts.*.name'        => 'required|string|max:255',
            'discounts.*.value'       => 'nullable|numeric|min:0',
        ];
    }
}
