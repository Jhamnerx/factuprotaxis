<?php

namespace App\Http\Controllers\Tenant;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Payment\Models\Plan;
use App\Models\Tenant\Taxis\Marca;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\Taxis\Modelo;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Taxis\Vehiculos;
use Modules\Payment\Models\Subscription;
use App\Models\Tenant\Taxis\Propietarios;
use App\Http\Requests\Tenant\VehiculoRequest;
use App\Http\Resources\Tenant\VehiculoResource;
use Modules\Payment\Models\SubscriptionInvoice;
use App\Http\Resources\Tenant\VehiculoCollection;
use App\Http\Resources\Tenant\SubscriptionCollection;
use App\Models\Tenant\Taxis\Condicion;

class UnidadesController extends Controller
{

    public function index()
    {
        $estado = '1';
        return view('tenant.taxis.unidades.index', compact('estado'));
    }

    public function indexBajas()
    {
        $estado = '0';
        return view('tenant.taxis.unidades.index', compact('estado'));
    }

    public function columns()
    {
        return [
            'id' => 'ID',
            'numero_interno' => 'N° Interno',
            'placa' => 'Placa',
            'marca' => 'Marca',
            'modelo' => 'Modelo',
            'color' => 'Color',
            'year' => 'Año',
            'propietario' => 'Propietario',
            'estado' => 'Estado',
            'estado_tuc' => 'Estado TUC',
            'created_at' => 'Fecha de Registro',
        ];
    }

    public function records(Request $request)
    {
        $records = $this->getRecords($request);

        // Si estamos en la vista de bajas, filtramos solo los vehículos con estado DE BAJA
        if ($request->has('estado') && $request->estado === 'BAJA') {
            $records->where('estado', 'DE BAJA');
        }

        return new VehiculoCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function tables()
    {

        $configuration = Configuration::first();
        $propietarios = $this->table('propietarios');
        $marcas = Marca::where('enabled', true)
            ->orderBy('nombre')
            ->get();
        $modelos = Modelo::where('enabled', true)
            ->orderBy('nombre')
            ->get();


        $condiciones = Condicion::orderBy('descripcion')
            ->get();

        return compact(
            'configuration',
            'propietarios',
            'marcas',
            'modelos',
            'condiciones'
        );
    }

    public function table($table)
    {
        if ($table === 'propietarios') {
            $propietarios = Propietarios::query()
                ->whereIsEnabled()
                ->orderBy('name')
                ->take(20)
                ->get()->transform(function ($row) {
                    /** @var Propietarios $row */
                    return $row->getCollectionData();
                });
            return $propietarios;
        }
    }

    public function record($id)
    {
        $record = new VehiculoResource(Vehiculos::findOrFail($id));

        return $record;
    }
    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getRecords(Request $request)
    {

        // $records = Item::whereTypeUser()->whereNotIsSet();
        $records = Vehiculos::query();

        switch ($request->column) {
            case 'numero_interno':
                $records->where('numero_interno', 'like', "%{$request->value}%");
                break;
            case 'propietario':
                $records->whereHas('propietario', function ($q) use ($request) {
                    $q->where('name', 'like', "%{$request->value}%");
                });
                break;
            case 'marca':
                $records->whereHas('marca', function ($q) use ($request) {
                    $q->where('nombre', 'like', "%{$request->value}%");
                });
                break;
            case 'modelo':
                $records->whereHas('modelo', function ($q) use ($request) {
                    $q->where('nombre', 'like', "%{$request->value}%");
                });
                break;
            case 'placa':
                $records->where('placa', 'like', "%{$request->value}%");
                break;
            case 'color':
                $records->where('color', 'like', "%{$request->value}%");
                break;
            case 'year':
                $records->where('year', $request->value);
                break;
            case 'estado':
                $records->where('estado', 'like', "%{$request->value}%");
                break;
            case 'estado_tuc':
                $records->whereHas('estadoTuc', function ($q) use ($request) {
                    $q->where('descripcion', $request->value);
                });
                break;

            default:
                if ($request->has('column')) {
                    $records->where($request->column, 'like', "%{$request->value}%");
                }
                break;
        }

        if (isset($request->type)) {

            if ($request->type == "0") {
                $records->notActive();
            }
        }


        return $records->orderBy('id', 'desc');
    }


    public function store(VehiculoRequest $request)
    {
        $id = $request->input('id');
        $vehiculo = Vehiculos::firstOrNew(['id' => $id]);
        $data = $request->all();
        unset($data['id']);
        $data['user_id'] = auth()->user()->id; // Asignar el usuario autenticado
        $vehiculo->fill($data);


        $vehiculo->save();

        $msg = ($id) ? 'Vehículo editado con éxito' : 'Vehículo registrado con éxito';

        return [
            'success' => true,
            'message' => $msg,
            'id' => $vehiculo->id
        ];
    }

    public function destroy($id)
    {
        try {

            $vehiculo = Vehiculos::findOrFail($id);
            $vehiculo->delete();

            return [
                'success' => true,
                'message' => 'Vehículo eliminado con éxito'
            ];
        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false, 'message' => 'El Vehículo esta siendo usado por otros registros, no puede eliminar'] : ['success' => false, 'message' => 'Error inesperado, no se pudo eliminar el Vehículo'];
        }
    }

