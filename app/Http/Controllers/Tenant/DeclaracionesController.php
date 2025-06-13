<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Taxis\Vehiculos;
use App\Models\Tenant\Taxis\Declaracion;
use App\Models\Tenant\Taxis\Propietarios;
use App\Http\Resources\Tenant\DeclaracionResource;
use App\Http\Resources\Tenant\DeclaracionCollection;

class DeclaracionesController extends Controller
{
    public function index()
    {
        return view('tenant.taxis.declaraciones.index');
    }

    public function columns()
    {
        return [
            'vehiculo_id' => ['label' => 'Vehículo', 'visible' => true],
            'propietario' => ['label' => 'Propietario', 'visible' => true],
            'created_at' => ['label' => 'Fecha Registro', 'visible' => true],
            'updated_at' => ['label' => 'Fecha Actualización', 'visible' => true],
            'user_id' => ['label' => 'Usuario', 'visible' => true],
        ];
    }

    public function records(Request $request)
    {
        $records = $this->getRecords($request);
        return new DeclaracionCollection($records->paginate(20));
    }

    public function getRecords(Request $request)
    {
        $records = Declaracion::query();

        if ($request->has('user_id')) {
            $records->whereHas('user', function ($query) use ($request) {
                $query->where('name', $request->name);
            });
        }
        if ($request->has('vehiculo_id')) {
            $records->whereHas('vehiculo', function ($query) use ($request) {
                $query->where('placa', $request->placa);
            });
        }

        if ($request->has('propietario')) {
            $records->whereHas('vehiculo', function ($query) use ($request) {
                $query->whereHas('propietario', function ($query) use ($request) {
                    $query->where('name', $request->propietario);
                });
            });
        }

        if ($request->has('column')) {
            $records->where($request->column, 'like', "%{$request->value}%");
        }
        return $records->orderBy('id', 'desc');
    }

    public function record($id)
    {
        $record = new DeclaracionResource(Declaracion::findOrFail($id));
        return $record;
    }

    public function store(Request $request)
    {
        $id = $request->input('id');
        $declaracion = Declaracion::firstOrNew(['id' => $id]);
        $data = $request->all();
        unset($data['id']);
        $data['user_id'] = auth()->user()->id;
        $declaracion->fill($data);
        $declaracion->save();
        $msg = ($id) ? 'Declaración editada con éxito' : 'Declaración registrada con éxito';
        return [
            'success' => true,
            'message' => $msg
        ];
    }

    public function destroy($id)
    {
        $declaracion = Declaracion::findOrFail($id);
        $declaracion->delete();
        return response()->json(['success' => true]);
    }

    public function tables()
    {
        $vehiculos = Vehiculos::where('estado', 'activo')
            ->orderBy('numero_interno')
            ->take(20)
            ->get()->transform(function ($row) {
                return $row->getCollectionData();
            });
        $propietarios = Propietarios::all();
        return compact('vehiculos', 'propietarios');
    }

    public function searchVehiculos(Request $request)
    {
        $input = $request->input('input');
        $vehiculos = Vehiculos::where('placa', 'like', "%$input%")
            ->orWhere('numero_serie', 'like', "%$input%")
            ->get();
        return response()->json(['vehiculos' => $vehiculos]);
    }
}
