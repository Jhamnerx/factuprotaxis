<?php

namespace Modules\Taxis\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Taxis\Conductor;
use App\Models\Tenant\Taxis\Contratos;
use App\Models\Tenant\Taxis\Solicitud;
use App\Models\Tenant\Taxis\Vehiculos;
use App\Models\Tenant\YapeNotification;
use App\Models\Tenant\Taxis\Conductores;
use Modules\Payment\Models\PaymentColor;
use App\Models\Tenant\Taxis\PermisoUnidad;
use App\Models\Tenant\Taxis\ConstanciaBaja;
use App\Http\Controllers\Tenant\PdfController;
use Modules\Payment\Models\SubscriptionInvoice;
use App\Http\Resources\Tenant\ContratoCollection;
use App\Http\Resources\Tenant\VehiculoCollection;
use App\Http\Resources\Tenant\ConductorCollection;
use App\Http\Resources\Tenant\SolicitudCollection;
use App\Http\Resources\Tenant\SubscriptionCollection;
use App\Http\Resources\Tenant\PermisoUnidadCollection;
use App\Http\Resources\Tenant\ConstanciaBajaCollection;

class PropietarioController extends Controller
{
    /**
     * Formatear nombres de servicios para mostrar
     */
    private function formatearNombreServicio($nombre)
    {
        // Mapeo de nombres comunes de servicios
        $serviciosFormateados = [
            'soat' => 'SOAT',
            'afocat' => 'AFOCAT',
            'revision_tecnica' => 'Revisión Técnica',
            'mantenimiento' => 'Mantenimiento',
            'poliza_seguro' => 'Póliza de Seguro',
            'licencia_conductor' => 'Licencia de Conductor',
            'citv' => 'CITV',
            'inspeccion_tecnica' => 'Inspección Técnica',
            'seguro_obligatorio' => 'Seguro Obligatorio',
            'tarjeta_propiedad' => 'Tarjeta de Propiedad',
        ];

        $nombreLower = strtolower(trim($nombre));

        // Si existe en el mapeo, usar el formato correcto
        if (isset($serviciosFormateados[$nombreLower])) {
            return $serviciosFormateados[$nombreLower];
        }

        // Si no existe en el mapeo, aplicar formato general
        return ucwords(str_replace(['_', '-'], ' ', $nombreLower));
    }
    /**
     * Dashboard del propietario
     */
    public function dashboard()
    {
        $propietario = session('taxis_user');

        // Estadísticas del propietario
        $vehiculosIds = Vehiculos::where('propietario_id', $propietario['id'])->pluck('id')->toArray();

        $stats = [
            'total_vehiculos' => Vehiculos::where('propietario_id', $propietario['id'])->count(),
            'vehiculos_activos' => Vehiculos::where('propietario_id', $propietario['id'])
                ->where('estado', 'activo')->count(),
            'total_conductores' => Conductor::whereHas('vehiculo', function ($q) use ($propietario) {
                $q->where('propietario_id', $propietario['id']);
            })->count(),
            'contratos_activos' => Contratos::where('propietario_id', $propietario['id'])
                ->where('estado', 'activo')->count(),
            'solicitudes_pendientes' => Solicitud::whereHas('detalle', function ($q) use ($vehiculosIds) {
                $q->whereIn('vehiculo_id', $vehiculosIds);
            })->where('estado', 'pendiente')->count(),
            'pagos_pendientes' => SubscriptionInvoice::whereHas('vehiculo', function ($q) use ($propietario) {
                $q->where('propietario_id', $propietario['id']);
            })->where('estado', 'pendiente')->count()
        ];

        // Vehículos recientes
        $vehiculos_recientes = Vehiculos::where('propietario_id', $propietario['id'])
            ->with(['marca', 'modelo', 'conductor'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('taxis::propietario.dashboard', compact('propietario', 'stats', 'vehiculos_recientes'));
    }

    /**
     * Ver vehículos del propietario
     */
    public function vehiculos(Request $request)
    {
        $propietario = session('taxis_user');

        // Query base
        $query = Vehiculos::where('propietario_id', $propietario['id'])
            ->with(['marca', 'modelo', 'conductor']);

        // Aplicar filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('marca_id')) {
            $query->where('marca_id', $request->marca_id);
        }

        if ($request->filled('conductor')) {
            if ($request->conductor === 'sin_conductor') {
                $query->whereNull('conductor_id');
            } else {
                $query->where('conductor_id', $request->conductor);
            }
        }

        // Obtener vehículos con paginación y mantener parámetros de filtro
        $vehiculos = $query->orderBy('created_at', 'desc')->paginate(12)->appends($request->query());

        // Obtener datos para los filtros - Solo marcas y conductores que están en uso por este propietario
        $marcasIds = Vehiculos::where('propietario_id', $propietario['id'])
            ->whereNotNull('marca_id')
            ->distinct()
            ->pluck('marca_id');

        $marcas = \App\Models\Tenant\Taxis\Marca::whereIn('id', $marcasIds)
            ->orderBy('nombre')
            ->get();

        $conductoresIds = Vehiculos::where('propietario_id', $propietario['id'])
            ->whereNotNull('conductor_id')
            ->distinct()
            ->pluck('conductor_id');

        $conductores = \App\Models\Tenant\Taxis\Conductor::whereIn('id', $conductoresIds)
            ->orderBy('name')
            ->get();

        return view('taxis::propietario.vehiculos.index', compact('propietario', 'vehiculos', 'marcas', 'conductores'));
    }

    /**
     * API: Obtener vehículos del propietario
     */
    public function vehiculosRecords(Request $request)
    {
        $propietario = session('taxis_user');

        $records = Vehiculos::where('propietario_id', $propietario['id'])
            ->with(['marca', 'modelo', 'conductor'])
            ->orderBy('id', 'desc');

        // Filtros adicionales
        if ($request->estado) {
            $records->where('estado', $request->estado);
        }

        return new VehiculoCollection($records->paginate(config('tenant.items_per_page')));
    }

    /**
     * Ver conductores asociados a los vehículos del propietario
     */
    public function conductores(Request $request)
    {
        $propietario = session('taxis_user');

        // Query base para conductores con vehículos del propietario
        $query = Conductor::whereHas('vehiculo', function ($q) use ($propietario) {
            $q->where('propietario_id', $propietario['id']);
        })->with(['vehiculo' => function ($q) use ($propietario) {
            $q->where('propietario_id', $propietario['id'])
                ->with(['marca', 'modelo']);
        }]);

        // Aplicar filtros
        if ($request->filled('estado')) {
            if ($request->estado === 'sin_vehiculo') {
                $query->whereDoesntHave('vehiculo');
            } else {
                $query->whereHas('vehiculo', function ($q) use ($request) {
                    $q->where('estado', $request->estado);
                });
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('telefono', 'like', "%{$search}%")
                    ->orWhere('numero_documento', 'like', "%{$search}%");
            });
        }

        // Obtener conductores con paginación
        $conductores = $query->orderBy('name')->paginate(12)->appends($request->query());

        return view('taxis::propietario.conductores.index', compact('propietario', 'conductores'));
    }

