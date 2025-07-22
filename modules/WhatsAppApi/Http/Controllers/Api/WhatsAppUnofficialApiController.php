<?php

namespace Modules\WhatsAppApi\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\WhatsAppApi\Services\WhatsAppUnofficialApi;
use Modules\WhatsAppApi\Http\Requests\Api\SendMessageUnofficialRequest;
use Modules\WhatsAppApi\Http\Requests\Api\SendMediaUnofficialRequest;


class WhatsAppUnofficialApiController extends Controller
{

    /**
     * Enviar mensaje de texto
     *
     * @param  SendMessageUnofficialRequest $request
     * @return array
     */
    public function sendMessage(SendMessageUnofficialRequest $request)
    {
        return (new WhatsAppUnofficialApi())->sendMessage($request->all());
    }


    /**
     * Enviar archivo multimedia
     *
     * @param  SendMediaUnofficialRequest $request
     * @return array
     */
    public function sendMedia(SendMediaUnofficialRequest $request)
    {
        return (new WhatsAppUnofficialApi())->sendMedia($request->all());
    }
}
