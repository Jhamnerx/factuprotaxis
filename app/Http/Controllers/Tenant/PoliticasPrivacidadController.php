<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\PoliticaPrivacidad;

class PoliticasPrivacidadController extends Controller
{
    public function index()
    {
        return view('tenant.taxis.politicas_privacidad.index');
    }

    public function records()
    {
        $records = PoliticaPrivacidad::all();
        return $records;
    }

    public function record($id)
    {
        $record = PoliticaPrivacidad::findOrFail($id);
        return $record;
    }

    public function store(Request $request)
    {
        $id = $request->input('id');
        $politica = PoliticaPrivacidad::firstOrNew(['id' => $id]);
        $politica->fill($request->all());
        $politica->save();

        return [
            'success' => true,
            'message' => $id ? 'Política actualizada con éxito' : 'Política registrada con éxito',
            'id' => $politica->id
        ];
    }

    public function destroy($id)
    {
        $politica = PoliticaPrivacidad::findOrFail($id);
        $politica->delete();

        return [
            'success' => true,
            'message' => 'Política eliminada con éxito'
        ];
    }
}
