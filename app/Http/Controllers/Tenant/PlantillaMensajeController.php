<?php

namespace App\Http\Controllers\Tenant;

use Exception;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Tenant\PlantillaMensaje;
use App\Http\Resources\Tenant\PlantillaMensajeResource;
use App\Http\Resources\Tenant\PlantillaMensajeCollection;

class PlantillaMensajeController extends Controller
{
    public function index()
    {
        return view('tenant.taxis.plantillas_mensajes.index');
    }

    public function columns()
    {
        return [
            'tipo' => 'Tipo',
            'asunto' => 'Asunto',
            'descripcion' => 'Descripción',
            'estado' => 'Estado'
        ];
    }

    public function records(Request $request)
    {
        $records = PlantillaMensaje::where($request->column, 'like', "%{$request->value}%")
            ->orderBy('tipo');

        return new PlantillaMensajeCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function tables()
    {
        $tipos = PlantillaMensaje::tiposDisponibles();

        return compact('tipos');
    }

    public function record($id)
    {
        $record = new PlantillaMensajeResource(PlantillaMensaje::findOrFail($id));

        return $record;
    }

    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string|max:50',
            'asunto' => 'nullable|string|max:255',
            'contenido' => 'required|string',
            'descripcion' => 'nullable|string',
            'estado' => 'boolean'
        ]);

        try {
            $id = $request->input('id');
            $plantilla = PlantillaMensaje::firstOrNew(['id' => $id]);
            $data = $request->all();
            unset($data['id']);
            $plantilla->fill($data);
            $plantilla->save();

            $msg = ($id) ? 'Plantilla editada con éxito' : 'Plantilla registrada con éxito';

            return [
                'success' => true,
                'message' => $msg,
                'id' => $plantilla->id
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error inesperado, no se pudo guardar la plantilla: ' . $e->getMessage()
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
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error inesperado, no se pudo eliminar la plantilla'
            ];
        }
    }

    /**
     * Previsualizar plantilla con variables de ejemplo
     */
    public function preview(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string',
            'contenido' => 'required|string'
        ]);

        try {
            $tipo = $request->tipo;
            $contenido = $request->contenido;

            // Variables de ejemplo según el tipo
            $variablesEjemplo = $this->getExampleVariables($tipo);

            // Procesar contenido
            $preview = $contenido;
            foreach ($variablesEjemplo as $variable => $valor) {
                $preview = str_replace("[{$variable}]", $valor, $preview);
            }

            return [
                'success' => true,
                'preview' => $preview,
                'variables' => $variablesEjemplo
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => 'Error al generar la previsualización: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Obtener variables de ejemplo según el tipo de plantilla
     */
    private function getExampleVariables($tipo)
    {
        $baseVariables = [
            'nombre' => 'Juan Pérez',
            'fecha' => date('d/m/Y'),
            'hora' => date('H:i')
        ];

        switch ($tipo) {
            case 'bienvenida':
                return array_merge($baseVariables, [
                    'celular' => '987654321',
                    'flota' => 'T-001',
                    'placa' => 'ABC-123'
                ]);

            case 'cumpleanos_propietario':
            case 'cumpleanos_conductor':
            case 'cumpleanos_personal':
                return array_merge($baseVariables, [
                    'edad' => '45'
                ]);

            case 'vencimiento_licencia_conductor':
                return array_merge($baseVariables, [
                    'fecha_vencimiento' => date('d/m/Y', strtotime('+30 days')),
                    'dias_restantes' => '30',
                    'licencia' => 'A-IIa'
                ]);

            case 'vencimiento_soat':
            case 'vencimiento_revision_tecnica':
                return array_merge($baseVariables, [
                    'placa' => 'ABC-123',
                    'fecha_vencimiento' => date('d/m/Y', strtotime('+30 days')),
                    'dias_restantes' => '30',
                    'numero_interno' => 'T-001'
                ]);

            default:
                return $baseVariables;
        }
    }

    /**
     * Obtener variables disponibles por tipo de plantilla
     */
    public function getAvailableVariables(Request $request)
    {
        $tipo = $request->get('tipo');
        $variables = $this->getExampleVariables($tipo);

        return [
            'success' => true,
            'variables' => array_keys($variables),
            'descriptions' => $this->getVariableDescriptions()
        ];
    }

    /**
     * Descripciones de las variables disponibles
     */
    private function getVariableDescriptions()
    {
        return [
            'nombre' => 'Nombre de la persona',
            'celular' => 'Número de teléfono',
            'fecha' => 'Fecha actual',
            'hora' => 'Hora actual',
            'flota' => 'Número interno del vehículo',
            'placa' => 'Placa del vehículo',
            'edad' => 'Edad de la persona',
            'fecha_vencimiento' => 'Fecha de vencimiento',
            'dias_restantes' => 'Días restantes hasta vencimiento',
            'licencia' => 'Número/clase de licencia',
            'numero_interno' => 'Número interno del vehículo'
        ];
    }
}
