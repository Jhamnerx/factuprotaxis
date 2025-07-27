<?php

namespace App\Jobs\Tenant;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use App\Models\Tenant\VehicleService;
use Hyn\Tenancy\Queue\TenantAwareJob;
use Illuminate\Queue\SerializesModels;
use App\Models\Tenant\PlantillaMensaje;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\WhatsAppApi\Services\WhatsAppService;

class CheckVehicleServicesExpirationJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, TenantAwareJob;

    protected $diasAnticipacion;

    /**
     * Create a new job instance.
     *
     * @param int $diasAnticipacion Días de anticipación para avisar
     */
    public function __construct($diasAnticipacion = 30)
    {
        $this->diasAnticipacion = $diasAnticipacion;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $whatsappService = new WhatsAppService();

            // Verificar servicios próximos a vencer
            $this->checkSOATExpiration($whatsappService);
            $this->checkTechnicalInspectionExpiration($whatsappService);

            // Marcar servicios vencidos
            $this->markExpiredServices();

            Log::info('Job de verificación de servicios vehiculares completado');
        } catch (\Exception $e) {

            Log::error("Error en CheckVehicleServicesExpirationJob", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Verificar vencimiento de SOAT
     */
    private function checkSOATExpiration($whatsappService)
    {
        $plantilla = PlantillaMensaje::obtenerPorTipo('vencimiento_soat');

        if (!$plantilla) {
            Log::warning('No se encontró plantilla de vencimiento SOAT');
            return;
        }

        $servicios = VehicleService::with(['vehiculo.propietario'])
            ->porTipo('SOAT')
            ->proximosAVencer($this->diasAnticipacion)
            ->where('event_sent', false)
            ->get();

        foreach ($servicios as $servicio) {
            $this->sendServiceExpirationMessage($servicio, $plantilla, $whatsappService, 'SOAT');
        }

        Log::info("Mensajes de vencimiento SOAT procesados", ['count' => $servicios->count()]);
    }

    /**
     * Verificar vencimiento de Revisión Técnica
     */
    private function checkTechnicalInspectionExpiration($whatsappService)
    {
        $plantilla = PlantillaMensaje::obtenerPorTipo('vencimiento_revision_tecnica');
        if (!$plantilla) {
            Log::warning('No se encontró plantilla de vencimiento revisión técnica');
            return;
        }

        $servicios = VehicleService::with(['vehiculo.propietario'])
            ->porTipo('REVISION_TECNICA')
            ->proximosAVencer($this->diasAnticipacion)
            ->where('event_sent', false)
            ->get();

        foreach ($servicios as $servicio) {
            $this->sendServiceExpirationMessage($servicio, $plantilla, $whatsappService, 'REVISION_TECNICA');
        }

        Log::info("Mensajes de vencimiento revisión técnica procesados", ['count' => $servicios->count()]);
    }

    /**
     * Marcar servicios vencidos
     */
    private function markExpiredServices()
    {
        $serviciosVencidos = VehicleService::vencidos()->get();

        foreach ($serviciosVencidos as $servicio) {
            $servicio->marcarVencido();
        }

        Log::info("Servicios marcados como vencidos", ['count' => $serviciosVencidos->count()]);
    }

    /**
     * Enviar mensaje de vencimiento de servicio
     */
    private function sendServiceExpirationMessage($servicio, $plantilla, $whatsappService, $tipoServicio)
    {
        try {
            $fechaVencimiento = Carbon::parse($servicio->expires_date);
            $diasRestantes = Carbon::now()->diffInDays($fechaVencimiento, false);
            $vehiculo = $servicio->vehiculo;
            $propietario = $vehiculo->propietario ?? null;

            if (!$vehiculo) {
                Log::warning("Servicio sin vehículo asociado", ['servicio_id' => $servicio->id]);
                return;
            }

            // Preparar variables para la plantilla
            $variables = [
                'nombre' => $propietario->name ?? 'Propietario',
                'placa' => $vehiculo->placa ?? 'Sin placa',
                'fecha_vencimiento' => $fechaVencimiento->format('d/m/Y'),
                'dias_restantes' => $diasRestantes,
                'tipo_servicio' => $tipoServicio,
                'numero_interno' => $vehiculo->numero_interno ?? ''
            ];

            // Procesar mensaje
            $mensaje = $plantilla->procesarContenido($variables);

            // Enviar a propietario
            if ($propietario && ($propietario->telephone_1 || $propietario->telephone)) {
                $telefonoPropietario = $propietario->telephone_1 ?? $propietario->telephone;
                $this->sendWhatsAppMessage($whatsappService, $telefonoPropietario, $mensaje, $servicio, 'propietario');
            }

            // Enviar también al teléfono del servicio si está configurado
            if ($servicio->mobile_phone) {
                $this->sendWhatsAppMessage($whatsappService, $servicio->mobile_phone, $mensaje, $servicio, 'servicio');
            }

            // Marcar como evento enviado
            $servicio->marcarEventoEnviado();
        } catch (\Exception $e) {
            Log::error("Error al enviar mensaje de vencimiento de servicio", [
                'servicio_id' => $servicio->id,
                'tipo_servicio' => $tipoServicio,
                'error' => $e->getMessage()
            ]);
        }
    }

    /**
     * Enviar mensaje por WhatsApp
     */
    private function sendWhatsAppMessage($whatsappService, $telefono, $mensaje, $servicio, $destinatario)
    {
        $result = $whatsappService->sendMessage([
            'phone_number' => $this->cleanPhoneNumber($telefono),
            'message' => $mensaje,
            'prefix_number' => '51'
        ]);

        if ($result['success']) {
            Log::info("Mensaje de vencimiento de servicio enviado", [
                'servicio_id' => $servicio->id,
                'servicio_tipo' => $servicio->name,
                'destinatario' => $destinatario,
                'telefono' => $telefono,
                'placa' => $servicio->vehiculo->placa ?? 'Sin placa'
            ]);
        } else {
            Log::error("Error al enviar mensaje de vencimiento de servicio", [
                'servicio_id' => $servicio->id,
                'servicio_tipo' => $servicio->name,
                'destinatario' => $destinatario,
                'telefono' => $telefono,
                'error' => $result['message']
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
