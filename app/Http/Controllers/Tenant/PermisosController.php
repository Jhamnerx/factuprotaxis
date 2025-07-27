<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Taxis\Vehiculos;
use App\Models\Tenant\Taxis\Propietarios;
use App\Models\Tenant\Taxis\PermisoUnidad;
use App\Http\Resources\Tenant\PermisoUnidadResource;
use App\Models\Tenant\Catalogs\IdentityDocumentType;
use App\Http\Resources\Tenant\PermisoUnidadCollection;

class PermisosController extends Controller
{
    public function index()
    {
        return view('tenant.taxis.permisos.index');
    }

    public function columns()
    {
        return [
            'datosVehiculo' => 'Placa',
            'datosVehiculo.propietario' => 'Propietario',
            'tipo_permiso' => 'Tipo de Permiso',
            'fecha_inicio' => 'Fecha Inicio',
            'fecha_fin' => 'Fecha Fin',
            'estado' => 'Estado',
            'created_at' => 'Fecha de Registro'
        ];
    }

    public function records(Request $request)
    {
        $records = $this->getRecords($request);

        return new PermisoUnidadCollection($records->paginate(config('tenant.items_per_page', 20)));
    }

    /**
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function getRecords(Request $request)
    {
        $records = PermisoUnidad::query();

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

    public function tables()
    {
        $propietarios = $this->table('propietarios');
        $vehiculos = Vehiculos::where('estado', 'activo')
            ->orderBy('numero_interno')
            ->take(20)
            ->get()->transform(function ($row) {
                /** @var Vehiculos $row */
                return $row->getCollectionData();
            });

        $configuration = Configuration::first();
        $identity_document_types = IdentityDocumentType::whereActive()->get();
        $api_service_token = \App\Models\Tenant\Configuration::getApiServiceToken();
        // Aquí puedes retornar catálogos si es necesario
        return compact('vehiculos', 'propietarios', 'configuration', 'identity_document_types', 'api_service_token');
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
        $record = new PermisoUnidadResource(PermisoUnidad::with(['datosVehiculo.propietario'])->findOrFail($id));
        return $record;
    }

    public function store(Request $request)
    {
        $id = $request->input('id');
        $permiso = PermisoUnidad::firstOrNew(['id' => $id]);
        $data = $request->all();
        unset($data['id']);
        $data['user_id'] = auth()->user()->id;
        $permiso->fill($data);
        $permiso->save();
        $msg = ($id) ? 'Permiso editado con éxito' : 'Permiso registrado con éxito';
        return [
            'success' => true,
            'message' => $msg,
            'data' => new PermisoUnidadResource($permiso->fresh(['datosVehiculo.propietario']))
        ];
    }

    public function update(Request $request, $id)
    {
        $permiso = PermisoUnidad::findOrFail($id);
        $permiso->update($request->all());
        return [
            'success' => true,
            'message' => 'Permiso actualizado con éxito',
            'data' => new PermisoUnidadResource($permiso->fresh(['datosVehiculo.propietario']))
        ];
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

    public function destroy($id)
    {
        $permiso = PermisoUnidad::findOrFail($id);
        $permiso->delete();
        return response()->json(['success' => true]);
    }

    public function downloadPdf($id)
    {
        $permiso = PermisoUnidad::with(['datosVehiculo.propietario'])->findOrFail($id);
        $pdf = PDF::loadView('tenant.taxis.permisos.pdf', compact('permiso'));
        return $pdf->download('permiso_unidad_' . $permiso->id . '.pdf');
    }

    public function cambiarEstado(Request $request, $id)
    {
        $permiso = PermisoUnidad::findOrFail($id);
        $nuevoEstado = $request->input('estado');
        $estadosValidos = ['pendiente', 'aceptado', 'anulado'];
        if (!in_array($nuevoEstado, $estadosValidos)) {
            return response()->json([
                'success' => false,
                'message' => 'Estado no válido.'
            ], 422);
        }
        $permiso->estado = $nuevoEstado;
        $permiso->save();
        return response()->json([
            'success' => true,
            'message' => 'Estado actualizado correctamente.',
            'estado' => $permiso->estado
        ]);
    }
}
