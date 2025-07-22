<?php

namespace Modules\WhatsAppApi\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Http\Controllers\Controller;
use Modules\WhatsAppApi\Services\WhatsAppService;
use Modules\WhatsAppApi\Http\Requests\Api\SendMessageUnofficialRequest;
use Modules\WhatsAppApi\Http\Requests\Api\SendMediaUnofficialRequest;


class WhatsAppServiceController extends Controller
{

    /**
     * Enviar mensaje (API inteligente - usa la mejor disponible)
     *
     * @param  SendMessageUnofficialRequest $request
     * @return array
     */
    public function sendMessage(SendMessageUnofficialRequest $request)
    {
        return (new WhatsAppService())->sendMessage($request->all());
    }


    /**
     * Enviar multimedia
     *
     * @param  SendMediaUnofficialRequest $request
     * @return array
     */
    public function sendMedia(SendMediaUnofficialRequest $request)
    {
        return (new WhatsAppService())->sendMedia($request->all());
    }


    /**
     * Enviar mensaje con API específica
     *
     * @param  Request $request
     * @return array
     */
    public function sendMessageWithApi(Request $request)
    {
        $request->validate([
            'api_type' => 'required|in:official,unofficial',
            'phone_number' => 'required|string',
            'message' => 'required|string',
            'prefix_number' => 'nullable|string'
        ]);

        return (new WhatsAppService())->sendMessageWithApi($request->all(), $request->api_type);
    }


    /**
     * Obtener estado de configuración
     *
     * @return array
     */
    public function getConfigStatus()
    {
        return (new WhatsAppService())->getConfigStatus();
    }
}
