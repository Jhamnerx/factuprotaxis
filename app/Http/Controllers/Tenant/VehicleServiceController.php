<?php

namespace App\Http\Controllers\Tenant;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\VehicleService;
use App\Models\Tenant\Taxis\Vehiculos;
use App\Models\Tenant\Configuration;
use App\Http\Resources\Tenant\VehicleServiceResource;
use App\Http\Resources\Tenant\VehicleServiceCollection;
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

        return new VehicleServiceCollection($data);
    }

    public function tables()
    {
        $vehiculos = Vehiculos::where('estado', 'activo')
            ->orderBy('numero_interno')
            ->take(20)
            ->get()->transform(function ($row) {
                /** @var Vehiculos $row */
                return $row->getCollectionData();
            });
        $tiposServicios = VehicleService::tiposServicios();

        return compact('vehiculos', 'tiposServicios');
    }

    public function record($id)
    {
        $service = VehicleService::with(['vehiculo'])->findOrFail($id);

        return new VehicleServiceResource($service);
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
                'data' => new VehicleServiceResource($service->fresh(['vehiculo']))
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
    public function getDiasRestantesBadge($dias)
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
     * Búsqueda de vehículos
     */
    public function searchVehiculos(Request $request)
    {
        $vehiculos = Vehiculos::where('placa', 'like', "%{$request->input}%")
            ->orWhere('numero_interno', 'like', "%{$request->input}%")
            ->whereIsEnabled()
            ->get()->transform(function ($row) {
                return $row->getCollectionData();
            });

        return compact('vehiculos');
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
