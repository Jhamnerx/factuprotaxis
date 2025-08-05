<?php

namespace Modules\Taxis\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Tenant\Company;
use Barryvdh\DomPDF\Facade as PDF;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Taxis\Vehiculos;
use App\Models\Tenant\Taxis\Conductor;
use App\Models\Tenant\Taxis\PermisoUnidad;
use App\Http\Controllers\Tenant\PdfController;
use App\Http\Resources\Tenant\VehiculoResource;
use Modules\Payment\Models\SubscriptionInvoice;
use App\Http\Resources\Tenant\SubscriptionCollection;
use App\Http\Resources\Tenant\PermisoUnidadCollection;

class ConductorController extends Controller
{
    /**
     * Dashboard del conductor
     */
    public function dashboard()
    {
        $conductor = session('taxis_user');

        // Obtener el vehículo asignado al conductor
        $vehiculo = Vehiculos::where('conductor_id', $conductor['id'])
            ->where('estado', 'ACTIVO')
            ->with(['propietario', 'marca', 'modelo'])
            ->first();

        // Estadísticas básicas
        $stats = [
            'vehiculo_asignado' => $vehiculo ? true : false,
            'permisos_vigentes' => 0,
            'pagos_pendientes' => 0,
            'servicios_programados' => 0
        ];

        if ($vehiculo) {
            // Contar permisos vigentes
            $stats['permisos_vigentes'] = PermisoUnidad::where('vehiculo_id', $vehiculo->id)
                ->where('estado', 'vigente')
                ->where('fecha_fin', '>=', now())
                ->count();

            // Contar pagos pendientes
            $stats['pagos_pendientes'] = SubscriptionInvoice::where('vehiculo_id', $vehiculo->id)
                ->where('estado', 'pendiente')
                ->count();
        }

        return view('taxis::conductor.dashboard', compact('conductor', 'vehiculo', 'stats'));
    }

    /**
     * Ver información del vehículo asignado
     */
    public function vehiculo()
    {
        $conductor = session('taxis_user');

        $vehiculo = Vehiculos::where('conductor_id', $conductor['id'])
            ->where('estado', 'ACTIVO')
            ->with(['propietario', 'marca', 'modelo'])
            ->first();

        if (!$vehiculo) {
            return redirect()->route('taxis.conductor.dashboard')
                ->with('error', 'No tienes un vehículo asignado.');
        }

        return view('taxis::conductor.vehiculo.show', compact('vehiculo'));
    }

