<?php

namespace App\Http\Controllers\Tenant;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TaxiController extends Controller
{
    public function propietarios()
    {
        return view('tenant.taxis.propietarios.index');
    }

    public function unidades()
    {
        return view('tenant.taxis.unidades.index');
    }

    public function unidadesBaja()
    {
        return view('tenant.taxis.unidades.baja');
    }

    public function marcas()
    {
        return view('tenant.taxis.marcas.index');
    }

    public function modelos()
    {
        return view('tenant.taxis.modelos.index');
    }
    public function planes()
    {
        return view('tenant.taxis.planes.index');
    }

    public function condiciones()
    {
        return view('tenant.taxis.condiciones.index');
    }

    public function pagos()
    {
        return view('tenant.taxis.pagos.index');
    }

    public function solicitudes()
    {
        return view('tenant.taxis.solicitudes.index');
    }

    public function permisos()
    {
        return view('tenant.taxis.permisos.index');
    }

    public function constancias()
    {
        return view('tenant.taxis.constancias.index');
    }

    public function declaraciones()
    {
        return view('tenant.taxis.declaraciones.index');
    }
}
