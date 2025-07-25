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

class PropietarioController extends Controller
{
    /**
     * Dashboard del propietario
     */
    public function dashboard()
    {
        $propietario = session('taxis_user');

        // Estadísticas del propietario
        $stats = [
            'total_vehiculos' => Vehiculos::where('propietario_id', $propietario['id'])->count(),
            'vehiculos_activos' => Vehiculos::where('propietario_id', $propietario['id'])
                ->where('estado', 'activo')->count(),
            'total_conductores' => Conductores::whereHas('vehiculos', function ($q) use ($propietario) {
                $q->where('propietario_id', $propietario['id']);
            })->count(),
            'contratos_activos' => Contratos::where('propietario_id', $propietario['id'])
                ->where('estado', 'activo')->count(),
            'solicitudes_pendientes' => Solicitud::where('propietario_id', $propietario['id'])
                ->where('estado', 'pendiente')->count(),
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
    public function vehiculos()
    {
        $propietario = session('taxis_user');
        return view('taxis::propietario.vehiculos.index', compact('propietario'));
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
    public function conductores()
    {
        $propietario = session('taxis_user');
        return view('taxis::propietario.conductores.index', compact('propietario'));
    }

    /**
     * API: Obtener conductores del propietario
     */
    public function conductoresRecords(Request $request)
    {
        $propietario = session('taxis_user');

        $records = Conductores::whereHas('vehiculos', function ($q) use ($propietario) {
            $q->where('propietario_id', $propietario['id']);
        })->with(['vehiculos' => function ($q) use ($propietario) {
            $q->where('propietario_id', $propietario['id']);
        }])->orderBy('name');

        return new ConductorCollection($records->paginate(config('tenant.items_per_page')));
    }

    /**
     * Ver contratos del propietario
     */
    public function contratos()
    {
        $propietario = session('taxis_user');
        return view('taxis::propietario.contratos.index', compact('propietario'));
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
    public function solicitudes()
    {
        $propietario = session('taxis_user');
        return view('taxis::propietario.solicitudes.index', compact('propietario'));
    }

    /**
     * API: Obtener solicitudes del propietario
     */
    public function solicitudesRecords(Request $request)
    {
        $propietario = session('taxis_user');

        $records = Solicitud::where('propietario_id', $propietario['id'])
            ->orderBy('id', 'desc');

        return new SolicitudCollection($records->paginate(config('tenant.items_per_page')));
    }

    /**
     * Ver constancias del propietario
     */
    public function constancias()
    {
        $propietario = session('taxis_user');
        return view('taxis::propietario.constancias.index', compact('propietario'));
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
    public function permisos()
    {
        $propietario = session('taxis_user');
        return view('taxis::propietario.permisos.index', compact('propietario'));
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
    public function servicios()
    {
        $propietario = session('taxis_user');
        return view('taxis::propietario.servicios.index', compact('propietario'));
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
}
