<?php

namespace Modules\Taxis\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Taxis\Vehiculos;
use App\Models\Tenant\Taxis\Contratos;
use App\Models\Tenant\Taxis\Solicitud;
use App\Models\Tenant\Taxis\Conductores;
use App\Models\Tenant\Taxis\PermisoUnidad;
use App\Models\Tenant\Taxis\ConstanciaBaja;
use Modules\Payment\Models\SubscriptionInvoice;
use App\Http\Resources\Tenant\VehiculoCollection;
use App\Http\Resources\Tenant\ContratoCollection;
use App\Http\Resources\Tenant\SolicitudCollection;
use App\Http\Resources\Tenant\ConductorCollection;
use App\Http\Resources\Tenant\PermisoUnidadCollection;
use App\Http\Resources\Tenant\ConstanciaBajaCollection;
use App\Http\Resources\Tenant\SubscriptionCollection;
use App\Models\Tenant\Taxis\Conductor;

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
        $query = Contratos::where('propietario_id', $propietario['id'])
            ->with(['vehiculo.marca', 'vehiculo.modelo']);

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
                $q->whereHas('vehiculo', function ($vq) use ($search) {
                    $vq->where('placa', 'like', "%{$search}%")
                        ->orWhere('numero_interno', 'like', "%{$search}%");
                });
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

        // Query base para constancias del propietario
        $query = \App\Models\Tenant\Taxis\ConstanciaBaja::whereHas('datosVehiculo', function ($q) use ($propietario) {
            $q->where('propietario_id', $propietario['id']);
        })->with(['datosVehiculo.marca', 'datosVehiculo.modelo', 'creator']);

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
     * API: Obtener constancias del propietario
     */
    public function constanciasRecords(Request $request)
    {
        $propietario = session('taxis_user');

        $records = ConstanciaBaja::whereHas('datosVehiculo', function ($q) use ($propietario) {
            $q->where('propietario_id', $propietario['id']);
        })->orderBy('id', 'desc');

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
        })->with(['vehiculo'])->orderBy('id', 'desc');

        return new SubscriptionCollection($records->paginate(config('tenant.items_per_page')));
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
                    ->orWhere('observaciones', 'like', "%{$search}%");
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
     * API: Obtener servicios del propietario
     */
    public function serviciosRecords(Request $request)
    {
        $propietario = session('taxis_user');

        // Obtener servicios a través de los vehículos del propietario
        $records = \App\Models\Tenant\VehicleService::whereHas('vehiculo', function ($q) use ($propietario) {
            $q->where('propietario_id', $propietario['id']);
        })->with(['vehiculo'])->orderBy('id', 'desc');

        return new \App\Http\Resources\Tenant\ServicioCollection($records->paginate(config('tenant.items_per_page')));
    }

    /**
     * Ver perfil del propietario
     */
    public function perfil()
    {
        $propietario = session('taxis_user');
        return view('taxis::propietario.perfil.show', compact('propietario'));
    }

    /**
     * Actualizar perfil del propietario
     */
    public function actualizarPerfil(Request $request)
    {
        $request->validate([
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date'
        ]);

        $propietario = session('taxis_user');

        // Actualizar información del propietario
        $propietarioModel = \App\Models\Tenant\Taxis\Propietarios::find($propietario['id']);
        if ($propietarioModel) {
            $propietarioModel->update($request->only([
                'telefono',
                'direccion',
                'fecha_nacimiento'
            ]));

            // Actualizar sesión
            session(['taxis_user' => $propietarioModel->toArray()]);
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
     * Descargar PDF de contrato (solo para vehículos del propietario)
     */
    public function descargarContrato($vehiculoId)
    {
        $propietario = session('taxis_user');

        // Verificar que el vehículo pertenece al propietario
        $vehiculo = Vehiculos::where('id', $vehiculoId)
            ->where('propietario_id', $propietario['id'])
            ->firstOrFail();

        // Redirigir a la ruta principal de PDF del sistema
        return redirect()->route('tenant.pdf.contrato', ['vehiculo' => $vehiculo]);
    }

    /**
     * Descargar PDF de solicitud (solo para solicitudes relacionadas a vehículos del propietario)
     */
    public function descargarSolicitud($solicitudId)
    {
        $propietario = session('taxis_user');

        // Obtener los IDs de vehículos del propietario
        $vehiculosIds = Vehiculos::where('propietario_id', $propietario['id'])
            ->pluck('id')
            ->toArray();

        // Verificar que la solicitud tiene vehículos del propietario autenticado
        $solicitud = Solicitud::whereHas('detalle', function ($q) use ($vehiculosIds) {
            $q->whereIn('vehiculo_id', $vehiculosIds);
        })->findOrFail($solicitudId);

        // Redirigir a la ruta principal de PDF del sistema
        return redirect()->route('tenant.pdf.solicitud', ['solicitud' => $solicitud]);
    }

    /**
     * Descargar PDF de constancia (solo para constancias relacionadas a vehículos del propietario)
     */
    public function descargarConstancia($constanciaId)
    {
        $propietario = session('taxis_user');

        // Verificar que la constancia pertenece a un vehículo del propietario autenticado
        $constancia = \App\Models\Tenant\Taxis\ConstanciaBaja::whereHas('datosVehiculo', function ($q) use ($propietario) {
            $q->where('propietario_id', $propietario['id']);
        })->findOrFail($constanciaId);

        // Redirigir a la ruta principal de PDF del sistema
        return redirect()->route('tenant.pdf.constancia', ['constancia' => $constancia]);
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

        // Redirigir a la ruta principal de PDF del sistema
        return redirect()->route('tenant.pdf.permiso-viaje', ['permiso' => $permiso]);
    }
}
