<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Taxis\Vehiculos;
use App\Models\Tenant\Taxis\Propietarios;
use App\Models\Tenant\Taxis\ConstanciaBaja;
use App\Http\Resources\Tenant\ConstanciaBajaResource;
use App\Http\Resources\Tenant\ConstanciaBajaCollection;

class ConstanciasController extends Controller
{
    public function index()
    {
        return view('tenant.taxis.constancias.index');
    }

    public function columns()
    {
        return [
            'datosVehiculo' => ['label' => 'Placa', 'visible' => true],
            'datosVehiculo.propietario' => ['label' => 'Propietario', 'visible' => true],
            'estado' => ['label' => 'Estado', 'visible' => true],
            'created_at' => ['label' => 'Fecha Registro', 'visible' => true],
            'updated_at' => ['label' => 'Fecha Actualización', 'visible' => true],
            'user_id' => ['label' => 'Usuario', 'visible' => true],
        ];
    }

    public function records(Request $request)
    {
        $records = $this->getRecords($request);

        return new ConstanciaBajaCollection($records->paginate(config('tenant.items_per_page', 20)));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getRecords(Request $request)
    {
        $records = ConstanciaBaja::query();

        switch ($request->column) {
            case 'datosVehiculo':
                $records->whereHas('datosVehiculo', function ($q) use ($request) {
                    $q->where('placa', 'like', "%{$request->value}%")
                        ->orWhere('numero_interno', 'like', "%{$request->value}%");
                });
                break;
            case 'datosVehiculo.propietario':
                $records->whereHas('datosVehiculo', function ($q) use ($request) {
                    $q->whereHas('propietario', function ($q) use ($request) {
                        $q->where('name', 'like', "%{$request->value}%")
                            ->orWhere('number', 'like', "%{$request->value}%");
                    });
                });
                break;

            default:
                if ($request->has('column')) {
                    $records->where($request->column, 'like', "%{$request->value}%");
                }
                break;
        }

        return $records->orderBy('id', 'desc');
    }

    public function record($id)
    {
        $record = new ConstanciaBajaResource(ConstanciaBaja::findOrFail($id));
        return $record;
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $data['user_id'] = auth()->id();
        $constancia = ConstanciaBaja::updateOrCreate(['id' => $data['id'] ?? null], $data);
        return response()->json(['success' => true, 'message' => 'Constancia guardada correctamente']);
    }

    public function destroy(ConstanciaBaja $constancia)
    {
        $constancia->delete();
        return response()->json(['success' => true, 'message' => 'Constancia eliminada correctamente']);
    }
    /**
     * Busca constancias por número o fecha de emisión y filtra por vehículo ID
     * 
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function searchConstancias(Request $request)
    {
        $q = $request->input('q');
        $v = $request->input('v');

        $query = ConstanciaBaja::query();

        // Primero filtramos por vehiculo_id
        if ($v) {
            $query->whereHas('detalle', function ($q) use ($v) {
                $q->where('vehiculo_id', $v);
            });
        }

        // Luego aplicamos el filtro de búsqueda si se proporciona
        if ($q) {
            $query->where(function ($subquery) use ($q) {
                $subquery->where('numero', 'like', "%{$q}%")
                    ->orWhere('fecha_emision', 'like', "%{$q}%");
            });
        }

        $constancias = $query->orderBy('id', 'desc')
            ->take(15)
            ->get()
            ->map(function ($constancia) {
                return $constancia->getCollectionData();
            });

        return response()->json([
            'data' => $constancias,
            'debug' => [
                'query' => $q,
                'vehiculo_id' => $v,
                'count' => count($constancias)
            ]
        ]);
    }

    public function tables()
    {
        $propietarios = $this->table('propietarios');
        $vehiculos = Vehiculos::where('estado', 'activo')
            ->orderBy('numero_interno')
            ->take(20)
            ->get()->transform(function ($row) {
                /** @var Vehiculo $row */
                return $row->getCollectionData();
            });

        $configuration = Configuration::first();

        // Aquí puedes retornar catálogos si es necesario
        return compact('vehiculos', 'propietarios', 'configuration');
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
