<?php

namespace App\Http\Controllers\Tenant;

use Exception;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Taxis\Vehiculos;
use App\Models\Tenant\Taxis\Contratos;
use App\Models\Tenant\Taxis\Propietarios;
use App\Http\Requests\Tenant\ContratoRequest;
use App\Http\Resources\Tenant\ContratoResource;
use App\Http\Resources\Tenant\ContratoCollection;

class ContratosController extends Controller
{
    public function index()
    {
        return view('tenant.taxis.contratos.index');
    }

    public function columns()
    {
        return [
            'vehiculo' => 'Vehículo',
            'propietario' => 'Propietario',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'monto_tributo' => 'Monto Tributo',
            'estado' => 'Estado'
        ];
    }

    public function records(Request $request)
    {
        $records = $this->getRecords($request);
        return new ContratoCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function tables()
    {
        $propietarios = $this->table('propietarios');
        $vehiculos = Vehiculos::with('propietario', 'marca', 'modelo')
            ->where('estado', 'activo')
            ->orderBy('numero_interno')
            ->take(100) // Aumentamos el límite a 100 vehículos
            ->get()->transform(function ($row) {
                /** @var Vehiculos $row */
                return $row->getCollectionData();
            });

        return compact('vehiculos', 'propietarios');
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
        $record = new ContratoResource(Contratos::findOrFail($id));
        return $record;
    }

    public function getRecords(Request $request)
    {
        $records = Contratos::with(['vehiculo', 'propietario']);

        if ($request->column) {
            switch ($request->column) {
                case 'vehiculo':
                    $records->whereHas('vehiculo', function ($query) use ($request) {
                        $query->where('placa', 'like', "%{$request->value}%");
                    });
                    break;
                case 'propietario':
                    $records->whereHas('propietario', function ($query) use ($request) {
                        $query->where('nombre_completo', 'like', "%{$request->value}%");
                    });
                    break;
                case 'estado':
                    $records->where('estado', 'like', "%{$request->value}%");
                    break;
                case 'fecha_inicio':
                    $records->where('fecha_inicio', 'like', "%{$request->value}%");
                    break;
            }
        }

        return $records->orderBy('id', 'desc');
    }

    public function store(Request $request)
    {
        // Si no se proporciona propietario_id, intentar obtenerlo del vehículo
        if (!$request->propietario_id && $request->vehiculo_id) {
            $vehiculo = Vehiculos::find($request->vehiculo_id);
            if ($vehiculo && $vehiculo->propietario_id) {
                $request->merge(['propietario_id' => $vehiculo->propietario_id]);
            }
        }

        $request->validate([
            'vehiculo_id' => 'required|exists:tenant.vehiculos,id',
            'propietario_id' => 'required|exists:tenant.propietarios,id',
            'fecha_inicio' => 'required|date',
            'fecha_fin' => 'nullable|date|after:fecha_inicio',
            'monto_tributo' => 'required|numeric|min:0',
            'estado' => 'required|in:activo,finalizado,cancelado',
            'observaciones' => 'nullable|string'
        ]);

        $id = $request->input('id');
        $contrato = Contratos::firstOrNew(['id' => $id]);

        $data = $request->all();
        unset($data['id']);
        $data['user_id'] = auth()->user()->id;

        // Obtener datos del vehículo y propietario para almacenar snapshot
        if (!$id || $request->vehiculo_id != $contrato->vehiculo_id) {
            $vehiculo = Vehiculos::with(['marca', 'modelo', 'propietario'])->find($request->vehiculo_id);
            $data['vehiculo'] = $vehiculo ? $vehiculo->getCollectionData() : null;

            // Si no se especifica propietario_id pero el vehículo tiene uno, usarlo
            if (!$request->propietario_id && $vehiculo && $vehiculo->propietario_id) {
                $data['propietario_id'] = $vehiculo->propietario_id;
                $request->merge(['propietario_id' => $vehiculo->propietario_id]);
            }
        }

        if (!$id || $request->propietario_id != $contrato->propietario_id) {
            $propietario = Propietarios::find($request->propietario_id);
            $data['propietario'] = $propietario ? $propietario->getCollectionData() : null;
        }

        $contrato->fill($data);
        $contrato->save();

        $msg = $id ? 'Contrato editado con éxito' : 'Contrato registrado con éxito';

        return [
            'success' => true,
            'message' => $msg,
            'id' => $contrato->id
        ];
    }

    public function destroy($id)
    {
        try {
            $contrato = Contratos::findOrFail($id);
            $contrato->delete();

            return [
                'success' => true,
                'message' => 'Contrato eliminado con éxito'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    /**
     * Cambiar estado del contrato
     */
    public function changeStatus(Request $request, $id)
    {
        try {
            $request->validate([
                'status' => 'required|in:activo,cancelado'
            ]);

            $contrato = Contratos::findOrFail($id);

            // No permitir cambios si el contrato está finalizado
            if ($contrato->estado === 'finalizado') {
                return response()->json([
                    'success' => false,
                    'message' => 'No se puede cambiar el estado de un contrato finalizado'
                ], 400);
            }

            $estadoAnterior = $contrato->estado;
            $contrato->estado = $request->status;
            $contrato->save();

            $mensaje = $request->status === 'activo'
                ? 'Contrato activado correctamente'
                : 'Contrato cancelado correctamente';

            return response()->json([
                'success' => true,
                'message' => $mensaje,
                'previous_status' => $estadoAnterior,
                'new_status' => $request->status
            ]);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Error al cambiar el estado: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Crear contrato desde un vehículo específico
     */
    public function createFromVehicle(Request $request)
    {
        $vehiculo = Vehiculos::with(['propietario', 'marca', 'modelo'])->find($request->vehiculo_id);

        if (!$vehiculo) {
            return response()->json([
                'success' => false,
                'message' => 'Vehículo no encontrado'
            ], 404);
        }

        $propietarioData = null;
        if ($vehiculo->propietario) {
            $propietarioData = [
                'id' => $vehiculo->propietario->id,
                'name' => $vehiculo->propietario->name,
                'number' => $vehiculo->propietario->number
            ];
        }

        return response()->json([
            'success' => true,
            'vehiculo' => [
                'id' => $vehiculo->id,
                'placa' => $vehiculo->placa,
                'numero_interno' => $vehiculo->numero_interno,
                'propietario_id' => $vehiculo->propietario_id,
                'propietario' => $propietarioData,
                'marca' => $vehiculo->marca ? $vehiculo->marca->nombre : null,
                'modelo' => $vehiculo->modelo ? $vehiculo->modelo->nombre : null
            ]
        ]);
    }

    /**
     * Buscar vehículos por placa o número interno
     */
    public function searchVehiculos(Request $request)
    {

        $vehiculos = Vehiculos::where('placa', 'like', "%{$request->input}%")
            ->orWhere('numero_interno', 'like', "%{$request->input}%")
            ->whereIsEnabled()
            ->get()->transform(function ($row) {
                /** @var  Vehiculos $row */
                return $row->getCollectionData();
            });

        return compact('vehiculos');
    }
}
