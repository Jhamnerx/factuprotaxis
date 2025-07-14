<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\TerminoCondicion;

class TerminosCondicionesController extends Controller
{
    public function index()
    {
        return view('tenant.taxis.terminos_condiciones.index');
    }

    public function records()
    {
        $records = TerminoCondicion::all();
        return $records;
    }

    public function record($id)
    {
        $record = TerminoCondicion::findOrFail($id);
        return $record;
    }

    public function store(Request $request)
    {
        $id = $request->input('id');
        $termino = TerminoCondicion::firstOrNew(['id' => $id]);
        $termino->fill($request->all());
        $termino->save();

        return [
            'success' => true,
            'message' => $id ? 'Término y condición actualizado con éxito' : 'Término y condición registrado con éxito',
            'id' => $termino->id
        ];
    }

    public function destroy($id)
    {
        $termino = TerminoCondicion::findOrFail($id);
        $termino->delete();

        return [
            'success' => true,
            'message' => 'Término y condición eliminado con éxito'
        ];
    }
}
