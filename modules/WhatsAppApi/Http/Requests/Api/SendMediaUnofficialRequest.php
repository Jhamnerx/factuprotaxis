<?php

namespace Modules\WhatsAppApi\Http\Requests\Api;

use Illuminate\Foundation\Http\FormRequest;
use Modules\WhatsAppApi\Services\WhatsAppUnofficialApi;


class SendMediaUnofficialRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        $allowedMediaTypes = WhatsAppUnofficialApi::getAllowedMediaTypes();

        return [
            'phone_number' => [
                'required',
                'string',
            ],
            'media_type' => [
                'required',
                'string',
                'in:' . implode(',', $allowedMediaTypes),
            ],
            'url' => [
                'required',
                'url',
            ],
            'caption' => [
                'nullable',
                'string',
            ],
            'ppt' => [
                'nullable',
                'boolean',
            ],
            'prefix_number' => [
                'nullable',
                'string',
            ]
        ];
    }

    public function messages()
    {
        $allowedMediaTypes = WhatsAppUnofficialApi::getAllowedMediaTypes();

        return [
            'phone_number.required' => 'El número de teléfono es obligatorio',
            'phone_number.string' => 'El número de teléfono debe ser una cadena de texto',
            'media_type.required' => 'El tipo de multimedia es obligatorio',
            'media_type.string' => 'El tipo de multimedia debe ser una cadena de texto',
            'media_type.in' => 'El tipo de multimedia debe ser uno de: ' . implode(', ', $allowedMediaTypes),
            'url.required' => 'La URL del archivo es obligatoria',
            'url.url' => 'La URL debe tener un formato válido',
            'caption.string' => 'El caption debe ser una cadena de texto',
            'ppt.boolean' => 'El campo ppt debe ser verdadero o falso',
            'prefix_number.string' => 'El código de país debe ser una cadena de texto',
        ];
    }
}
