<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Plan;
use App\Http\Resources\Tenant\PlanResource;
use App\Http\Resources\Tenant\PlanCollection;
use App\Http\Requests\Tenant\PlanRequest;

class PlanesController extends Controller
{
    public function index()
    {
        return view('tenant.taxis.planes.index');
    }

    public function columns()
    {
        return [
            'columns' => [
                'name' => 'Nombre',
                'description' => 'Descripción',
                'price' => 'Precio',
                'is_active' => 'Activo',
                'currency' => 'Moneda',
                'invoice_period' => 'Periodo',
                'invoice_interval' => 'Intervalo',
                'sort_order' => 'Orden',
                'is_socio' => 'Socio',
            ]
        ];
    }

    public function records(Request $request)
    {
        $records = Plan::query();
        if ($request->value) {
            $records->where('name', 'like', "%{$request->value}%");
        }
        return new PlanCollection($records->orderBy('sort_order')->paginate(config('tenant.items_per_page')));
    }

    public function record($id)
    {
        $record = Plan::findOrFail($id);
        return new PlanResource($record);
    }

    public function store(PlanRequest $request)
    {
        $data = $request->validated();
        // Determinar el tipo de plan según invoice_interval
        $data['type'] = ($data['invoice_interval'] ?? null) === 'indeterminate' ? 'indeterminate' : 'recurring';
        if (($data['invoice_interval'] ?? null) === 'indeterminate') {
            $data['invoice_interval'] = 'year';
        }
        $plan = Plan::updateOrCreate(['id' => $request->input('id')], $data);
        return [
            'success' => true,
            'message' => ($request->input('id') ? 'Plan actualizado' : 'Plan registrado'),
            'id' => $plan->id
        ];
    }

    public function destroy($id)
    {
        $plan = Plan::findOrFail($id);
        $plan->delete();
        return [
            'success' => true,
            'message' => 'Plan eliminado'
        ];
    }
}