    /**
     * API: Obtener conductores del propietario
     */
    public function conductoresRecords(Request $request)
    {
        $propietario = session('taxis_user');

        $records = Conductor::whereHas('vehiculos', function ($q) use ($propietario) {
            $q->where('propietario_id', $propietario['id']);
        })->with(['vehiculos' => function ($q) use ($propietario) {
            $q->where('propietario_id', $propietario['id']);
        }])->orderBy('name');

        return new ConductorCollection($records->paginate(config('tenant.items_per_page')));
    }

    /**
     * Ver contratos del propietario
     */
    public function contratos(Request $request)
    {
        $propietario = session('taxis_user');

        // Query base para contratos del propietario
        $query = Contratos::where('propietario_id', $propietario['id']);

        // Aplicar filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('vehiculo_id')) {
            $query->where('vehiculo_id', $request->vehiculo_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(vehiculo, '$.placa')) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(vehiculo, '$.numero_interno')) LIKE ?", ["%{$search}%"]);
            });
        }

        // Obtener contratos con paginación
        $contratos = $query->orderBy('created_at', 'desc')->paginate(12)->appends($request->query());

        // Obtener vehículos para el filtro
        $vehiculos = Vehiculos::where('propietario_id', $propietario['id'])
            ->with(['marca', 'modelo'])
            ->orderBy('placa')
            ->get();

        return view('taxis::propietario.contratos.index', compact('propietario', 'contratos', 'vehiculos'));
    }
    /**
     * Descargar PDF de contrato (solo para vehículos del propietario)
     */
    public function descargarContrato($contratoId)
    {
        $propietario = session('taxis_user');

        // Verificar que el contrato pertenece al propietario
        $contrato = Contratos::where('id', $contratoId)
            ->where('propietario_id', $propietario['id'])
            ->firstOrFail();


        $pdfController = new PdfController();
        return $pdfController->contrato($contrato->id);
    }
    /**
     * API: Obtener contratos del propietario
     */
    public function contratosRecords(Request $request)
    {
        $propietario = session('taxis_user');

        $records = Contratos::where('propietario_id', $propietario['id'])
            ->with(['vehiculo'])
            ->orderBy('id', 'desc');

        return new ContratoCollection($records->paginate(config('tenant.items_per_page')));
    }

    /**
     * Ver solicitudes del propietario
     */
    public function solicitudes(Request $request)
    {
        $propietario = session('taxis_user');

        // Primero obtenemos los IDs de los vehículos del propietario
        $vehiculosIds = Vehiculos::where('propietario_id', $propietario['id'])
            ->pluck('id')
            ->toArray();

        // Query base para solicitudes del propietario
        $query = Solicitud::whereHas('detalle', function ($q) use ($vehiculosIds) {
            $q->whereIn('vehiculo_id', $vehiculosIds);
        })->with(['detalle.infoVehiculo.marca', 'detalle.infoVehiculo.modelo', 'creator']);

        // Aplicar filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('tipo')) {
            $query->where('tipo', $request->tipo);
        }

        if ($request->filled('vehiculo_id')) {
            $query->whereHas('detalle', function ($q) use ($request) {
                $q->where('vehiculo_id', $request->vehiculo_id);
            });
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('descripcion', 'like', "%{$search}%")
                    ->orWhere('motivo', 'like', "%{$search}%")
                    ->orWhere('observaciones', 'like', "%{$search}%")
                    ->orWhereHas('detalle.infoVehiculo', function ($vq) use ($search) {
                        $vq->where('placa', 'like', "%{$search}%")
                            ->orWhere('numero_interno', 'like', "%{$search}%");
                    });
            });
        }

        // Obtener solicitudes con paginación
        $solicitudes = $query->orderBy('created_at', 'desc')->paginate(12)->appends($request->query());

        // Obtener vehículos para el filtro
        $vehiculos = Vehiculos::where('propietario_id', $propietario['id'])
            ->with(['marca', 'modelo'])
            ->orderBy('placa')
            ->get();

        return view('taxis::propietario.solicitudes.index', compact('propietario', 'solicitudes', 'vehiculos'));
    }
    /**
     * Descargar PDF de solicitud (solo para solicitudes relacionadas a vehículos del propietario)
     */
    public function descargarSolicitud($solicitudId)
    {
        $propietario = session('taxis_user');

        // Verificar que la solicitud tiene vehículos del propietario autenticado
        $solicitud = Solicitud::findOrFail($solicitudId);
        $pdfController = new PdfController();
        return $pdfController->solicitud($solicitud->id);
    }
    /**
     * API: Obtener solicitudes del propietario
     */
    public function solicitudesRecords(Request $request)
    {
        $propietario = session('taxis_user');

        // Primero obtenemos los IDs de los vehículos del propietario
        $vehiculosIds = Vehiculos::where('propietario_id', $propietario['id'])
            ->pluck('id')
            ->toArray();

        // Luego obtenemos las solicitudes que tienen detalles con esos vehículos
        $records = Solicitud::whereHas('detalle', function ($q) use ($vehiculosIds) {
            $q->whereIn('vehiculo_id', $vehiculosIds);
        })->with(['detalle.vehiculo'])
            ->orderBy('id', 'desc');

        return new SolicitudCollection($records->paginate(config('tenant.items_per_page')));
    }

    /**
     * Ver constancias del propietario
     */
    public function constancias(Request $request)
    {
        $propietario = session('taxis_user');

        // Obtener IDs de vehículos del propietario
        $vehiculosIds = Vehiculos::where('propietario_id', $propietario['id'])->pluck('id')->toArray();

        // Query base para constancias usando los IDs de vehículos
        $query = \App\Models\Tenant\Taxis\ConstanciaBaja::whereIn('vehiculo_id', $vehiculosIds)
            ->with(['datosVehiculo.marca', 'datosVehiculo.modelo', 'creator']);

        // Aplicar filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('vehiculo_id')) {
            $query->where('vehiculo_id', $request->vehiculo_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('numero', 'like', "%{$search}%")
                    ->orWhere('observaciones', 'like', "%{$search}%")
                    ->orWhereHas('datosVehiculo', function ($vq) use ($search) {
                        $vq->where('placa', 'like', "%{$search}%")
                            ->orWhere('numero_interno', 'like', "%{$search}%");
                    });
            });
        }

        // Obtener constancias con paginación
        $constancias = $query->orderBy('created_at', 'desc')->paginate(12)->appends($request->query());

        // Obtener vehículos para el filtro
        $vehiculos = Vehiculos::where('propietario_id', $propietario['id'])
            ->with(['marca', 'modelo'])
            ->orderBy('placa')
            ->get();

        return view('taxis::propietario.constancias.index', compact('propietario', 'constancias', 'vehiculos'));
    }
    /**
     * Descargar PDF de constancia (solo para constancias relacionadas a vehículos del propietario)
     */
    public function descargarConstancia($constanciaId)
    {
        $propietario = session('taxis_user');

        // Verificar que la constancia pertenece a un vehículo del propietario autenticado
        $constancia = \App\Models\Tenant\Taxis\ConstanciaBaja::findOrFail($constanciaId);

        $pdfController = new PdfController();
        return $pdfController->constancias($constancia->id);
    }

    /**
     * API: Obtener constancias del propietario
     */
    public function constanciasRecords(Request $request)
    {
        $propietario = session('taxis_user');

        // Obtener IDs de vehículos del propietario
        $vehiculosIds = Vehiculos::where('propietario_id', $propietario['id'])->pluck('id')->toArray();

        // Query usando los IDs de vehículos
        $records = ConstanciaBaja::whereIn('vehiculo_id', $vehiculosIds)
            ->orderBy('id', 'desc');

        return new ConstanciaBajaCollection($records->paginate(config('tenant.items_per_page')));
    }

    /**
     * Ver pagos de todos los vehículos del propietario
     */
    public function pagos()
    {
        $propietario = session('taxis_user');
        return view('taxis::propietario.pagos.index', compact('propietario'));
    }

    /**
     * API: Obtener pagos del propietario
     */
    public function pagosRecords(Request $request)
    {
        $propietario = session('taxis_user');

        $records = SubscriptionInvoice::whereHas('vehiculo', function ($q) use ($propietario) {
            $q->where('propietario_id', $propietario['id']);
        })->with(['vehiculo']);

        // Filtrar por vehículo específico si se proporciona
        if ($request->filled('vehiculo_id')) {
            $records->where('vehiculo_id', $request->vehiculo_id);
        }

        $records->orderBy('year', 'desc')->orderBy('mes', 'desc');

        return new SubscriptionCollection($records->paginate(config('tenant.items_per_page')));
    }

    /**
     * API: Registrar nuevo pago
     */
    public function registrarPago(Request $request)
    {
        $propietario = session('taxis_user');

        $validated = $request->validate([
            'vehiculo_id' => 'required|exists:taxis_vehiculos,id',
            'mes' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'monto' => 'required|numeric|min:0',
            'metodo_pago' => 'required|string',
            'estado' => 'string'
        ]);

        // Verificar que el vehículo pertenece al propietario
        $vehiculo = Vehiculos::where('id', $validated['vehiculo_id'])
            ->where('propietario_id', $propietario['id'])
            ->firstOrFail();

        $pago = SubscriptionInvoice::create([
            'vehiculo_id' => $validated['vehiculo_id'],
            'mes' => $validated['mes'],
            'year' => $validated['year'],
            'monto' => $validated['monto'],
            'metodo_pago' => $validated['metodo_pago'],
            'estado' => $validated['estado'] ?? 'pagado',
            'fecha_cobro' => now(),
            'tipo' => 'mensual'
        ]);

        return response()->json(['data' => $pago, 'message' => 'Pago registrado correctamente']);
    }

    /**
     * Método de prueba para verificar datos
     */
    public function testVehiculos()
    {
        $propietario = session('taxis_user');
        if (!$propietario) {
            return response()->json(['error' => 'No hay propietario en sesión']);
        }

        $vehiculos = Vehiculos::where('propietario_id', $propietario['id'])
            ->with(['marca', 'modelo', 'conductor'])
            ->get();

        return response()->json([
            'propietario' => $propietario,
            'total_vehiculos' => $vehiculos->count(),
            'vehiculos' => $vehiculos->map(function ($v) {
                return $v->getCollectionData();
            })
        ]);
    }

    /**
     * Ver permisos de todos los vehículos del propietario
     */
    public function permisos(Request $request)
    {
        $propietario = session('taxis_user');

        // Query base para permisos del propietario
        $query = PermisoUnidad::whereHas('datosVehiculo', function ($q) use ($propietario) {
            $q->where('propietario_id', $propietario['id']);
        })->with(['datosVehiculo.marca', 'datosVehiculo.modelo', 'creator']);

        // Aplicar filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('tipo_permiso')) {
            $query->where('tipo_permiso', 'like', '%' . $request->tipo_permiso . '%');
        }

        if ($request->filled('vehiculo_id')) {
            $query->where('vehiculo_id', $request->vehiculo_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('motivo', 'like', "%{$search}%")
                    ->orWhere('tipo_permiso', 'like', "%{$search}%")
                    ->orWhere('observaciones', 'like', "%{$search}%")
                    ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(vehiculo, '$.placa')) LIKE ?", ["%{$search}%"])
                    ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(vehiculo, '$.numero_interno')) LIKE ?", ["%{$search}%"]);
            });
        }

        // Obtener permisos con paginación
        $permisos = $query->orderBy('created_at', 'desc')->paginate(12)->appends($request->query());

        // Obtener vehículos para el filtro
        $vehiculos = Vehiculos::where('propietario_id', $propietario['id'])
            ->with(['marca', 'modelo'])
            ->orderBy('placa')
            ->get();

        return view('taxis::propietario.permisos.index', compact('propietario', 'permisos', 'vehiculos'));
    }

    /**
     * API: Obtener permisos del propietario
     */
    public function permisosRecords(Request $request)
    {
        $propietario = session('taxis_user');

        $records = PermisoUnidad::whereHas('datosVehiculo', function ($q) use ($propietario) {
            $q->where('propietario_id', $propietario['id']);
        })->with(['datosVehiculo'])->orderBy('id', 'desc');

        return new PermisoUnidadCollection($records->paginate(config('tenant.items_per_page')));
    }

    /**
     * Ver servicios de todos los vehículos del propietario
     */
    public function servicios(Request $request)
    {
        $propietario = session('taxis_user');

        // Query base para servicios del propietario
        $query = \App\Models\Tenant\VehicleService::whereHas('vehiculo', function ($q) use ($propietario) {
            $q->where('propietario_id', $propietario['id']);
        })->with(['vehiculo.marca', 'vehiculo.modelo']);

        // Aplicar filtros
        if ($request->filled('estado')) {
            if ($request->estado === 'vencido') {
                $query->where('expires_date', '<', now());
            } elseif ($request->estado === 'proximo_vencer') {
                $query->whereBetween('expires_date', [now(), now()->addDays(30)]);
            } elseif ($request->estado === 'vigente') {
                $query->where('expires_date', '>', now()->addDays(30));
            }
        }

        if ($request->filled('tipo_servicio')) {
            $query->where('name', 'like', '%' . $request->tipo_servicio . '%');
        }

        if ($request->filled('vehiculo_id')) {
            $query->where('device_id', $request->vehiculo_id);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%')
                    ->orWhereHas('vehiculo', function ($vehicleQuery) use ($search) {
                        $vehicleQuery->where('placa', 'like', '%' . $search . '%');
                    });
            });
        }

        // Obtener servicios con paginación
        $servicios = $query->orderBy('expires_date', 'asc')->paginate(12)->appends($request->query());

        // Obtener vehículos para el filtro
        $vehiculos = Vehiculos::where('propietario_id', $propietario['id'])
            ->with(['marca', 'modelo'])
            ->orderBy('placa')
            ->get();

        // Obtener tipos de servicios únicos para filtro
        $tiposServiciosRaw = \App\Models\Tenant\VehicleService::whereHas('vehiculo', function ($q) use ($propietario) {
            $q->where('propietario_id', $propietario['id']);
        })->distinct()->pluck('name')->filter()->sort()->values();

        // Formatear los tipos de servicios para el filtro
        $tiposServicios = $tiposServiciosRaw->mapWithKeys(function ($tipo) {
            return [$tipo => $this->formatearNombreServicio($tipo)];
        });

        return view('taxis::propietario.servicios.index', compact('propietario', 'servicios', 'vehiculos', 'tiposServicios'));
    }

    /**
     * Ver perfil del propietario
     */
    public function perfil()
    {
        $propietario = session('taxis_user');

        // Obtener el modelo completo del propietario con relaciones
        $propietarioModel = \App\Models\Tenant\Taxis\Propietarios::with([
            'identity_document_type',
            'country',
            'department',
            'province',
            'district'
        ])->find($propietario['id']);

        // Obtener catálogos para los select
        $identity_document_types = \App\Models\Tenant\Catalogs\IdentityDocumentType::where('active', true)->get();
        $countries = \App\Models\Tenant\Catalogs\Country::where('active', true)->get();
        $departments = \App\Models\Tenant\Catalogs\Department::where('active', true)->get();
        $provinces = [];
        $districts = [];

        if ($propietarioModel->department_id) {
            $provinces = \App\Models\Tenant\Catalogs\Province::where('department_id', $propietarioModel->department_id)
                ->where('active', true)->get();
        }

        if ($propietarioModel->province_id) {
            $districts = \App\Models\Tenant\Catalogs\District::where('province_id', $propietarioModel->province_id)
                ->where('active', true)->get();
        }

        return view('taxis::propietario.perfil.show', compact(
            'propietarioModel',
            'identity_document_types',
            'countries',
            'departments',
            'provinces',
            'districts'
        ));
    }

    /**
     * Actualizar perfil del propietario
     */
    public function actualizarPerfil(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'number' => 'required|string|max:20',
            'identity_document_type_id' => 'required|exists:identity_document_types,id',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'telephone_1' => 'nullable|string|max:20',
            'telephone_2' => 'nullable|string|max:20',
            'telephone_3' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'address' => 'nullable|string|max:255',
            'country_id' => 'required|exists:countries,id',
            'department_id' => 'required|exists:departments,id',
            'province_id' => 'required|exists:provinces,id',
            'district_id' => 'required|exists:districts,id',
            'password' => 'nullable|string|min:6|confirmed',
        ]);

        $propietario = session('taxis_user');

        // Actualizar información del propietario
        $propietarioModel = \App\Models\Tenant\Taxis\Propietarios::find($propietario['id']);
        if ($propietarioModel) {
            $dataToUpdate = $request->only([
                'name',
                'number',
                'identity_document_type_id',
                'fecha_nacimiento',
                'telephone_1',
                'telephone_2',
                'telephone_3',
                'email',
                'address',
                'country_id',
                'department_id',
                'province_id',
                'district_id'
            ]);

            // Solo actualizar contraseña si se proporcionó
            if ($request->filled('password')) {
                $dataToUpdate['password'] = $request->password;
            }

            $propietarioModel->update($dataToUpdate);

            // Actualizar sesión con datos frescos
            $propietarioFresh = $propietarioModel->fresh();
            session(['taxis_user' => $propietarioFresh->getCollectionData()]);
        }

        return redirect()->route('taxis.propietario.perfil')
            ->with('success', 'Perfil actualizado correctamente.');
    }

    /**
     * Detalle de un vehículo específico
     */
    public function vehiculoDetalle($id)
    {
        $propietario = session('taxis_user');

        $vehiculo = Vehiculos::where('id', $id)
            ->where('propietario_id', $propietario['id'])
            ->with(['marca', 'modelo', 'conductor'])
            ->firstOrFail();

        return view('taxis::propietario.vehiculos.show', compact('vehiculo'));
    }






    /**
     * Descargar PDF de permiso (solo para permisos de vehículos del propietario)
     */
    public function descargarPermiso($permisoId)
    {
        $propietario = session('taxis_user');

        // Verificar que el permiso pertenece a un vehículo del propietario
        $permiso = PermisoUnidad::whereHas('datosVehiculo', function ($q) use ($propietario) {
            $q->where('propietario_id', $propietario['id']);
        })->findOrFail($permisoId);

        $pdfController = new PdfController();
        return $pdfController->permisoViaje($permiso->id);
    }

    /**
     * Obtener configuración de pago Yape
     */
    public function getPaymentConfiguration()
    {
        try {
            $config = \Modules\Payment\Models\PaymentConfiguration::first();

            if (!$config || !$config->enabled_yape) {
                return response()->json([
                    'success' => false,
                    'message' => 'El pago por Yape no está habilitado'
                ], 400);
            }

            return response()->json([
                'success' => true,
                'data' => [
                    'name_yape' => $config->name_yape,
                    'telephone_yape' => $config->telephone_yape,
                    'image_url_yape' => $config->getImageUrlYapeAttribute(),
                    'enabled_yape' => $config->enabled_yape
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al obtener configuración de pago'
            ], 500);
        }
    }

    /**
     * Verificar pago Yape
     */
    public function verificarYape(Request $request)
    {
        try {
            // Validación condicional para código de seguridad
            $rules = [
                'titular_yape' => 'required|string|max:255',
                'vehiculo_id' => 'required|integer',
                'mes' => 'required|integer|between:1,12',
                'year' => 'required|integer|min:2020',
                'monto' => 'required|numeric|min:0',
                'desde_yape' => 'boolean',
                'fecha_pago' => 'nullable|date',
                'busqueda_flexible' => 'boolean'
            ];

            // Si desde_yape es true, el código de seguridad es requerido
            if ($request->input('desde_yape', false)) {
                $rules['codigo_seguridad'] = 'required|string|max:50';
            } else {
                $rules['codigo_seguridad'] = 'nullable|string|max:50';
            }

            $validated = $request->validate($rules);

            $propietario = session('taxis_user');

            // Verificar que el vehículo pertenece al propietario
            $vehiculo = Vehiculos::where('id', $validated['vehiculo_id'])
                ->where('propietario_id', $propietario['id'])
                ->first();

            if (!$vehiculo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehículo no encontrado o no autorizado'
                ], 404);
            }

            // Determinar la fecha del pago
            $fechaPago = $validated['fecha_pago'] ?? now()->format('Y-m-d');

            // Determinar si se debe incluir código de seguridad en la búsqueda
            $codigoSeguridad = null;
            if ($validated['desde_yape'] && !empty($validated['codigo_seguridad'])) {
                $codigoSeguridad = $validated['codigo_seguridad'];
            }

            // Usar el modelo YapeNotification con búsqueda flexible y código de seguridad
            $notifications = YapeNotification::findCompatibleNotifications(
                $validated['titular_yape'],
                $validated['monto'],
                $fechaPago,
                $validated['busqueda_flexible'] ?? true,
                $codigoSeguridad
            );

            if ($notifications->isEmpty()) {
                $mensaje = 'No se encontró ningún pago disponible con ese titular, monto y fecha.';
                if ($validated['desde_yape'] && !empty($validated['codigo_seguridad'])) {
                    $mensaje .= ' Verifica que el código de seguridad sea correcto.';
                }
                $mensaje .= ' Verifica los datos ingresados o contacta por WhatsApp si ya realizaste el pago.';

                return response()->json([
                    'success' => false,
                    'message' => $mensaje
                ], 404);
            }

            // Tomar la primera notificación (más reciente)
            $notification = $notifications->first();

            // Si NO se usó desde_yape pero se proporciona código de seguridad, verificarlo manualmente
            if (!$validated['desde_yape'] && !empty($validated['codigo_seguridad'])) {
                $hasSecurityCode = collect($notifications)->filter(function ($notif) use ($validated) {
                    return stripos(json_encode($notif->raw_notification), $validated['codigo_seguridad']) !== false;
                })->first();

                if (!$hasSecurityCode) {
                    return response()->json([
                        'success' => false,
                        'message' => 'El código de seguridad no coincide con ningún pago encontrado.'
                    ], 400);
                }

                $notification = $hasSecurityCode;
            }

            return response()->json([
                'success' => true,
                'message' => 'Pago verificado correctamente',
                'data' => [
                    'notification_id' => $notification->id,
                    'sender' => $notification->sender,
                    'amount' => $notification->amount,
                    'date' => $notification->notification_date->format('d/m/Y H:i'),
                    'is_used' => $notification->is_used,
                    'used_for_invoice' => $notification->subscription_invoice_id
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Datos inválidos',
                'errors' => $e->errors()
            ], 422);
        }
    }

    /**
     * Confirmar y registrar pago después de verificación
     */
    public function confirmarPagoYape(Request $request)
    {

        try {
            $validated = $request->validate([
                'vehiculoId' => 'required|integer',
                'mes' => 'required|integer|between:1,12',
                'year' => 'required|integer|min:2020',
                'monto' => 'required|numeric|min:0',
                'fecha' => 'required|date',
                'descuento' => 'nullable|numeric|min:0',
                'moneda' => 'required|string|size:3',
                'tipo' => 'required|string',
                'color' => 'required|string',
                'metodo_pago' => 'required|string',
                'notification_id' => 'required|integer',
                'titular_yape' => 'required|string|max:255'
            ]);

            $propietario = session('taxis_user');

            // Verificar que el vehículo pertenece al propietario
            $vehiculo = Vehiculos::where('id', $validated['vehiculoId'])
                ->where('propietario_id', $propietario['id'])
                ->first();

            if (!$vehiculo) {
                return response()->json([
                    'success' => false,
                    'message' => 'Vehículo no encontrado o no autorizado'
                ], 404);
            }

            // Verificar que no exista ya un pago para ese mes/año
            $pagoExistente = SubscriptionInvoice::where('vehiculo_id', $validated['vehiculoId'])
                ->where('mes', $validated['mes'])
                ->where('year', $validated['year'])
                ->first();

            if ($pagoExistente) {
                return response()->json([
                    'success' => false,
                    'message' => 'Ya existe un pago registrado para este mes y año'
                ], 400);
            }

            // Verificar que la notificación existe y no ha sido utilizada
            $notification = YapeNotification::find($validated['notification_id']);
            if (!$notification) {
                return response()->json([
                    'success' => false,
                    'message' => 'Notificación de pago no encontrada'
                ], 404);
            }

            // Verificar si la notificación ya fue utilizada
            if ($notification->is_used) {
                return response()->json([
                    'success' => false,
                    'message' => 'Esta notificación de pago ya ha sido utilizada para otro registro. No se puede usar el mismo pago para múltiples facturas.'
                ], 400);
            }

            // Crear el registro de pago usando solo campos que existen en la tabla
            $invoice = SubscriptionInvoice::create([
                'vehiculo_id' => $validated['vehiculoId'],
                'subscription_id' => $vehiculo->subscription_id,
                'year' => $validated['year'],
                'mes' => $validated['mes'],
                'monto' => $validated['monto'],
                'fecha_cobro' => $validated['fecha'],
                'descuento' => $validated['descuento'] ?? 0,
                'moneda' => $validated['moneda'],
                'tipo' => $validated['tipo'],
                'metodo_pago' => $validated['metodo_pago'],
                'estado' => 'pagado',
                'payed_total' => true,
            ]);

            // Marcar la notificación como utilizada y asociarla con la factura
            $notification->markAsUsed($invoice->id);

            // Registrar el color del pago
            PaymentColor::updateOrCreate(
                [
                    'colorable_id' => $validated['vehiculoId'],
                    'colorable_type' => get_class($vehiculo),
                    'year' => $validated['year'],
                    'month' => $validated['mes'],
                ],
                ['color' => $validated['color']]
            );

            return response()->json([
                'success' => true,
                'message' => 'Pago registrado correctamente',
                'data' => [
                    'id' => $invoice->id,
                    'monto' => $invoice->monto,
                    'fecha' => $invoice->fecha_cobro,
                    'estado' => $invoice->estado,
                    'mes' => $invoice->mes,
                    'year' => $invoice->year
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
                'message' => 'Error al registrar el pago: ' . $e->getMessage()
            ], 500);
        }
    }
}
