<?php

namespace App\Http\Controllers\Tenant\Guest;

use Illuminate\Http\Request;
use App\Models\Tenant\Company;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Models\Tenant\Taxis\WebPageTaxis;
use App\Http\Resources\Tenant\WebPageTaxisResource;

class WebController extends Controller
{
    public function home()
    {
        $web = WebPageTaxis::first();
        $web_page = new WebPageTaxisResource($web);
        return view('tenant.web.index', compact('web_page'));
    }
    public function nosotros()
    {
        $web = WebPageTaxis::first();
        $web_page = new WebPageTaxisResource($web);
        $company = Company::active();
        return view('tenant.web.nosotros', compact('web_page', 'company'));
    }
    public function contacto()
    {
        $web = WebPageTaxis::first();
        $web_page = new WebPageTaxisResource($web);
        return view('tenant.web.contacto', compact('web_page'));
    }
    public function servicios()
    {
        $web = WebPageTaxis::first();
        $web_page = new WebPageTaxisResource($web);

        return view('tenant.web.servicios', compact('web_page'));
    }

    public function config()
    {
        return view('tenant.taxis.web.form');
    }

    public function record()
    {
        $web_page = WebPageTaxis::first();
        $record = new WebPageTaxisResource($web_page);

        return $record;
    }

    public function store(Request $request)
    {

        $id = $request->input('id');
        $web_page = WebPageTaxis::find($id);
        $web_page->fill($request->all());
        $web_page->save();

        return [
            'success' => true,
            'message' => 'Página web actualizada'
        ];
    }
    public function uploads(Request $request)
    {
        if ($request->hasFile('file')) {
            $type = $request->input('type');
            $file = $request->file('file');
            $ext = $file->getClientOriginalExtension();

            // Determinar la carpeta de destino según el tipo
            $folder = '';

            if (strpos($type, 'service_image_') === 0) {
                $folder = 'services';
            } elseif ($type === 'about_image') {
                $folder = 'about';
            } elseif ($type === 'contact_image') {
                $folder = 'contact';
            } elseif (strpos($type, 'client_image_') === 0) {
                $folder = 'client';
            }

            // Generar un nombre único para el archivo
            $name = $type . '_' . time() . '.' . $ext;

            // Validar el archivo
            $request->validate(['file' => 'required|mimes:jpeg,png,jpg,gif,svg|max:2048']);

            // Guardar el archivo
            $file->storeAs('public/uploads/' . $folder, $name);

            return [
                'success' => true,
                'message' => 'Imagen subida correctamente',
                'name' => $name,
                'type' => $type
            ];
        }

        return [
            'success' => false,
            'message' => 'No se pudo subir la imagen'
        ];
    }
}
