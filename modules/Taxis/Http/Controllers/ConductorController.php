<?php

namespace Modules\Taxis\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use App\Models\Tenant\Taxis\Vehiculos;
use App\Models\Tenant\Taxis\PermisoUnidad;
use Modules\Payment\Models\SubscriptionInvoice;
use App\Http\Resources\Tenant\VehiculoResource;
use App\Http\Resources\Tenant\PermisoUnidadCollection;
use App\Http\Resources\Tenant\SubscriptionCollection;

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
            ->where('estado', 'activo')
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
            ->where('estado', 'activo')
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
    public function permisos()
    {
        $conductor = session('taxis_user');

        $vehiculo = Vehiculos::where('conductor_id', $conductor['id'])
            ->where('estado', 'activo')
            ->first();

        if (!$vehiculo) {
            return redirect()->route('taxis.conductor.dashboard')
                ->with('error', 'No tienes un vehículo asignado.');
        }

        return view('taxis::conductor.permisos.index', compact('vehiculo'));
    }

    /**
     * API: Obtener permisos del vehículo del conductor
     */
    public function permisosRecords(Request $request)
    {
        $conductor = session('taxis_user');

        $vehiculo = Vehiculos::where('conductor_id', $conductor['id'])
            ->where('estado', 'activo')
            ->first();

        if (!$vehiculo) {
            return response()->json(['data' => [], 'total' => 0]);
        }

        $records = PermisoUnidad::where('vehiculo_id', $vehiculo->id)
            ->orderBy('id', 'desc');

        return new PermisoUnidadCollection($records->paginate(config('tenant.items_per_page')));
    }

    /**
     * Ver pagos del vehículo
     */
    public function pagos()
    {
        $conductor = session('taxis_user');

        $vehiculo = Vehiculos::where('conductor_id', $conductor['id'])
            ->where('estado', 'activo')
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
            ->where('estado', 'activo')
            ->first();

        if (!$vehiculo) {
            return response()->json(['data' => [], 'total' => 0]);
        }

        $records = SubscriptionInvoice::where('vehiculo_id', $vehiculo->id)
            ->orderBy('id', 'desc');

        return new SubscriptionCollection($records->paginate(config('tenant.items_per_page')));
    }

    /**
     * Ver servicios del vehículo
     */
    public function servicios()
    {
        $conductor = session('taxis_user');

        $vehiculo = Vehiculos::where('conductor_id', $conductor['id'])
            ->where('estado', 'activo')
            ->first();

        if (!$vehiculo) {
            return redirect()->route('taxis.conductor.dashboard')
                ->with('error', 'No tienes un vehículo asignado.');
        }

        return view('taxis::conductor.servicios.index', compact('vehiculo'));
    }

    /**
     * Ver perfil del conductor
     */
    public function perfil()
    {
        $conductor = session('taxis_user');
        return view('taxis::conductor.perfil.show', compact('conductor'));
    }

    /**
     * Actualizar perfil del conductor
     */
    public function actualizarPerfil(Request $request)
    {
        $request->validate([
            'telefono' => 'nullable|string|max:20',
            'direccion' => 'nullable|string|max:255',
            'fecha_nacimiento' => 'nullable|date',
            'licencia_numero' => 'nullable|string|max:50',
            'licencia_vencimiento' => 'nullable|date'
        ]);

        $conductor = session('taxis_user');

        // Actualizar información del conductor
        $conductorModel = \App\Models\Tenant\Taxis\Conductores::find($conductor['id']);
        if ($conductorModel) {
            $conductorModel->update($request->only([
                'telefono',
                'direccion',
                'fecha_nacimiento',
                'licencia_numero',
                'licencia_vencimiento'
            ]));

            // Actualizar sesión
            session(['taxis_user' => $conductorModel->toArray()]);
        }

        return redirect()->route('taxis.conductor.perfil')
            ->with('success', 'Perfil actualizado correctamente.');
    }
}
