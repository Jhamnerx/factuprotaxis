<?php

namespace Modules\WhatsAppApi\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;


class SendMessageUnofficialRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'phone_number' => [
                'required',
                'string',
            ],
            'message' => [
                'required',
                'string',
            ],
            'prefix_number' => [
                'nullable',
                'string',
            ]
        ];
    }

    public function messages()
    {
        return [
            'phone_number.required' => 'El número de teléfono es obligatorio',
            'phone_number.string' => 'El número de teléfono debe ser una cadena de texto',
            'message.required' => 'El mensaje es obligatorio',
            'message.string' => 'El mensaje debe ser una cadena de texto',
            'prefix_number.string' => 'El código de país debe ser una cadena de texto',
        ];
    }
}
