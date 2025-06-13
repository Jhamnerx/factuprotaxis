<?php

namespace App\Http\Controllers\Tenant;

use Exception;
use App\Models\Tenant\Taxis\Modelo;
use App\Models\Tenant\Taxis\Marca;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\ModeloCollection;
use App\Http\Resources\Tenant\ModeloResource;

class ModelosController extends Controller
{
    public function index()
    {
        return view('tenant.taxis.modelos.index');
    }

    public function columns()
    {
        return [
            'nombre' => 'Nombre',
            'marca' => 'Marca',
            'enabled' => 'Estado',
            'created_at' => 'Fecha de Registro'
        ];
    }

    public function tables()
    {
        $marcas = Marca::where('enabled', true)
            ->orderBy('nombre')
            ->get();

        return compact('marcas');
    }

    public function records(Request $request)
    {
        $records = $this->getRecords($request);
        return new ModeloCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function record($id)
    {
        $record = new ModeloResource(Modelo::findOrFail($id));
        return $record;
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'marca_id' => 'required|exists:tenant.marcas,id',
            'enabled' => 'boolean',
        ]);

        $id = $request->input('id');
        $modelo = Modelo::firstOrNew(['id' => $id]);
        $modelo->fill($request->all());
        $modelo->save();

        return [
            'success' => true,
            'message' => ($id) ? 'Modelo actualizado correctamente' : 'Modelo registrado correctamente',
            'id' => $modelo->id
        ];
    }

    public function destroy($id)
    {
        try {
            $modelo = Modelo::findOrFail($id);
            $modelo->delete();

            return [
                'success' => true,
                'message' => 'Modelo eliminado correctamente'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getRecords(Request $request)
    {
        $records = Modelo::query();

        switch ($request->column) {
            case 'marca':
                $records->whereHas('marca', function ($q) use ($request) {
                    $q->where('nombre', 'like', "%{$request->value}%");
                });
                break;
            default:
                if ($request->has('column')) {

                    $records->where($request->column, 'like', "%{$request->value}%");
                }
                break;
        }


        return $records->orderBy('nombre');
    }

    /**
     * Obtiene los modelos asociados a una marca específica
     *
     * @param  int  $marcaId
     * @return \Illuminate\Http\JsonResponse
     */
    public function getModelosByMarca($marcaId)
    {
        $records = Modelo::with('marca')
            ->where('marca_id', $marcaId)
            ->where('enabled', true)
            ->orderBy('nombre')
            ->get()
            ->map(function ($modelo) {
                return [
                    'id' => $modelo->id,
                    'nombre' => $modelo->nombre,
                    'marca_id' => $modelo->marca_id,
                    'marca' => optional($modelo->marca)->nombre,
                    'model_make_id' => $modelo->model_make_id
                ];
            });

        return response()->json($records);
    }
}
