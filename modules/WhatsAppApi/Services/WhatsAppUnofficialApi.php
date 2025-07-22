<?php

namespace Modules\WhatsAppApi\Services;

use Modules\WhatsAppApi\Helpers\HttpConnectionApi;
use App\Models\Tenant\{
    Company
};
use Exception;


class WhatsAppUnofficialApi
{
    protected $api_key;
    protected $sender;
    protected $base_url;
    protected $http_connection;


    /**
     * @return void
     */
    public function __construct()
    {
        $company = Company::selectDataWhatsAppApi()->first();
        $this->api_key = $company->ws_unofficial_api_key ?? null;
        $this->sender = $company->ws_unofficial_sender ?? null;
        $this->base_url = $company->ws_unofficial_url ?? 'https://messages.zoftwaresolutions.pro';
        $this->http_connection = new HttpConnectionApi($this->api_key);
    }


    /**
     * Enviar mensaje de texto
     *
     * @param  array $params_data
     * @return array
     */
    public function sendMessage($params_data)
    {
        $validate_data = $this->validateData();
        if (!$validate_data['success']) return $validate_data;

        try {
            $params = $this->getMessageParams($params_data);
            $url = $this->base_url . '/send-message';

            $response = $this->http_connection->sendRequestUnofficial($url, $params, 'POST');

            return $this->processResponse($response);
        } catch (Exception $e) {
            return $this->http_connection->responseError($e);
        }
    }


    /**
     * Enviar archivo multimedia
     *
     * @param  array $params_data
     * @return array
     */
    public function sendMedia($params_data)
    {
        $validate_data = $this->validateData();
        if (!$validate_data['success']) return $validate_data;

        try {
            $params = $this->getMediaParams($params_data);
            $url = $this->base_url . '/send-media';

            $response = $this->http_connection->sendRequestUnofficial($url, $params, 'POST');

            return $this->processResponse($response);
        } catch (Exception $e) {
            return $this->http_connection->responseError($e);
        }
    }


    /**
     * Validar datos de configuración
     *
     * @return array
     */
    private function validateData()
    {
        if (!$this->api_key) {
            return [
                'success' => false,
                'message' => 'No tiene registrado correctamente la clave API no oficial',
            ];
        }

        if (!$this->sender) {
            return [
                'success' => false,
                'message' => 'No tiene registrado correctamente el número remitente',
            ];
        }

        if (!$this->base_url) {
            return [
                'success' => false,
                'message' => 'No tiene registrado correctamente la URL del servicio',
            ];
        }

        return [
            'success' => true,
            'message' => null,
        ];
    }


    /**
     * Procesar respuesta de la API
     *
     * @param  array $response
     * @return array
     */
    private function processResponse($response)
    {
        if (!isset($response['status'])) {
            return $this->http_connection->responseMessage(false, 'Respuesta inválida del servidor');
        }

        if ($response['status'] === true) {
            $message = $response['msg'] ?? 'Mensaje enviado correctamente';
            return $this->http_connection->responseMessage(true, $message);
        } else {
            $message = $response['msg'] ?? 'Error desconocido';
            $errors = $response['errors'] ?? null;

            if ($errors) {
                if (is_string($errors)) {
                    $message .= ': ' . $errors;
                } else if (is_array($errors)) {
                    $error_details = implode(', ', array_values($errors));
                    $message .= ': ' . $error_details;
                }
            }

            return $this->http_connection->responseMessage(false, $message);
        }
    }


    /**
     * Obtener parámetros para mensaje de texto
     *
     * @param  array $params
     * @return array
     */
    private function getMessageParams($params)
    {
        $prefix_number = $params['prefix_number'] ?? '51';
        $phone_number = $prefix_number . $params['phone_number'];

        return [
            'api_key' => $this->api_key,
            'sender' => $this->sender,
            'number' => $phone_number,
            'message' => $params['message']
        ];
    }


    /**
     * Obtener parámetros para envío de multimedia
     *
     * @param  array $params
     * @return array
     */
    private function getMediaParams($params)
    {
        $prefix_number = $params['prefix_number'] ?? '51';
        $phone_number = $prefix_number . $params['phone_number'];

        $data = [
            'api_key' => $this->api_key,
            'sender' => $this->sender,
            'number' => $phone_number,
            'media_type' => $params['media_type'],
            'url' => $params['url']
        ];

        // Añadir caption si es imagen o video
        if (in_array($params['media_type'], ['image', 'video']) && !empty($params['caption'])) {
            $data['caption'] = $params['caption'];
        }

        // Añadir ppt si es audio
        if ($params['media_type'] === 'audio' && isset($params['ppt'])) {
            $data['ppt'] = $params['ppt'];
        }

        return $data;
    }


    /**
     * Obtener tipos de media permitidos
     *
     * @return array
     */
    public static function getAllowedMediaTypes()
    {
        return ['image', 'video', 'audio', 'pdf', 'xls', 'xlsx', 'doc', 'docx', 'zip'];
    }
}
