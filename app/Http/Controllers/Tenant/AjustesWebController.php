<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Tenant\AjusteWeb;

class AjustesWebController extends Controller
{
    public function index()
    {
        return view('tenant.taxis.ajustes_web.index');
    }

    public function records()
    {
        $records = AjusteWeb::all();
        return $records;
    }

    public function record($id)
    {
        $record = AjusteWeb::findOrFail($id);
        return $record;
    }

    public function store(Request $request)
    {
        $id = $request->input('id');
        $ajuste = AjusteWeb::firstOrNew(['id' => $id]);
        $ajuste->fill($request->all());
        $ajuste->save();

        return [
            'success' => true,
            'message' => $id ? 'Ajuste actualizado con éxito' : 'Ajuste registrado con éxito',
            'id' => $ajuste->id
        ];
    }

    public function destroy($id)
    {
        $ajuste = AjusteWeb::findOrFail($id);
        $ajuste->delete();

        return [
            'success' => true,
            'message' => 'Ajuste eliminado con éxito'
        ];
    }
}
