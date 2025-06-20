<?php

namespace App\Http\Controllers\Tenant;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Taxis\Condicion;
use App\Http\Resources\Tenant\CondicionResource;
use App\Http\Resources\Tenant\CondicionCollection;

class CondicionesController extends Controller
{
    public function index()
    {
        return view('tenant.taxis.condiciones.index');
    }

    public function columns()
    {
        return [
            'columns' => [
                'color' => 'Color',
                'descripcion' => 'Descripción',
                'created_at' => 'Fecha Registro',
                'updated_at' => 'Fecha Actualización',
            ]
        ];
    }

    public function records(Request $request)
    {
        $records = Condicion::query();
        if ($request->value) {
            $records->where('nombre', 'like', "%{$request->value}%");
        }
        return new CondicionCollection($records->orderBy('id', 'desc')->paginate(config('tenant.items_per_page')));
    }

    public function record($id)
    {
        $record = Condicion::findOrFail($id);
        return new CondicionResource($record);
    }

    public function store(Request $request)
    {

        $data = $request->validate([
            'color' => 'required|string|max:20',
            'descripcion' => 'nullable|string|max:255',
        ]);

        $condicion = Condicion::updateOrCreate(['id' => $request->input('id')], $data);

        return [
            'success' => true,
            'message' => ($request->input('id') ? 'Condición actualizada' : 'Condición registrada'),
            'id' => $condicion->id
        ];
    }

    public function destroy($id)
    {
        try {
            $condicion = Condicion::findOrFail($id);
            $condicion->delete();
            return [
                'success' => true,
                'message' => 'Condición eliminada'
            ];
        } catch (Exception $e) {

            return ($e->getCode() == '23000') ? ['success' => false, 'message' => 'La condición está siendo usada por otros registros, no puede eliminar'] : ['success' => false, 'message' => 'Error inesperado, no se pudo eliminar la condición'];
        }
    }
}
