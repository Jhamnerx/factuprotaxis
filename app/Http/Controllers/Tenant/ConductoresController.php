<?php

namespace App\Http\Controllers\Tenant;

use Exception;
use App\Traits\OfflineTrait;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Taxis\Conductor;
use App\Http\Requests\Tenant\ConductorRequest;
use App\Http\Resources\Tenant\ConductorResource;
use App\Http\Resources\Tenant\ConductorCollection;
use App\Jobs\Tenant\SendWelcomeMessageJob;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ConductoresController extends Controller
{
    use OfflineTrait;

    public function index()
    {
        return view('tenant.taxis.conductores.index');
    }

    public function columns()
    {
        return [
            'name' => 'Nombre',
            'number' => 'Número de documento',
            'fecha_nacimiento_formatted' => 'Fecha de Nacimiento',
            'edad' => 'Edad',
            'licencia' => 'Licencia',
            'address' => 'Dirección',
            'telephone_1' => 'Teléfono 1',
            'telephone_2' => 'Teléfono 2',
            'telephone_3' => 'Teléfono 3',
        ];
    }

    public function records(Request $request)
    {
        $records = Conductor::where($request->column, 'like', "%{$request->value}%")
            ->orderBy('name');

        return new ConductorCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function tables()
    {
        $configuration = Configuration::first();

        return compact('configuration');
    }

    public function record($id)
    {
        $record = new ConductorResource(Conductor::findOrFail($id));

        return $record;
    }

    public function store(ConductorRequest $request)
    {

        try {
            $id = $request->input('id');

            $conductor = Conductor::firstOrNew(['id' => $id]);
            $data = $request->all();
            unset($data['id']);
            $conductor->fill($data);
            $conductor->save();

            // Enviar mensaje de bienvenida si es un nuevo conductor
            if (!$id && $conductor->telephone_1) {
                // Buscar si el conductor tiene vehículo asignado para incluir datos del vehículo
                $vehicleData = null;
                if (method_exists($conductor, 'vehiculo') && $conductor->vehiculo()->exists()) {
                    $vehiculo = $conductor->vehiculo()->first();
                    $vehicleData = [
                        'numero_interno' => $vehiculo->numero_interno,
                        'placa' => $vehiculo->placa
                    ];
                }

                SendWelcomeMessageJob::dispatch('conductor', $conductor->toArray(), $vehicleData)
                    ->delay(now()->addMinutes(1));
            }

            $msg = ($id) ? 'Conductor editado con éxito' : 'Conductor registrado con éxito';

            return [
                'success' => true,
                'message' => $msg,
                'id' => $conductor->id
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error inesperado, no se pudo guardar el conductor: ' . $e->getMessage()
            ];
        }
    }

    public function destroy($id)
    {
        try {
            $conductor = Conductor::findOrFail($id);

            // Verificar si tiene vehículos asignados
            if ($conductor->hasVehiclesAssigned()) {
                return [
                    'success' => false,
                    'message' => 'El conductor no puede ser eliminado porque tiene vehículos asignados'
                ];
            }

            // Verificar si el conductor que se va a eliminar es el usuario autenticado
            $currentUser = Auth::guard('conductores')->user();
            $shouldLogout = false;
            if ($currentUser && $currentUser->id == $conductor->id) {
                $shouldLogout = true;
            }

            $conductor->delete();

            // Si se eliminó el conductor autenticado, cerrar su sesión
            if ($shouldLogout) {
                Auth::guard('conductores')->logout();
                Session::invalidate();
                Session::regenerateToken();
            }

            return [
                'success' => true,
                'message' => 'Conductor eliminado con éxito'
            ];
        } catch (Exception $e) {
            return ($e->getCode() == '23000') ?
                ['success' => false, 'message' => 'El conductor está siendo usado por otros registros, no puede eliminar'] :
                ['success' => false, 'message' => 'Error inesperado, no se pudo eliminar el conductor'];
        }
    }

    /**
     * Método para búsqueda de conductores
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchConductores(Request $request)
    {
        $term = $request->input('term');
        $conductores = Conductor::whereIsEnabled()
            ->where(function ($query) use ($term) {
                $query->where('name', 'like', "%{$term}%")
                    ->orWhere('number', 'like', "%{$term}%")
                    ->orWhere('licencia', 'like', "%{$term}%");
            })
            ->orderBy('name')
            ->take(15)
            ->get()
            ->map(function ($conductor) {
                return $conductor->getCollectionData();
            });

        return response()->json($conductores);
    }
}
