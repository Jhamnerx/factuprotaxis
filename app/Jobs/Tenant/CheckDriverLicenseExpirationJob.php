<?php

namespace App\Jobs\Tenant;

use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Support\Facades\Log;
use Hyn\Tenancy\Queue\TenantAwareJob;
use App\Models\Tenant\Taxis\Conductor;
use Illuminate\Queue\SerializesModels;
use App\Models\Tenant\PlantillaMensaje;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\WhatsAppApi\Services\WhatsAppService;

class CheckDriverLicenseExpirationJob implements ShouldQueue
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
            $plantilla = PlantillaMensaje::obtenerPorTipo('vencimiento_licencia_conductor');

            if (!$plantilla) {
                Log::warning('No se encontró plantilla de vencimiento de licencia');
                return;
            }

            // Fecha límite para avisar
            $fechaLimite = Carbon::now()->addDays($this->diasAnticipacion);

            // Buscar conductores con licencia próxima a vencer
            $conductores = Conductor::where('enabled', true)
                ->whereNotNull('fecha_vencimiento_licencia')
                ->where('fecha_vencimiento_licencia', '<=', $fechaLimite)
                ->where('fecha_vencimiento_licencia', '>=', Carbon::now())
                ->get();

            $mensajesEnviados = 0;

            foreach ($conductores as $conductor) {
                $this->sendLicenseExpirationMessage($conductor, $plantilla, $whatsappService);
                $mensajesEnviados++;
            }

            Log::info('Job de verificación de licencias completado', [
                'conductores_encontrados' => $conductores->count(),
                'mensajes_enviados' => $mensajesEnviados,
                'dias_anticipacion' => $this->diasAnticipacion
            ]);
        } catch (\Exception $e) {
            Log::error("Error en CheckDriverLicenseExpirationJob", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Enviar mensaje de vencimiento de licencia
     */
    private function sendLicenseExpirationMessage($conductor, $plantilla, $whatsappService)
    {
        try {
            $fechaVencimiento = Carbon::parse($conductor->fecha_vencimiento_licencia);
            $diasRestantes = Carbon::now()->diffInDays($fechaVencimiento, false);

            // Preparar variables para la plantilla
            $variables = [
                'nombre' => $conductor->name,
                'fecha_vencimiento' => $fechaVencimiento->format('d/m/Y'),
                'dias_restantes' => $diasRestantes,
                'licencia' => $conductor->licencia ?? 'Sin número'
            ];

            // Procesar mensaje
            $mensaje = $plantilla->procesarContenido($variables);

            // Obtener teléfono
            $telefono = $conductor->telephone_1 ?? $conductor->telephone ?? null;

            if (!$telefono) {
                Log::warning("No se encontró teléfono para conductor", [
                    'conductor_id' => $conductor->id,
                    'nombre' => $conductor->name
                ]);
                return;
            }

            // Enviar mensaje
            $result = $whatsappService->sendMessage([
                'phone_number' => $this->cleanPhoneNumber($telefono),
                'message' => $mensaje,
                'prefix_number' => '51'
            ]);

            if ($result['success']) {
                Log::info("Mensaje de vencimiento de licencia enviado", [
                    'conductor_id' => $conductor->id,
                    'nombre' => $conductor->name,
                    'fecha_vencimiento' => $fechaVencimiento->format('d/m/Y'),
                    'dias_restantes' => $diasRestantes
                ]);
            } else {
                Log::error("Error al enviar mensaje de vencimiento de licencia", [
                    'conductor_id' => $conductor->id,
                    'nombre' => $conductor->name,
                    'error' => $result['message']
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Error al enviar mensaje de vencimiento de licencia individual", [
                'conductor_id' => $conductor->id,
                'nombre' => $conductor->name ?? 'Sin nombre',
                'error' => $e->getMessage()
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
