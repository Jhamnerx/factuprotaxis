<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Taxis\Vehiculos;
use Modules\Payment\Models\PaymentColor;
use Illuminate\Support\Facades\Validator;
use Modules\Payment\Models\SubscriptionInvoice;
use App\Http\Resources\Tenant\VehiculoCollection;

class PagosController extends Controller
{
    public function index()
    {
        return view('tenant.taxis.pagos.index');
    }

    public function columnsVehiculos()
    {
        return [
            'id' => 'ID',
            'placa' => 'Placa',
            'marca' => 'Marca',
            'modelo' => 'Modelo',
            'color' => 'Color',
            'anio' => 'Año',
            'tipo_vehiculo' => 'Tipo de Vehículo',
            'estado' => 'Estado',
        ];
    }

    public function recordsVehiculos(Request $request)
    {
        $records = $this->getRecords($request);

        // Si estamos en la vista de bajas, filtramos solo los vehículos con estado DE BAJA
        if ($request->has('estado') && $request->estado === 'BAJA') {
            $records->where('estado', 'DE BAJA');
        }

        return new VehiculoCollection($records->paginate(config('tenant.items_per_page')));
    }

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
                $records->where('estado', $request->value);
                break;
            case 'estado_tuc':
                $records->where('estado_tuc', $request->value);
                break;

