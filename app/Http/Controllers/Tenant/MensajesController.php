<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Models\Tenant\PlantillaMensaje;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class MensajesController extends Controller
{
    public function index()
    {
        return view('tenant.taxis.mensajes.plantillas');
    }

    public function records()
    {
        $records = PlantillaMensaje::all();
        return $records;
    }

    public function record($id)
    {
        // Si $id es un número, buscamos por ID
        if (is_numeric($id)) {
            $record = PlantillaMensaje::findOrFail($id);
        } else {
            // Si no es numérico, asumimos que es el tipo de plantilla
            $record = PlantillaMensaje::where('tipo', $id)->first();

            // Si no encontramos la plantilla, retornamos un objeto vacío
            if (!$record) {
                return [
                    'id' => null,
                    'tipo' => $id,
                    'asunto' => '',
                    'contenido' => '',
                    'descripcion' => '',
                    'estado' => true
                ];
            }
        }

        return $record;
    }

    public function store(Request $request)
    {
        try {
            DB::connection('tenant')->beginTransaction();

            $id = $request->input('id');
            $plantilla = PlantillaMensaje::firstOrNew(['id' => $id]);
            $plantilla->fill($request->all());
            $plantilla->save();

            DB::connection('tenant')->commit();

            return [
                'success' => true,
                'message' => ($id) ? 'Plantilla actualizada con éxito' : 'Plantilla registrada con éxito',
                'id' => $plantilla->id
            ];
        } catch (\Exception $e) {
            DB::connection('tenant')->rollBack();
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function destroy($id)
    {
        try {
            $plantilla = PlantillaMensaje::findOrFail($id);
            $plantilla->delete();

            return [
                'success' => true,
                'message' => 'Plantilla eliminada con éxito'
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function getByTipo($tipo)
    {
        $record = PlantillaMensaje::where('tipo', $tipo)->first();

        if (!$record) {
            return [
                'success' => false,
                'message' => 'Plantilla no encontrada',
                'data' => [
                    'id' => null,
                    'tipo' => $tipo,
                    'asunto' => '',
                    'contenido' => '',
                    'descripcion' => '',
                    'estado' => true
                ]
            ];
        }

        return [
            'success' => true,
            'data' => $record
        ];
    }

    /**
     * Renderiza una plantilla reemplazando las variables con datos reales
     * 
     * @param Request $request
     * @return array
     */
    public function renderTemplate(Request $request)
    {
        try {
            $tipo = $request->input('tipo');
            $data = $request->input('data', []);

            $plantilla = PlantillaMensaje::where('tipo', $tipo)->first();

            if (!$plantilla) {
                return [
                    'success' => false,
                    'message' => 'Plantilla no encontrada'
                ];
            }

            // Renderizar el contenido reemplazando las variables
            $contenido = $plantilla->contenido;
            $asunto = $plantilla->asunto;

            foreach ($data as $key => $value) {
                $contenido = str_replace("[$key]", $value, $contenido);
                $asunto = str_replace("[$key]", $value, $asunto);
            }

            return [
                'success' => true,
                'data' => [
                    'asunto' => $asunto,
                    'contenido' => $contenido
                ]
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }
}