    /**
     * Ver permisos del vehículo
     */
    public function permisos(Request $request)
    {
        $conductor = session('taxis_user');

        $vehiculo = Vehiculos::where('conductor_id', $conductor['id'])
            ->where('estado', 'ACTIVO')
            ->first();

        if (!$vehiculo) {
            return redirect()->route('taxis.conductor.dashboard')
                ->with('error', 'No tienes un vehículo asignado.');
        }

        // Query base para permisos del vehículo del conductor
        $query = PermisoUnidad::where('vehiculo_id', $vehiculo->id)
            ->with(['creator']);

        // Aplicar filtros
        if ($request->filled('estado')) {
            $query->where('estado', $request->estado);
        }

        if ($request->filled('tipo_permiso')) {
            $query->where('tipo_permiso', 'like', '%' . $request->tipo_permiso . '%');
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

        return view('taxis::conductor.permisos.index', compact('vehiculo', 'permisos'));
    }

    /**
     * Descargar permiso en PDF
     */
    public function descargarPermiso($permisoId)
    {
        $conductor = session('taxis_user');

        // Obtener el vehículo del conductor
        $vehiculo = Vehiculos::where('conductor_id', $conductor['id'])
            ->where('estado', 'ACTIVO')
            ->first();

        if (!$vehiculo) {
            abort(403, 'No tienes un vehículo asignado.');
        }

        // Verificar que el permiso pertenece al vehículo del conductor
        $permiso = PermisoUnidad::where('vehiculo_id', $vehiculo->id)
            ->findOrFail($permisoId);

        $pdfController = new PdfController();
        return $pdfController->permisoViaje($permiso->id);
    }

    /**
     * API: Obtener permisos del vehículo del conductor
     */
    public function permisosRecords(Request $request)
    {
        $conductor = session('taxis_user');

        $vehiculo = Vehiculos::where('conductor_id', $conductor['id'])
            ->where('estado', 'ACTIVO')
            ->first();

        if (!$vehiculo) {
            return response()->json(['data' => [], 'total' => 0]);
        }

        $records = PermisoUnidad::where('vehiculo_id', $vehiculo->id);

        // Aplicar filtros
        if ($request->filled('search')) {
            $search = $request->search;
            $records->where(function ($q) use ($search) {
                $q->where('motivo', 'like', "%{$search}%")
                    ->orWhere('tipo_permiso', 'like', "%{$search}%")
                    ->orWhere('observaciones', 'like', "%{$search}%");
            });
        }

        if ($request->filled('estado')) {
            $records->where('estado', $request->estado);
        }

        if ($request->filled('tipo_permiso')) {
            $records->where('tipo_permiso', 'like', '%' . $request->tipo_permiso . '%');
        }

        $records->orderBy('id', 'desc');

        $collection = new PermisoUnidadCollection($records->paginate(config('tenant.items_per_page')));

        // Modificar las URLs de descarga para usar la ruta del conductor
        $collection = $collection->toResponse($request)->getData(true);

        if (isset($collection['data']) && is_array($collection['data'])) {
            foreach ($collection['data'] as &$permiso) {
                if (isset($permiso['id'])) {
                    $permiso['download_permiso'] = route('taxis.conductor.pdf.permiso', $permiso['id']);
                }
            }
        }

        return response()->json($collection);
    }

    /**
     * Ver pagos del vehículo
     */
    public function pagos(Request $request)
    {
        $conductor = session('taxis_user');

        $vehiculo = Vehiculos::where('conductor_id', $conductor['id'])
            ->where('estado', 'ACTIVO')
            ->first();

        if (!$vehiculo) {
            return redirect()->route('taxis.conductor.dashboard')
                ->with('error', 'No tienes un vehículo asignado.');
        }

        return view('taxis::conductor.pagos.index', compact('vehiculo'));
    }

    /**
     * API: Obtener pagos del vehículo del conductor
     */
    public function pagosRecords(Request $request)
    {
        $conductor = session('taxis_user');

        $vehiculo = Vehiculos::where('conductor_id', $conductor['id'])
            ->where('estado', 'ACTIVO')
            ->first();

        if (!$vehiculo) {
            return response()->json(['data' => [], 'total' => 0]);
        }

        $records = SubscriptionInvoice::where('vehiculo_id', $vehiculo->id);

        // Filtrar por vehículo específico si se proporciona (aunque el conductor solo tiene uno)
        if ($request->filled('vehiculo_id')) {
            $records->where('vehiculo_id', $request->vehiculo_id);
        }

        $records->orderBy('year', 'desc')->orderBy('mes', 'desc');

        return new SubscriptionCollection($records->paginate(config('tenant.items_per_page')));
    }

    /**
     * API: Registrar nuevo pago del conductor
     */
    public function registrarPago(Request $request)
    {
        $conductor = session('taxis_user');

        $validated = $request->validate([
            'mes' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'monto' => 'required|numeric|min:0',
            'metodo_pago' => 'required|string',
            'estado' => 'string'
        ]);

        // Obtener el vehículo del conductor
        $vehiculo = Vehiculos::where('conductor_id', $conductor['id'])
            ->where('estado', 'ACTIVO')
            ->firstOrFail();

        $pago = SubscriptionInvoice::create([
            'vehiculo_id' => $vehiculo->id,
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
     * Obtener configuración de pago
     */
    public function getPaymentConfiguration()
    {
        $conductor = session('taxis_user');

        $vehiculo = Vehiculos::where('conductor_id', $conductor['id'])
            ->where('estado', 'ACTIVO')
            ->with(['subscription.plan'])
            ->first();

        if (!$vehiculo) {
            return response()->json(['error' => 'No tienes un vehículo asignado'], 404);
        }

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
                'vehiculo' => new \App\Http\Resources\Tenant\VehiculoResource($vehiculo),
                'yape_config' => [
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
     * Verificar pago con Yape
     */
    public function verificarYape(Request $request)
    {
        $conductor = session('taxis_user');

        $request->validate([
            'titular_yape' => 'required|string',
            'codigo_seguridad' => 'required|string',
            'mes' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020',
            'desde_yape' => 'boolean',
            'pago_dias_anteriores' => 'boolean',
            'fecha_pago' => 'nullable|date'
        ]);

        $vehiculo = Vehiculos::where('conductor_id', $conductor['id'])
            ->where('estado', 'ACTIVO')
            ->firstOrFail();

        // Buscar notificación de Yape
        $notificacion = \App\Models\Tenant\YapeNotification::where('phone', $request->titular_yape)
            ->where('code', $request->codigo_seguridad)
            ->where('used', false)
            ->first();

        if (!$notificacion) {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró una transacción de Yape con los datos proporcionados.'
            ], 404);
        }

        // Verificar que no esté vencida (ejemplo: 24 horas)
        if ($notificacion->created_at->addHours(24) < now()) {
            return response()->json([
                'success' => false,
                'message' => 'La transacción de Yape ha expirado.'
            ], 400);
        }

        return response()->json([
            'success' => true,
            'notification_id' => $notificacion->id,
            'amount' => $notificacion->amount,
            'date' => $notificacion->date,
            'message' => 'Transacción de Yape verificada correctamente.'
        ]);
    }

    /**
     * Confirmar pago con Yape
     */
    public function confirmarPagoYape(Request $request)
    {
        $conductor = session('taxis_user');

        $request->validate([
            'notification_id' => 'required|exists:yape_notifications,id',
            'mes' => 'required|integer|between:1,12',
            'year' => 'required|integer|min:2020'
        ]);

        $vehiculo = Vehiculos::where('conductor_id', $conductor['id'])
            ->where('estado', 'ACTIVO')
            ->firstOrFail();

        $notificacion = \App\Models\Tenant\YapeNotification::findOrFail($request->notification_id);

        // Verificar que la notificación no esté usada
        if ($notificacion->used) {
            return response()->json([
                'success' => false,
                'message' => 'Esta transacción de Yape ya ha sido utilizada.'
            ], 400);
        }

        // Verificar si ya existe un pago para este mes/año
        $pagoExistente = SubscriptionInvoice::where('vehiculo_id', $vehiculo->id)
            ->where('mes', $request->mes)
            ->where('year', $request->year)
            ->first();

        if ($pagoExistente) {
            return response()->json([
                'success' => false,
                'message' => 'Ya existe un pago registrado para este mes y año.'
            ], 400);
        }

        // Crear el pago
        $pago = SubscriptionInvoice::create([
            'vehiculo_id' => $vehiculo->id,
            'mes' => $request->mes,
            'year' => $request->year,
            'monto' => $notificacion->amount,
            'metodo_pago' => 'yape',
            'estado' => 'pagado',
            'fecha_cobro' => now(),
            'tipo' => 'mensual',
            'yape_notification_id' => $notificacion->id
        ]);

        // Marcar la notificación como usada
        $notificacion->update(['used' => true]);

        return response()->json([
            'success' => true,
            'pago' => $pago,
            'message' => 'Pago registrado exitosamente con Yape.'
        ]);
    }

    /**
     * Ver servicios del vehículo
     */
    public function servicios(Request $request)
    {
        $conductor = session('taxis_user');

        $vehiculo = Vehiculos::where('conductor_id', $conductor['id'])
            ->where('estado', 'ACTIVO')
            ->first();

        if (!$vehiculo) {
            return redirect()->route('taxis.conductor.dashboard')
                ->with('error', 'No tienes un vehículo asignado.');
        }

        // Query base para servicios del vehículo del conductor
        $query = \App\Models\Tenant\VehicleService::where('device_id', $vehiculo->id)
            ->with(['vehiculo.marca', 'vehiculo.modelo']);

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

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                    ->orWhere('description', 'like', '%' . $search . '%');
            });
        }

        // Obtener servicios con paginación
        $servicios = $query->orderBy('expires_date', 'asc')->paginate(12)->appends($request->query());

        // Obtener tipos de servicios únicos para filtro
        $tiposServiciosRaw = \App\Models\Tenant\VehicleService::where('device_id', $vehiculo->id)
            ->distinct()->pluck('name')->filter()->sort()->values();

        // Formatear los tipos de servicios para el filtro
        $tiposServicios = $tiposServiciosRaw->mapWithKeys(function ($tipo) {
            return [$tipo => $this->formatearNombreServicio($tipo)];
        });

        return view('taxis::conductor.servicios.index', compact('vehiculo', 'servicios', 'tiposServicios'));
    }

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
     * API: Obtener servicios del vehículo del conductor
     */
    public function serviciosRecords(Request $request)
    {
        $conductor = session('taxis_user');

        $vehiculo = Vehiculos::where('conductor_id', $conductor['id'])
            ->where('estado', 'ACTIVO')
            ->first();

        if (!$vehiculo) {
            return response()->json([
                'data' => [],
                'message' => 'No tienes un vehículo asignado.'
            ]);
        }

        $records = \App\Models\Tenant\Taxis\Servicios::where('vehiculo_id', $vehiculo->id)
            ->with(['vehiculo'])
            ->orderBy('id', 'desc');

        return new \App\Http\Resources\Tenant\ServicioCollection($records->paginate(config('tenant.items_per_page')));
    }

    /**
     * Ver perfil del conductor
     */
    public function perfil()
    {
        $conductor = session('taxis_user');

        if (!$conductor) {
            return redirect()->route('taxis.login')
                ->with('error', 'Debes iniciar sesión como conductor.');
        }

        // Verificar que el conductor existe en la base de datos
        $conductorModel = Conductor::find($conductor['id']);

        if (!$conductorModel) {
            return redirect()->route('taxis.login')
                ->with('error', 'No se pudo encontrar la información del conductor.');
        }

        // Usar los datos frescos de la base de datos
        $conductor = $conductorModel->toArray();

        return view('taxis::conductor.perfil.show', compact('conductor'));
    }

    /**
     * Actualizar perfil del conductor
     */
    public function actualizarPerfil(Request $request)
    {
        $request->validate([
            'telephone_1' => 'nullable|string|max:20',
            'telephone_2' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:500',
            'fecha_nacimiento' => 'nullable|date|before:today',
            'licencia_numero' => 'nullable|string|max:50',
            'licencia_vencimiento' => 'nullable|date',
            'password' => 'nullable|string|min:6|confirmed',
            'password_confirmation' => 'nullable|required_with:password'
        ], [
            'fecha_nacimiento.before' => 'La fecha de nacimiento debe ser anterior a hoy.',
            'password.min' => 'La contraseña debe tener al menos 6 caracteres.',
            'password.confirmed' => 'La confirmación de contraseña no coincide.',
            'password_confirmation.required_with' => 'Debes confirmar la nueva contraseña.'
        ]);

        $conductor = session('taxis_user');

        // Buscar el conductor en la base de datos
        $conductorModel = Conductor::find($conductor['id']);

        if (!$conductorModel) {
            return redirect()->route('taxis.conductor.perfil')
                ->with('error', 'No se pudo encontrar la información del conductor.');
        }

        // Preparar datos para actualizar (campos básicos)
        $updateData = $request->only([
            'telephone_1',
            'telephone_2',
            'address',
            'fecha_nacimiento'
        ]);

        // Manejar la información de licencia como array
        if ($request->filled(['licencia_numero', 'licencia_vencimiento'])) {
            $licenciaData = [
                'numero' => $request->licencia_numero,
                'vencimiento' => $request->licencia_vencimiento
            ];
            $updateData['licencia'] = $licenciaData;
        } elseif ($request->filled('licencia_numero')) {
            // Si solo se proporciona el número, conservar vencimiento existente
            $licenciaExistente = $conductorModel->licencia ?? [];
            $updateData['licencia'] = [
                'numero' => $request->licencia_numero,
                'vencimiento' => $licenciaExistente['vencimiento'] ?? null
            ];
        } elseif ($request->filled('licencia_vencimiento')) {
            // Si solo se proporciona vencimiento, conservar número existente
            $licenciaExistente = $conductorModel->licencia ?? [];
            $updateData['licencia'] = [
                'numero' => $licenciaExistente['numero'] ?? null,
                'vencimiento' => $request->licencia_vencimiento
            ];
        }

        // Manejar el cambio de contraseña si se proporciona
        if ($request->filled('password')) {
            $updateData['password'] = bcrypt($request->password);
        }

        // Actualizar información del conductor
        $conductorModel->update($updateData);

        // Actualizar la sesión con los nuevos datos
        $conductorActualizado = $conductorModel->fresh()->toArray();
        session(['taxis_user' => $conductorActualizado]);

        return redirect()->route('taxis.conductor.perfil')
            ->with('success', 'Perfil actualizado correctamente.');
    }
}
