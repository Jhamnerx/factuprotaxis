<?php

namespace App\Jobs\Tenant;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Modules\WhatsAppApi\Services\WhatsAppService;
use App\Models\Tenant\PlantillaMensaje;
use App\Models\Tenant\Taxis\Propietarios;
use App\Models\Tenant\Taxis\Conductor;
use App\Models\Tenant\Personal;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendBirthdayMessagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        try {
            $today = Carbon::today();
            $whatsappService = new WhatsAppService();

            // Enviar mensajes a propietarios
            $this->sendBirthdayMessagesToOwners($today, $whatsappService);

            // Enviar mensajes a conductores
            $this->sendBirthdayMessagesToDrivers($today, $whatsappService);

            // Enviar mensajes a personal
            $this->sendBirthdayMessagesToStaff($today, $whatsappService);

            Log::info('Job de cumpleaños completado exitosamente');
        } catch (\Exception $e) {
            Log::error("Error en SendBirthdayMessagesJob", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
        }
    }

    /**
     * Enviar mensajes de cumpleaños a propietarios
     */
    private function sendBirthdayMessagesToOwners($today, $whatsappService)
    {
        $plantilla = PlantillaMensaje::obtenerPorTipo('cumpleanos_propietario');
        if (!$plantilla) {
            Log::warning('No se encontró plantilla de cumpleaños para propietarios');
            return;
        }

        $propietarios = Propietarios::whereRaw(
            'DAY(fecha_nacimiento) = ? AND MONTH(fecha_nacimiento) = ?',
            [$today->day, $today->month]
        )
            ->where('enabled', true)
            ->get();

        foreach ($propietarios as $propietario) {
            $this->sendBirthdayMessage($propietario, $plantilla, $whatsappService, 'propietario');
        }

        Log::info("Mensajes de cumpleaños enviados a propietarios", ['count' => $propietarios->count()]);
    }

    /**
     * Enviar mensajes de cumpleaños a conductores
     */
    private function sendBirthdayMessagesToDrivers($today, $whatsappService)
    {
        $plantilla = PlantillaMensaje::obtenerPorTipo('cumpleanos_conductor');
        if (!$plantilla) {
            Log::warning('No se encontró plantilla de cumpleaños para conductores');
            return;
        }

        $conductores = Conductor::whereRaw(
            'DAY(fecha_nacimiento) = ? AND MONTH(fecha_nacimiento) = ?',
            [$today->day, $today->month]
        )
            ->where('enabled', true)
            ->get();

        foreach ($conductores as $conductor) {
            $this->sendBirthdayMessage($conductor, $plantilla, $whatsappService, 'conductor');
        }

        Log::info("Mensajes de cumpleaños enviados a conductores", ['count' => $conductores->count()]);
    }

    /**
     * Enviar mensajes de cumpleaños a personal
     */
    private function sendBirthdayMessagesToStaff($today, $whatsappService)
    {
        $plantilla = PlantillaMensaje::obtenerPorTipo('cumpleanos_personal');
        if (!$plantilla) {
            Log::warning('No se encontró plantilla de cumpleaños para personal');
            return;
        }

        // Verificar si existe el modelo Personal
        if (class_exists('App\Models\Tenant\Personal')) {
            $personal = Personal::whereRaw(
                'DAY(fecha_nacimiento) = ? AND MONTH(fecha_nacimiento) = ?',
                [$today->day, $today->month]
            )
                ->where('enabled', true)
                ->get();

            foreach ($personal as $empleado) {
                $this->sendBirthdayMessage($empleado, $plantilla, $whatsappService, 'personal');
            }

            Log::info("Mensajes de cumpleaños enviados a personal", ['count' => $personal->count()]);
        } else {
            Log::info('Modelo Personal no existe, saltando envío de mensajes a personal');
        }
    }

    /**
     * Enviar mensaje de cumpleaños individual
     */
    private function sendBirthdayMessage($person, $plantilla, $whatsappService, $type)
    {
        try {
            // Calcular edad
            $fechaNacimiento = Carbon::parse($person->fecha_nacimiento);
            $edad = $fechaNacimiento->age;

            // Preparar variables para la plantilla
            $variables = [
                'nombre' => $person->name,
                'edad' => $edad,
                'fecha' => Carbon::today()->format('d/m/Y')
            ];

            // Procesar mensaje
            $mensaje = $plantilla->procesarContenido($variables);

            // Obtener teléfono
            $telefono = $person->telephone_1 ?? $person->telephone ?? null;

            if (!$telefono) {
                Log::warning("No se encontró teléfono para cumpleaños", [
                    'type' => $type,
                    'name' => $person->name,
                    'id' => $person->id
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
                Log::info("Mensaje de cumpleaños enviado exitosamente", [
                    'type' => $type,
                    'name' => $person->name,
                    'phone' => $telefono
                ]);
            } else {
                Log::error("Error al enviar mensaje de cumpleaños", [
                    'type' => $type,
                    'name' => $person->name,
                    'phone' => $telefono,
                    'error' => $result['message']
                ]);
            }
        } catch (\Exception $e) {
            Log::error("Error al enviar mensaje de cumpleaños individual", [
                'type' => $type,
                'name' => $person->name ?? 'Sin nombre',
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
