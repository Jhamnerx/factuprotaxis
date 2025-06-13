<?php

namespace App\Http\Controllers\Tenant;

use Exception;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\Tenant\Taxis\Marca;
use App\Http\Controllers\Controller;
use App\Http\Resources\Tenant\MarcaResource;
use App\Http\Resources\Tenant\MarcaCollection;

class MarcasController extends Controller
{
    public function index()
    {
        return view('tenant.taxis.marcas.index');
    }

    public function columns()
    {
        return [
            'nombre' => 'Nombre',
            'created_at' => 'Fecha de Registro',
            'make_country' => 'País de Fabricación',
        ];
    }

    public function records(Request $request)
    {
        $records = Marca::where($request->column, 'like', "%{$request->value}%")
            ->orderBy('nombre');

        return new MarcaCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function record($id)
    {
        $record = new MarcaResource(Marca::findOrFail($id));
        return $record;
    }

    public function store(Request $request)
    {
        $request->validate([
            'nombre' => 'required|max:255',
            'make_country' => 'nullable|max:50',
            'enabled' => 'boolean',
        ]);

        $id = $request->input('id');
        $request['marca_id'] = Str::lower($request->input('marca', null));

        $marca = Marca::firstOrNew(['id' => $id]);
        $marca->fill($request->all());
        $marca->save();

        return [
            'success' => true,
            'message' => ($id) ? 'Marca actualizada correctamente' : 'Marca registrada correctamente',
            'id' => $marca->id
        ];
    }

    public function destroy($id)
    {
        try {
            $marca = Marca::findOrFail($id);
            $marca->delete();

            return [
                'success' => true,
                'message' => 'Marca eliminada correctamente'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
