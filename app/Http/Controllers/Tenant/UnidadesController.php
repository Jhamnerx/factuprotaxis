<?php

namespace App\Http\Controllers\Tenant;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Taxis\Vehiculos;
use App\Http\Requests\Tenant\VehiculoRequest;
use App\Http\Resources\Tenant\VehiculoResource;
use App\Http\Resources\Tenant\VehiculoCollection;
use App\Models\Tenant\Taxis\Marca;
use App\Models\Tenant\Taxis\Modelo;
use App\Models\Tenant\Taxis\Propietarios;

class UnidadesController extends Controller
{

    public function index()
    {
        $estado = '';
        return view('tenant.taxis.unidades.index', compact('estado'));
    }

    public function indexBajas()
    {
        $estado = 'BAJA';
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

        return compact(
            'configuration',
            'propietarios',
            'marcas',
            'modelos'
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
}