            default:
                if ($request->has('column')) {
                    $records->where($request->column, 'like', "%{$request->value}%");
                }
                break;
        }


        return $records->orderBy('id', 'desc');
    }

    public function store(Request $request)
    {
        $data = $request->all();

        // Determinar si es un pago único o múltiple
        if (isset($data[0]) && is_array($data[0])) {
            // Es un pago de múltiples meses (array de arrays)
            return $this->procesarPagoMultiple($data);
        } else {
            // Es un pago de un solo mes (array simple)
            return $this->procesarPagoUnico($data);
        }
    }

    /**
     * Procesa un pago para un solo mes
     * 
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    private function procesarPagoUnico($data)
    {
        // Validaciones básicas para el pago único
        $validator = Validator::make($data, [
            'vehiculoId' => 'required|exists:tenant.vehiculos,id',
            'monto' => 'required|numeric|min:0',
            'fecha' => 'required|date',
            'year' => 'required|integer',
            'month' => 'required|integer|min:1|max:12',
            'descuento' => 'nullable|numeric|min:0',
            'moneda' => 'required|string|size:3',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Error de validación',
                'errors' => $validator->errors()
            ], 422);
        }

        try {
            $vehiculo = Vehiculos::findOrFail($data['vehiculoId']);            // Crear factura de suscripción
            $invoice = new SubscriptionInvoice();
            $invoice->vehiculo_id = $data['vehiculoId'];
            $invoice->subscription_id = $vehiculo->subscription_id;
            $invoice->year = $data['year'];
            $invoice->mes = $data['month'];
            $invoice->monto = $data['monto'];
            $invoice->fecha_cobro = $data['fecha'];
            $invoice->descuento = $data['descuento'] ?? 0;
            $invoice->moneda = $data['moneda'];
            // Ya no guardamos el color directamente en la factura
            // $invoice->color = $data['color'];
            $invoice->tipo = $data['tipo'];
            $invoice->save();

            // Registrar el color en PaymentColor
            PaymentColor::updateOrCreate(
                [
                    'colorable_id' => $data['vehiculoId'],
                    'colorable_type' => get_class($vehiculo),
                    'year' => $data['year'],
                    'month' => $data['month'],
                ],
                ['color' => $data['color']]
            );

            return response()->json([
                'success' => true,
                'message' => 'Pago registrado correctamente',
                'data' => $invoice
            ]);
        } catch (\Exception $e) {

            return response()->json([
                'success' => false,
                'message' => 'Error al procesar el pago: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Procesa pagos para múltiples meses
     * 
     * @param array $data
     * @return \Illuminate\Http\JsonResponse
     */
    private function procesarPagoMultiple($data)
    {
        // Validar que todos los elementos tengan la misma estructura
        $errores = [];
        $mesesPagados = [];
        $montoPorMesTotal = 0;
        $descuentoPorMesTotal = 0;

        // Obtener el ID de vehículo del primer elemento para validar que sea el mismo
        $vehiculoId = $data[0]['vehiculoId'] ?? null;
        // Obtener el año del primer registro (asumimos que todos son del mismo año)
        $year = $data[0]['year'] ?? null;
        // Fecha del pago (usamos la fecha del primer registro)
        $fecha = $data[0]['fecha'] ?? now()->format('Y-m-d');        // Moneda (usamos la del primer registro)
        $moneda = $data[0]['moneda'] ?? 'PEN';
        // Tipo de pago (usamos el del primer registro)
        $tipo = $data[0]['tipo'] ?? null;
        // ID único para identificar este grupo de pagos
        $grupoPagoId = uniqid('pago_grupal_');

        DB::beginTransaction();

        try {
            // Verificar que el vehículo exista
            $vehiculo = Vehiculos::findOrFail($vehiculoId);

            // Estructura para almacenar los detalles de todos los pagos
            $pagosMensualesData = [];

            // Validar todos los pagos mensuales antes de procesarlos
            foreach ($data as $index => $pagoMes) {
                // Validar cada pago mensual
                $validator = Validator::make($pagoMes, [
                    'vehiculoId' => 'required|integer',
                    'year' => 'required|integer',
                    'mes' => 'required|integer|min:1|max:12',
                    'monto' => 'required|numeric|min:0',
                    'fecha' => 'required|date',
                    'descuentoPorMes' => 'nullable|numeric|min:0',
                    'montoPorMes' => 'nullable|numeric|min:0',
                ]);

                if ($validator->fails()) {
                    $errores[] = [
                        'mes' => $pagoMes['mes'] ?? $index,
                        'errores' => $validator->errors()->toArray()
                    ];
                    continue;
                }

                // Verificar que todos los pagos sean del mismo vehículo
                if ($pagoMes['vehiculoId'] != $vehiculoId) {
                    $errores[] = [
                        'mes' => $pagoMes['mes'],
                        'error' => 'Todos los pagos deben ser para el mismo vehículo'
                    ];
                    continue;
                }                // Acumular montos para el total
                $montoPorMesTotal += $pagoMes['montoPorMes'] ?? $pagoMes['monto'];
                // No acumulamos el descuento total, ya que cada elemento tiene el mismo valor total
                // Solo tomamos el del primer elemento en descuentoTotalGlobal
                // $descuentoTotal += $pagoMes['descuento'] ?? 0;
                $descuentoPorMesTotal += $pagoMes['descuentoPorMes'] ?? 0;

                // Acumular meses pagados para este año
                $mesesPagados[] = $pagoMes['mes'];

                // Almacenar detalles de cada pago mensual
                $pagosMensualesData[] = [
                    'mes' => $pagoMes['mes'],
                    'year' => $pagoMes['year'],
                    'monto' => $pagoMes['montoTotal'],
                    'monto_por_mes' => $pagoMes['montoPorMes'] ?? $pagoMes['monto'],
                    'descuento_por_mes' => $pagoMes['descuentoPorMes'] ?? 0,
                    'fecha' => $pagoMes['fecha'],
                    'color' => $pagoMes['color'],
                ];
            }

            // Si hay errores, no continuar
            if (!empty($errores)) {
                DB::rollBack();
                return response()->json([
                    'success' => false,
                    'message' => 'Errores en la validación de pagos mensuales',
                    'errors' => $errores
                ], 422);
            }            // Crear un solo registro de factura para todos los meses
            $invoice = new SubscriptionInvoice();
            $invoice->subscription_id = $vehiculo->subscription_id;
            $invoice->vehiculo_id = $vehiculoId;
            $invoice->fecha_cobro = $fecha;
            // Usamos el campo data para almacenar todos los meses pagados
            $dataJson = [];
            $dataJson[$year] = $mesesPagados;
            $invoice->data = $dataJson;
            $invoice->moneda = $moneda;
            $invoice->tipo = $tipo;
            $invoice->payment_details = $pagosMensualesData;
            $invoice->grupo_pago_id = $grupoPagoId;
            $invoice->es_pago_multiple = true;
            $invoice->cantidad_meses = count($mesesPagados);
            $invoice->save();


            // Registrar los colores para cada mes en PaymentColor
            foreach ($pagosMensualesData as $detalle) {
                PaymentColor::updateOrCreate(
                    [
                        'colorable_id' => $vehiculoId,
                        'colorable_type' => get_class($vehiculo),
                        'year' => $detalle['year'],
                        'month' => $detalle['mes'],
                    ],
                    ['color' => $detalle['color']]
                );
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => 'Pagos múltiples registrados correctamente en un solo documento',
                'data' => $invoice,
                'invoice_id' => $invoice->id
            ]);
        } catch (\Exception $e) {
            dd($e);
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'Error al procesar los pagos múltiples: ' . $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
}
