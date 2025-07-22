<?php

namespace App\Http\Controllers\Tenant;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\VehicleService;
use App\Models\Tenant\Taxis\Vehiculos;
use App\Models\Tenant\Configuration;
use Carbon\Carbon;

class VehicleServiceController extends Controller
{
    public function index()
    {
        $configuration = Configuration::first();
        return view('tenant.taxis.vehicle_services.index', compact('configuration'));
    }

    public function columns()
    {
        return [
            'vehiculo_placa' => 'Placa',
            'name' => 'Tipo de Servicio',
            'expires_date' => 'Fecha de Vencimiento',
            'dias_restantes' => 'Días Restantes',
            'mobile_phone' => 'Teléfono',
            'event_sent' => 'Notificación Enviada',
            'expired' => 'Vencido'
        ];
    }

    public function records(Request $request)
    {
        $records = VehicleService::with(['vehiculo'])
            ->when($request->column && $request->value, function ($query) use ($request) {
                switch ($request->column) {
                    case 'vehiculo_placa':
                        return $query->whereHas('vehiculo', function ($q) use ($request) {
                            $q->where('placa', 'like', "%{$request->value}%");
                        });
                    case 'name':
                        return $query->where('name', 'like', "%{$request->value}%");
                    case 'expires_date':
                        return $query->whereDate('expires_date', $request->value);
                    default:
                        return $query->where($request->column, 'like', "%{$request->value}%");
                }
            })
            ->orderBy('expires_date', 'asc');

        $data = $records->paginate(config('tenant.items_per_page'));

        // Agregar información calculada
        $data->getCollection()->transform(function ($service) {
            $diasRestantes = $service->diasHastaVencimiento();

            return [
                'id' => $service->id,
                'vehiculo_placa' => $service->vehiculo->placa ?? 'Sin placa',
                'vehiculo_numero_interno' => $service->vehiculo->numero_interno ?? '',
                'name' => $service->name,
                'expires_date' => $service->expires_date ? $service->expires_date->format('d/m/Y') : '',
                'dias_restantes' => $diasRestantes,
                'dias_restantes_badge' => $this->getDiasRestantesBadge($diasRestantes),
                'mobile_phone' => $service->mobile_phone,
                'event_sent' => $service->event_sent ? 'Sí' : 'No',
                'event_sent_boolean' => $service->event_sent,
                'expired' => $service->expired ? 'Sí' : 'No',
                'expired_boolean' => $service->expired,
                'description' => $service->description,
                'propietario_nombre' => $service->nombre_propietario,
            ];
        });

        return response()->json([
            'data' => $data->items(),
            'pagination' => [
                'total' => $data->total(),
                'per_page' => $data->perPage(),
                'current_page' => $data->currentPage(),
                'last_page' => $data->lastPage(),
                'from' => $data->firstItem(),
                'to' => $data->lastItem(),
            ]
        ]);
    }

    public function tables()
    {
        $vehiculos = Vehiculos::where('estado', '!=', 'DE BAJA')
            ->with('propietario:id,name')
            ->orderBy('placa')
            ->get(['id', 'placa', 'numero_interno', 'propietario_id'])
            ->map(function ($vehiculo) {
                return [
                    'id' => $vehiculo->id,
                    'placa' => $vehiculo->placa,
                    'numero_interno' => $vehiculo->numero_interno,
                    'propietario' => $vehiculo->propietario->name ?? 'Sin propietario'
                ];
            });

        $tiposServicios = VehicleService::tiposServicios();

        return compact('vehiculos', 'tiposServicios');
    }

    public function record($id)
    {
        $service = VehicleService::with(['vehiculo'])->findOrFail($id);

        return [
            'id' => $service->id,
            'device_id' => $service->device_id,
            'name' => $service->name,
            'expires_date' => $service->expires_date ? $service->expires_date->format('Y-m-d') : '',
            'remind_date' => $service->remind_date ? $service->remind_date->format('Y-m-d') : '',
            'mobile_phone' => $service->mobile_phone,
            'email' => $service->email,
            'description' => $service->description,
            'vehiculo' => $service->vehiculo ? [
                'id' => $service->vehiculo->id,
                'placa' => $service->vehiculo->placa,
                'numero_interno' => $service->vehiculo->numero_interno
            ] : null,
        ];
    }

    public function store(Request $request)
    {
        $request->validate([
            'device_id' => 'required|exists:tenant.vehiculos,id',
            'name' => 'required|string|max:255',
            'expires_date' => 'required|date|after:today',
            'remind_date' => 'nullable|date|before:expires_date',
            'mobile_phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'description' => 'nullable|string'
        ]);

        try {
            $id = $request->input('id');
            $service = VehicleService::firstOrNew(['id' => $id]);
            $data = $request->all();
            unset($data['id']);

            $data['user_id'] = auth()->user()->id;
            $data['expires_date'] = Carbon::parse($data['expires_date']);

            if ($data['remind_date']) {
                $data['remind_date'] = Carbon::parse($data['remind_date']);
            }

            // Resetear flags si se está editando y cambiando la fecha
            if ($id && $service->expires_date != $data['expires_date']) {
                $data['event_sent'] = false;
                $data['expired'] = false;
            }

            $service->fill($data);
            $service->save();

            $msg = ($id) ? 'Servicio editado con éxito' : 'Servicio registrado con éxito';

            return [
                'success' => true,
                'message' => $msg,
                'id' => $service->id
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error inesperado, no se pudo guardar el servicio: ' . $e->getMessage()
            ];
        }
    }

    public function destroy($id)
    {
        try {
            $service = VehicleService::findOrFail($id);
            $service->delete();

            return [
                'success' => true,
                'message' => 'Servicio eliminado con éxito'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error inesperado, no se pudo eliminar el servicio'
            ];
        }
    }

    /**
     * Obtener badge para días restantes
     */
    private function getDiasRestantesBadge($dias)
    {
        if ($dias === null) return ['class' => 'secondary', 'text' => 'Sin fecha'];
        if ($dias < 0) return ['class' => 'danger', 'text' => 'Vencido'];
        if ($dias <= 7) return ['class' => 'danger', 'text' => $dias . ' días'];
        if ($dias <= 30) return ['class' => 'warning', 'text' => $dias . ' días'];
        return ['class' => 'success', 'text' => $dias . ' días'];
    }

    /**
     * Obtener resumen de servicios
     */
    public function dashboard()
    {
        $proximosAVencer = VehicleService::proximosAVencer(30)->count();
        $vencidos = VehicleService::vencidos()->count();
        $totalServicios = VehicleService::count();

        $serviciosPorTipo = VehicleService::selectRaw('name, COUNT(*) as count')
            ->groupBy('name')
            ->get();

        return [
            'proximos_a_vencer' => $proximosAVencer,
            'vencidos' => $vencidos,
            'total_servicios' => $totalServicios,
            'servicios_por_tipo' => $serviciosPorTipo
        ];
    }

    /**
     * Enviar notificación manual
     */
    public function sendNotification(Request $request, $id)
    {
        try {
            $service = VehicleService::findOrFail($id);

            // Aquí puedes disparar el job específico para este servicio
            // Por ahora solo marcamos como enviado
            $service->marcarEventoEnviado();

            return [
                'success' => true,
                'message' => 'Notificación enviada correctamente'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al enviar la notificación: ' . $e->getMessage()
            ];
        }
    }
}
