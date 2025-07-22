<?php

namespace Modules\WhatsAppApi\Services;

use Modules\WhatsAppApi\Services\WhatsAppCloudApi;
use Modules\WhatsAppApi\Services\WhatsAppUnofficialApi;
use App\Models\Tenant\Company;
use Exception;


class WhatsAppService
{
    protected $official_api;
    protected $unofficial_api;
    protected $company;

    public function __construct()
    {
        $this->company = Company::selectDataWhatsAppApi()->first();
        $this->official_api = new WhatsAppCloudApi();
        $this->unofficial_api = new WhatsAppUnofficialApi();
    }

    /**
     * Enviar mensaje de texto (prioriza API no oficial)
     *
     * @param  array $params_data
     * @return array
     */
    public function sendMessage($params_data)
    {
        // Intentar primero con API no oficial
        if ($this->hasUnofficialApiConfig()) {
            $result = $this->unofficial_api->sendMessage($params_data);
            if ($result['success']) {
                return $result;
            }
        }

        // Si falla o no está configurada, usar API oficial
        if ($this->hasOfficialApiConfig()) {
            return $this->official_api->sendMessage($params_data);
        }

        return [
            'success' => false,
            'message' => 'No hay configuración válida para ninguna API de WhatsApp'
        ];
    }

    /**
     * Enviar multimedia (solo API no oficial)
     *
     * @param  array $params_data
     * @return array
     */
    public function sendMedia($params_data)
    {
        if (!$this->hasUnofficialApiConfig()) {
            return [
                'success' => false,
                'message' => 'La API no oficial de WhatsApp no está configurada'
            ];
        }

        return $this->unofficial_api->sendMedia($params_data);
    }

    /**
     * Verificar si tiene configuración de API oficial
     *
     * @return bool
     */
    public function hasOfficialApiConfig()
    {
        return !empty($this->company->ws_api_token) && !empty($this->company->ws_api_phone_number_id);
    }

    /**
     * Verificar si tiene configuración de API no oficial
     *
     * @return bool
     */
    public function hasUnofficialApiConfig()
    {
        return !empty($this->company->ws_unofficial_api_key) &&
            !empty($this->company->ws_unofficial_sender) &&
            !empty($this->company->ws_unofficial_url);
    }

    /**
     * Obtener estado de configuración de APIs
     *
     * @return array
     */
    public function getConfigStatus()
    {
        return [
            'official_configured' => $this->hasOfficialApiConfig(),
            'unofficial_configured' => $this->hasUnofficialApiConfig(),
            'media_available' => $this->hasUnofficialApiConfig()
        ];
    }

    /**
     * Enviar mensaje usando API específica
     *
     * @param  array $params_data
     * @param  string $api_type ('official' | 'unofficial')
     * @return array
     */
    public function sendMessageWithApi($params_data, $api_type)
    {
        switch ($api_type) {
            case 'official':
                if (!$this->hasOfficialApiConfig()) {
                    return [
                        'success' => false,
                        'message' => 'La API oficial de WhatsApp no está configurada'
                    ];
                }
                return $this->official_api->sendMessage($params_data);

            case 'unofficial':
                if (!$this->hasUnofficialApiConfig()) {
                    return [
                        'success' => false,
                        'message' => 'La API no oficial de WhatsApp no está configurada'
                    ];
                }
                return $this->unofficial_api->sendMessage($params_data);

            default:
                return [
                    'success' => false,
                    'message' => 'Tipo de API no válido'
                ];
        }
    }
}
