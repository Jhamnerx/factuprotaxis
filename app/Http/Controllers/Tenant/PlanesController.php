<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Modules\Payment\Models\Plan;
use App\Http\Controllers\Controller;
use App\Http\Requests\Tenant\PlanRequest;
use App\Http\Resources\Tenant\PlanResource;
use App\Http\Resources\Tenant\PlanCollection;

class PlanesController extends Controller
{
    public function index()
    {
        return view('tenant.taxis.planes.index');
    }

    public function columns()
    {
        return [
            'name' => 'Nombre',
            'description' => 'Descripción',
            'invoice_interval' => 'Intervalo de Facturación',
            'is_socio' => 'Es Socio',

        ];
    }

    public function records(Request $request)
    {
        $records = Plan::query();

        if ($request->column == 'is_socio') {
            $valor = $request->value == 'si' ? 'true' : 'false';
            $records->where('is_socio', $valor);
        }

        if ($request->column == 'invoice_interval') {
            $records->where('invoice_interval', $request->value);
        }

        $records->where($request->column, 'like', "%{$request->value}%");

        return new PlanCollection($records->orderBy('sort_order')->paginate(config('tenant.items_per_page')));
    }

    public function record($id)
    {
        $record = Plan::findOrFail($id);
        return new PlanResource($record);
    }
    public function store(PlanRequest $request)
    {
        $data = $request->all();

        // Determinar el tipo de plan según invoice_interval
        $data['type'] = ($data['invoice_interval'] ?? null) === 'indeterminate' ? 'indeterminate' : 'recurring';

        // Asegurar que is_socio sea un booleano
        $data['is_socio'] = filter_var($data['is_socio'] ?? false, FILTER_VALIDATE_BOOLEAN);

        // Para planes socio, siempre establecer el intervalo anual
        if ($data['is_socio']) {
            $data['invoice_interval'] = 'year';
            // No establecemos un período fijo para planes socio, ya que se manejará a nivel de vehículo
        }

        // Crear o actualizar el plan
        $id = $request->input('id');
        $isUpdate = !empty($id);

        $plan = Plan::updateOrCreate(['id' => $id], $data);

        // // Si es una actualización, eliminar las características existentes
        // if ($isUpdate) {
        //     $plan->features()->delete();
        // }

        // // Manejar las características (features) solo si hay características definidas
        // // Para planes socio, ya no creamos la característica "Valido Hasta" automáticamente
        // if (!empty($request->features)) {
        //     foreach ($request->features as $feature) {
        //         if (!empty($feature['name']) && isset($feature['value'])) {
        //             $plan->features()->create([
        //                 'name' => $feature['name'],
        //                 'value' => $feature['value'],
        //                 'sort_order' => $feature['sort_order'] ?? 0,
        //                 'resettable_period' => $feature['resettable_period'] ?? null,
        //                 'resettable_interval' => $feature['resettable_interval'] ?? null,
        //                 'description' => $feature['description'] ?? null,
        //                 'slug' => Str::slug($feature['name']),
        //             ]);
        //         }
        //     }
        // }

        return [
            'success' => true,
            'message' => ($isUpdate ? 'Plan actualizado' : 'Plan registrado'),
            'id' => $plan->id
        ];
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();
        return [
            'success' => true,
            'message' => 'Plan eliminado'
        ];
    }

    public function lastSortOrder()
    {
        $last = Plan::orderByDesc('sort_order')->first();
        return [
            'last_sort_order' => $last ? $last->sort_order : 0
        ];
    }

    /**
     * Obtiene la información de suscripción de plan socio para un vehículo
     *
     * @param int $vehicleId
     * @return array
     */
    public function getSocioPlanInfo($vehicleId)
    {
        try {
            $vehiculo = \App\Models\Tenant\Taxis\Vehiculos::findOrFail($vehicleId);

            // Obtener la suscripción activa del vehículo
            $subscription = $vehiculo->activeSubscription();

            if (!$subscription) {
                return [
                    'success' => false,
                    'message' => 'El vehículo no tiene un plan activo'
                ];
            }

            $plan = $subscription->plan;

            if (!$plan->is_socio) {
                return [
                    'success' => false,
                    'message' => 'El vehículo no tiene un plan socio asignado'
                ];
            }

            // Obtener información de la suscripción
            $metadata = $subscription->metadata ?? [];
            $durationYears = $metadata['duration_years'] ?? null;
            $validUntilYear = $metadata['valid_until_year'] ?? null;

            // Calcular tiempo restante
            $endDate = $subscription->ends_at;
            $today = now();
            $remainingDays = $today->diffInDays($endDate, false);

            return [
                'success' => true,
                'subscription' => [
                    'id' => $subscription->id,
                    'plan_id' => $plan->id,
                    'plan_name' => $plan->name,
                    'start_date' => $subscription->starts_at->toDateString(),
                    'end_date' => $subscription->ends_at->toDateString(),
                    'duration_years' => $durationYears,
                    'valid_until_year' => $validUntilYear,
                    'remaining_days' => max(0, $remainingDays),
                    'is_active' => $subscription->isActive(),
                    'is_valid' => $remainingDays > 0,
                    'metadata' => $metadata
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al obtener información del plan socio: ' . $e->getMessage()
            ];
        }
    }
}