    /**
     * Busca propietarios según un término de búsqueda
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchPropietarios(Request $request)
    {
        $term = $request->input('term');
        $propietarios = Propietarios::where('enabled', true)
            ->where(function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('number', 'like', "%{$term}%")
                    ->orWhere('email', 'like', "%{$term}%");
            })
            ->orderBy('name')
            ->take(15)
            ->get()
            ->map(function ($propietario) {
                return $propietario->getCollectionData();
            });

        return response()->json($propietarios);
    }

    /**
     * Búsqueda remota de vehículos por placa o propietario
     */
    public function searchUnidades(Request $request)
    {
        $q = $request->input('q');
        $vehiculos = Vehiculos::with('propietario')
            ->where(function ($query) use ($q) {
                $query->where('placa', 'like', "%$q%")
                    ->orWhereHas('propietario', function ($sub) use ($q) {
                        $sub->where('name', 'like', "%$q%")
                            ->orWhere('number', 'like', "%$q%")
                            ->orWhere('email', 'like', "%$q%");
                    });
            })
            ->orderBy('placa')
            ->take(20)
            ->get()
            ->map(function ($vehiculo) {
                return [
                    'id' => $vehiculo->id,
                    'placa' => $vehiculo->placa,
                    'propietario' => $vehiculo->propietario ? $vehiculo->propietario->toArray() : null,
                    'marca' => $vehiculo->marca,
                    'modelo' => $vehiculo->modelo,
                    'color' => $vehiculo->color,
                    'year' => $vehiculo->year,
                    // Agrega otros campos necesarios
                ];
            });
        return response()->json(['data' => $vehiculos]);
    }

    public function planes_tables()
    {
        $planes = \Modules\Payment\Models\Plan::where('is_active', true)
            ->orderBy('sort_order')
            ->get(['id', 'name', 'description', 'price', 'currency', 'invoice_period', 'invoice_interval']);
        return response()->json([
            'planes' => $planes
        ]);
    }

