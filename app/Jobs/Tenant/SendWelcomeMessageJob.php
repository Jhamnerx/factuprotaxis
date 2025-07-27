<?php

namespace App\Jobs\Tenant;

use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Hyn\Tenancy\Queue\TenantAwareJob;
use Illuminate\Queue\SerializesModels;
use App\Models\Tenant\PlantillaMensaje;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\WhatsAppApi\Services\WhatsAppService;

class SendWelcomeMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, TenantAwareJob;

    protected $userType;
    protected $userData;
    protected $vehicleData;

    /**
     * Create a new job instance.
     *
     * @param string $userType (propietario, conductor, personal)
     * @param array $userData
     * @param array $vehicleData (opcional, para conductores)
     */
    public function __construct($userType, $userData, $vehicleData = null)
    {
        $this->userType = $userType;
        $this->userData = $userData;
        $this->vehicleData = $vehicleData;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            // Obtener la plantilla de bienvenida
            $plantilla = PlantillaMensaje::obtenerPorTipo('bienvenida');

            if (!$plantilla) {
                Log::warning('No se encontró plantilla de bienvenida');
                return;
            }

            // Preparar variables para reemplazar en la plantilla
            $variables = [
                'nombre' => $this->userData['name'] ?? 'Usuario',
                'celular' => $this->userData['telephone_1'] ?? $this->userData['telephone'] ?? '',
                'fecha' => now()->format('d/m/Y'),
                'hora' => now()->format('H:i')
            ];

            // Agregar datos del vehículo si es conductor
            if ($this->userType === 'conductor' && $this->vehicleData) {
                $variables['flota'] = $this->vehicleData['numero_interno'] ?? '';
                $variables['placa'] = $this->vehicleData['placa'] ?? '';
            } else {
                $variables['flota'] = '';
                $variables['placa'] = '';
            }

            // Procesar el contenido de la plantilla
            $mensaje = $plantilla->procesarContenido($variables);

            // Obtener teléfono para envío
            $telefono = $this->userData['telephone_1'] ?? $this->userData['telephone'] ?? null;

            if (!$telefono) {
                Log::warning("No se encontró teléfono para enviar mensaje de bienvenida", [
                    'user_type' => $this->userType,
                    'user_data' => $this->userData
                ]);
                return;
            }

            // Enviar mensaje por WhatsApp
            $whatsappService = new WhatsAppService();
            $result = $whatsappService->sendMessage([
                'phone_number' => $this->cleanPhoneNumber($telefono),
                'message' => $mensaje,
                'prefix_number' => '51'
            ]);

            if ($result['success']) {
                Log::info("Mensaje de bienvenida enviado exitosamente", [
                    'user_type' => $this->userType,
                    'phone' => $telefono,
                    'name' => $this->userData['name'] ?? 'Sin nombre'
                ]);
            } else {
                Log::error("Error al enviar mensaje de bienvenida", [
                    'user_type' => $this->userType,
                    'phone' => $telefono,
                    'error' => $result['message']
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Excepción en SendWelcomeMessageJob", [
                'user_type' => $this->userType,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Limpiar número de teléfono
     */
    private function cleanPhoneNumber($phone)
    {
        // Remover espacios, guiones y caracteres especiales
        $phone = preg_replace('/[^0-9]/', '', $phone);

        // Si empieza con 51, removerlo
        if (substr($phone, 0, 2) === '51') {
            $phone = substr($phone, 2);
        }

        // Si empieza con +51, removerlo
        if (substr($phone, 0, 3) === '+51') {
            $phone = substr($phone, 3);
        }

        return $phone;
    }
}
