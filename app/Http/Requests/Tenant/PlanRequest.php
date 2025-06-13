<?php

namespace App\Http\Requests\Tenant;

use Illuminate\Foundation\Http\FormRequest;

class PlanRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $id = $this->route('plan');
        return [
            'name'                    => 'required|string|max:255',
            'description'             => 'nullable|string',
            'price'                   => 'required|numeric|min:0',
            'slug'                    => 'nullable|string|max:255|unique:plans,slug,' . $id,
            'signup_fee'              => 'required|numeric|min:0',
            'currency'                => 'required|string',
            'invoice_period'          => 'required|integer|min:1',
            'invoice_interval'        => 'required|string',
            'active_subscribers_limit' => 'nullable|integer|min:0',
            'sort_order'              => 'required|integer|min:0|unique:plans,sort_order,' . $id,
            'features'                => 'array',
            'features.*.name'         => 'required|string|max:255',
            'features.*.value'        => 'nullable|numeric',
            'discounts'               => 'array',
            'discounts.*.name'        => 'required|string|max:255',
            'discounts.*.value'       => 'nullable|numeric|min:0',
        ];
    }
}