    public function subscription_create(Request $request)
    {

        $request->validate([
            'plan_id' => 'required|exists:tenant.plans,id',
            'vehiculo_id' => 'required|exists:tenant.vehiculos,id',
            'fecha_inicio' => 'required_if:type,create',
            'type' => 'required|in:create,change',
        ]);

        $vehiculo = \App\Models\Tenant\Taxis\Vehiculos::where('id', $request->vehiculo_id)->first();

        if (!$vehiculo) {
            return response()->json(['success' => false, 'message' => 'Unidad no encontrada'], 404);
        }

        $plan = Plan::find($request->plan_id);


        if (!$plan) {
            return response()->json(['success' => false, 'message' => 'Plan no encontrado'], 404);
        }


        try {
            DB::connection('tenant')->beginTransaction();

            if ($request->type === 'create') {
                $fecha = Carbon::createFromFormat('Y-m-d', $request->fecha_inicio);
                $sub = $vehiculo->newPlanSubscription('principal', $plan, $fecha);


                $vehiculo->update([
                    'plan_id' => $plan->id,
                    'subscription_id' => $sub->id,
                ]);
                DB::connection('tenant')->commit();
                return [
                    'success' => true,
                    'message' => 'Suscripción creada correctamente',
                ];
            } else {

                if ($plan->id == $vehiculo->plan_id) {
                    return [
                        'success' => false,
                        'message' => 'El plan ya está activo',
                    ];
                }

                $subscription = Subscription::find($vehiculo->subscription_id);

                if (!$subscription) {
                    return [
                        'success' => false,
                        'message' => 'No se encontró la suscripción actual',
                    ];
                }


                $vehiculo->update([
                    'plan_id' => $plan->id,
                    'subscription_id' => $subscription->id,
                ]);

                $subscription->changePlan($plan);
                DB::connection('tenant')->commit();
                return [
                    'success' => true,
                    'message' => 'Plan cambiado correctamente',
                ];
            }
        } catch (\Exception $e) {
            DB::connection('tenant')->rollBack();
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 500);
        }
    }
    public function subscriptionInvoices($id)
    {

        $invoices = SubscriptionInvoice::where('subscription_id', $id);

        return new SubscriptionCollection($invoices->get());
    }

    public function paymentColors($id)
    {
        // Obtiene el vehículo 
        $vehiculo = Vehiculos::findOrFail($id);

        // Obtiene los colores de pago usando la relación paymentColors
        $paymentColors = $vehiculo->paymentColors()->get();
        // Preparamos el array de colores por año y mes
        $colors = [];

        foreach ($paymentColors as $paymentColor) {
            $year = (int)$paymentColor->year;
            $month = (int)$paymentColor->month;

            if (!isset($colors[$year])) {
                $colors[$year] = [];
            }

            $colors[(int)$year][(int)$month] = $paymentColor->color;
        }

        // Si no hay colores específicos guardados, generamos algunos basados en las facturas
        if (empty($paymentColors) && $vehiculo->subscription) {
            $invoices = SubscriptionInvoice::where('subscription_id', $vehiculo->subscription->id)->get();

            foreach ($invoices as $invoice) {
                $year = (int)$invoice->year;
                $month = (int)$invoice->month;

                if (!isset($colors[$year])) {
                    $colors[$year] = [];
                }

                // Define un color basado en el estado del pago
                // Se pueden añadir más estados y colores según necesidad
                if ($invoice->status === 'paid') {
                    $colors[$year][$month] = '#d1fae5'; // Verde para pagos completos
                } elseif ($invoice->status === 'partial') {
                    $colors[$year][$month] = '#fef3c7'; // Amarillo para pagos parciales
                } elseif ($invoice->status === 'pending') {
                    $colors[$year][$month] = '#fee2e2'; // Rojo para pagos pendientes
                }
            }
        }

        return response()->json([
            'success' => true,
            'colors' => $colors,
            'vehicle_id' => $id
        ]);
    }

    /**
     * Actualiza o crea un color para un pago específico de un vehículo
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function updatePaymentColor(Request $request)
    {
        $request->validate([
            'vehicle_id' => 'required|exists:vehiculos,id',
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
            'color' => 'required|string'
        ]);

        $vehiculo = Vehiculos::findOrFail($request->vehicle_id);

        // Busca si ya existe un color para este año y mes
        $paymentColor = $vehiculo->paymentColors()
            ->where('year', $request->year)
            ->where('month', $request->month)
            ->first();

        if ($paymentColor) {
            // Actualizar el color existente
            $paymentColor->update(['color' => $request->color]);
        } else {
            // Crear un nuevo registro de color
            $vehiculo->paymentColors()->create([
                'year' => $request->year,
                'month' => $request->month,
                'color' => $request->color
            ]);
        }

        return response()->json([
            'success' => true,
            'message' => 'Color de pago actualizado correctamente'
        ]);
    }

    /**
     * Vincular conductor a vehículo
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function vincularConductor(Request $request)
    {
        try {
            $request->validate([
                'vehiculo_id' => 'required|exists:tenant.vehiculos,id',
                'conductor_id' => 'nullable|exists:tenant.conductores,id'
            ]);

            $vehiculo = Vehiculos::findOrFail($request->vehiculo_id);

            // Si se está asignando un conductor nuevo, verificar que no esté ya asignado a otro vehículo
            if ($request->conductor_id) {
                $conductorYaAsignado = Vehiculos::where('conductor_id', $request->conductor_id)
                    ->where('id', '!=', $vehiculo->id)
                    ->first();

                if ($conductorYaAsignado) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El conductor ya está asignado a otro vehículo (Placa: ' . $conductorYaAsignado->placa . ')'
                    ], 400);
                }
            }

            // Actualizar el conductor del vehículo
            $vehiculo->update(['conductor_id' => $request->conductor_id]);

            // Obtener información del conductor para la respuesta
            $conductor = null;
            if ($request->conductor_id) {
                $conductor = \App\Models\Tenant\Taxis\Conductor::find($request->conductor_id);
            }

            return response()->json([
                'success' => true,
                'message' => $request->conductor_id ? 'Conductor vinculado correctamente' : 'Conductor desvinculado correctamente',
                'data' => [
                    'vehiculo_id' => $vehiculo->id,
                    'conductor_id' => $request->conductor_id,
                    'conductor' => $conductor ? $conductor->getCollectionData() : null
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al vincular conductor: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Obtener conductores disponibles para asignar
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function getConductoresDisponibles(Request $request)
    {
        try {
            $vehiculoId = $request->input('vehiculo_id');

            // Obtener conductores habilitados que no estén asignados a ningún vehículo
            // excepto al vehículo actual (para permitir reasignación)
            $conductores = \App\Models\Tenant\Taxis\Conductor::whereEnabled()
                ->where(function ($query) use ($vehiculoId) {
                    $query->whereDoesntHave('vehiculo')
                        ->orWhereHas('vehiculo', function ($q) use ($vehiculoId) {
                            if ($vehiculoId) {
                                $q->where('id', $vehiculoId);
                            }
                        });
                })
                ->orderBy('name')
                ->get()
                ->map(function ($conductor) {
                    return $conductor->getCollectionData();
                });

            return response()->json([
                'success' => true,
                'data' => $conductores
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener conductores: ' . $e->getMessage()
            ], 500);
        }
    }
}
