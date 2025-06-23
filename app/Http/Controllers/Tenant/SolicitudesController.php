<?php

namespace App\Http\Controllers\Tenant;

use Exception;
use Carbon\Carbon;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\PDF;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Configuration;
use App\Models\Tenant\Taxis\Vehiculo;
use App\Models\Tenant\Taxis\Solicitud;
use App\Models\Tenant\Taxis\Vehiculos;
use Illuminate\Support\Facades\Storage;
use App\Models\Tenant\Taxis\ConstanciaBaja;
use App\Models\Tenant\Taxis\SolicitudDetalle;
use Modules\Finance\Helpers\UploadFileHelper;
use App\Http\Resources\Tenant\SolicitudResource;
use App\Http\Resources\Tenant\SolicitudCollection;
use App\Http\Requests\Tenant\SolicitudUpdateRequest;

class SolicitudesController extends Controller
{
    public function index()
    {
        return view('tenant.taxis.solicitudes.index');
    }

    public function create()
    {
        return view('tenant.taxis.solicitudes.form');
    }

    public function edit($id)
    {
        return view('tenant.taxis.solicitudes.form', ['id' => $id]);
    }


    public function columns()
    {
        return [
            'tipo' => 'Tipo',
            'estado' => 'Estado',
            'fecha' => 'Fecha',
            'vehiculo' => 'Vehículo',
            'propietario' => 'Propietario',
        ];
    }

    public function records(Request $request)
    {
        $records = Solicitud::query();

        switch ($request->column) {
            case 'tipo':
                $records->where('tipo', 'like', '%' . $request->value . '%');
                break;
            case 'estado':
                $records->where('estado', 'like', '%' . $request->value . '%');
                break;
            case 'vehiculo':
                $records->whereHas('detalle', function ($query) use ($request) {
                    $query->whereHas('vehiculo', function ($q) use ($request) {
                        $q->where('numero_interno', 'like', '%' . $request->value . '%')
                            ->orWhere('placa', 'like', '%' . $request->value . '%');
                    });
                });
                break;
            case 'fecha':
                $records->where('fecha', 'like', '%' . $request->value . '%');
                break;
            default:
                $records->where($request->column, 'like', '%' . $request->value . '%');
        }

        $records = $records->orderBy('id', 'desc');

        return new SolicitudCollection($records->paginate(config('tenant.items_per_page')));
    }

    public function tables()
    {
        $vehiculos = Vehiculos::where('estado', 'activo')
            ->orderBy('numero_interno')
            ->take(20)
            ->get()->transform(function ($row) {
                /** @var Vehiculo $row */
                return $row->getCollectionData();
            });

        $constancias_baja = ConstanciaBaja::query()
            ->whereHas('datosVehiculo', function ($q) {
                $q->where('estado', 'activo');
            })
            ->orderBy('id', 'desc')
            ->take(20)
            ->get()->transform(function ($row) {
                /** @var ConstanciaBaja $row */
                return $row->getCollectionData();
            });

        $configuration = Configuration::first();

        return compact('vehiculos', 'configuration', 'constancias_baja');
    }

    public function record($id)
    {
        $record = Solicitud::findOrFail($id);
        return new SolicitudResource($record);
    }

    public function store(SolicitudUpdateRequest $request)
    {
        try {
            DB::connection('tenant')->beginTransaction();

            $solicitud = new Solicitud();
            $solicitud->fill($request->all());
            $solicitud->user_id = auth()->id();
            $solicitud->estado = 'pendiente';
            $solicitud->fecha = Carbon::now();
            $documentos_adjuntos = $request->documentos_adjuntos;

            if (is_string($documentos_adjuntos)) {
                $documentos_adjuntos = json_decode($documentos_adjuntos, true);
            }

            if (isset($documentos_adjuntos) && is_array($documentos_adjuntos)) {
                $documentos = [];
                foreach ($documentos_adjuntos as $doc) {

                    $temp_path = $doc['temp_path'];

                    if ($temp_path) {
                        // UploadFileHelper::checkIfValidFile($doc['nombre'], $temp_path, false, 'pdf', ['pdf']);
                        $directory = 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'solicitudes' . DIRECTORY_SEPARATOR;
                        $file_name_old = $doc['nombre'];
                        $file_name_old_array = explode('.', $file_name_old);
                        $file_content = file_get_contents($temp_path);
                        $datenow = date('YmdHis');
                        $file_name = Str::slug($file_name_old_array[0]) . '-' . $datenow . '.' . $file_name_old_array[1];

                        Storage::put($directory . $file_name, $file_content);

                        $documentos[] = [
                            'nombre' => $doc['nombre'],
                            'ruta' => $directory . $file_name,
                            'tipo' => $doc['tipo'] ?? '',
                        ];

                        @unlink($doc['temp_path']);
                    }
                }

                $solicitud->documentos_adjuntos = $documentos;
            }

            $solicitud->save();

            // Guardar detalle (siempre guardar JSON de vehiculo y propietario)
            if ($request->has('detalle')) {
                $detalle = is_string($request->detalle) ? json_decode($request->detalle, true) : $request->detalle;
                if (is_array($detalle)) {
                    foreach ($detalle as $detalle) {
                        $solicitudDetalle = new SolicitudDetalle();
                        $solicitudDetalle->solicitud_id = $solicitud->id;
                        $solicitudDetalle->vehiculo_id = $detalle['id'] ?? null;
                        $solicitudDetalle->vehiculo = isset($detalle['vehiculo']) ? $detalle['vehiculo'] : null;
                        $solicitudDetalle->propietario = isset($detalle['propietario']) ? $detalle['propietario'] : null;
                        if ($solicitud->tipo === 'correccion_datos' && !empty($detalle['correcciones'])) {

                            // ACTUALIZAR CAMPOS DEL VEHICULO
                            $vehiculo = Vehiculos::find($detalle['id']);

                            if ($vehiculo) {

                                foreach ($detalle['correcciones']  as $key => $corr) {
                                    $detalle['correcciones'][$key]['valor_anterior'] = $vehiculo->{$corr['campo']};
                                    $vehiculo->{$corr['campo']} = $corr['valor_nuevo'];
                                }
                                $vehiculo->save();
                            }

                            $solicitudDetalle->correcciones = $detalle['correcciones'];
                        }
                        $solicitudDetalle->save();
                    }
                }
            }

            DB::connection('tenant')->commit();

            return [
                'success' => true,
                'message' => 'Solicitud registrada con éxito',
                'id' => $solicitud->id
            ];
        } catch (Exception $e) {
            DB::connection('tenant')->rollBack();

            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function update(SolicitudUpdateRequest $request, $id)
    {
        try {
            DB::connection('tenant')->beginTransaction();

            $solicitud = Solicitud::findOrFail($id);
            $solicitud->update($request->all());

            // Procesar documentos eliminados
            $documentosEliminados = $request->input('documentos_eliminados');
            if (is_string($documentosEliminados)) {
                $documentosEliminados = json_decode($documentosEliminados, true);
            }
            $documentosAdjuntos = $request->input('documentos_adjuntos');
            if (is_string($documentosAdjuntos)) {
                $documentosAdjuntos = json_decode($documentosAdjuntos, true);
            }
            // Eliminar archivos del storage
            if (is_array($documentosEliminados)) {
                foreach ($documentosEliminados as $nombre) {
                    if (is_array($solicitud->documentos_adjuntos)) {
                        foreach ($solicitud->documentos_adjuntos as $doc) {
                            if ($doc['nombre'] === $nombre && isset($doc['ruta'])) {
                                $ruta = ltrim(str_replace(['public/', 'public\\'], '', $doc['ruta']), '/\\');
                                Storage::delete('public/' . $ruta);
                            }
                        }
                    }
                }
            }
            // Procesar documentos nuevos y mantener los existentes
            $documentos = [];
            if (is_array($documentosAdjuntos)) {
                foreach ($documentosAdjuntos as $doc) {
                    // Si tiene temp_path es nuevo, si no, es existente
                    if (!empty($doc['temp_path'])) {
                        $ext = strtolower(pathinfo($doc['nombre'], PATHINFO_EXTENSION));
                        $datenow = date('YmdHis');
                        $file_name = pathinfo($doc['nombre'], PATHINFO_FILENAME) . '-' . $datenow . '.' . $ext;
                        $directory = 'public' . DIRECTORY_SEPARATOR . 'uploads' . DIRECTORY_SEPARATOR . 'solicitudes' . DIRECTORY_SEPARATOR;
                        $file_content = file_get_contents($doc['temp_path']);
                        Storage::put($directory . $file_name, $file_content);
                        $documentos[] = [
                            'nombre' => $doc['nombre'],
                            'ruta' => 'uploads/solicitudes/' . $file_name,
                            'tipo' => $doc['tipo'] ?? '',
                            'fecha' => $doc['fecha'] ?? Carbon::now()->format('Y-m-d H:i:s')
                        ];
                        @unlink($doc['temp_path']);
                    } else if (!empty($doc['ruta']) && !empty($doc['nombre'])) {
                        // Mantener documento existente
                        $documentos[] = $doc;
                    }
                }
            }
            $solicitud->documentos_adjuntos = $documentos;
            $solicitud->save();

            // Actualizar detalle (siempre guardar JSON de vehiculo y propietario)
            if ($request->has('detalle')) {
                $detalle = is_string($request->detalle) ? json_decode($request->detalle, true) : $request->detalle;
                if (is_array($detalle)) {
                    SolicitudDetalle::where('solicitud_id', $solicitud->id)->delete();
                    foreach ($detalle as $detalle) {
                        $solicitudDetalle = new SolicitudDetalle();
                        $solicitudDetalle->solicitud_id = $solicitud->id;
                        $solicitudDetalle->vehiculo_id = $detalle['id'] ?? null;
                        $solicitudDetalle->vehiculo = isset($detalle['vehiculo']) ? $detalle['vehiculo'] : null;
                        $solicitudDetalle->propietario = isset($detalle['propietario']) ? $detalle['propietario'] : null;

                        if ($solicitud->tipo === 'correccion_datos' && !empty($detalle['correcciones'])) {

                            // ACTUALIZAR CAMPOS DEL VEHICULO
                            $vehiculo = Vehiculos::find($detalle['id']);

                            if ($vehiculo) {

                                foreach ($detalle['correcciones']  as $key => $corr) {
                                    $detalle['correcciones'][$key]['valor_anterior'] = $vehiculo->{$corr['campo']};
                                    $vehiculo->{$corr['campo']} = $corr['valor_nuevo'];
                                }
                                $vehiculo->save();
                            }

                            $solicitudDetalle->correcciones = $detalle['correcciones'];
                        }

                        $solicitudDetalle->save();
                    }
                }
            }

            DB::connection('tenant')->commit();

            return [
                'success' => true,
                'message' => 'Solicitud actualizada con éxito'
            ];
        } catch (Exception $e) {
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
            $solicitud = Solicitud::findOrFail($id);

            // Eliminar archivos asociados
            if (is_array($solicitud->documentos_adjuntos)) {
                foreach ($solicitud->documentos_adjuntos as $documento) {
                    if (isset($documento['ruta'])) {
                        Storage::delete('public/' . $documento['ruta']);
                    }
                }
            }

            // Eliminar detalle
            SolicitudDetalle::where('solicitud_id', $solicitud->id)->delete();

            // Eliminar solicitud
            $solicitud->delete();

            return [
                'success' => true,
                'message' => 'Solicitud eliminada con éxito'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function cambiarEstado(Request $request, $id)
    {
        try {
            $solicitud = Solicitud::findOrFail($id);
            $solicitud->estado = $request->estado;
            $solicitud->observaciones = $request->observaciones ?? $solicitud->observaciones;
            $solicitud->save();

            return [
                'success' => true,
                'message' => 'Estado de solicitud actualizado con éxito'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function downloadFile($id, $index)
    {
        try {
            $solicitud = Solicitud::findOrFail($id);

            if (!is_array($solicitud->documentos_adjuntos) || !isset($solicitud->documentos_adjuntos[$index])) {
                throw new Exception("El documento no existe");
            }

            $documento = $solicitud->documentos_adjuntos[$index];
            $ruta = $documento['ruta'];

            if (!Storage::disk('tenant')->exists($ruta)) {
                throw new Exception("El archivo no existe en el sistema");
            }

            return Storage::disk('tenant')->download($ruta, $documento['nombre']);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 404);
        }
    }

    public function deleteFile(Request $request, $id)
    {
        try {
            $solicitud = Solicitud::findOrFail($id);
            $index = $request->index;

            if (!is_array($solicitud->documentos_adjuntos) || !isset($solicitud->documentos_adjuntos[$index])) {
                throw new Exception("El documento no existe");
            }

            $documento = $solicitud->documentos_adjuntos[$index];
            $ruta = 'public/' . $documento['ruta'];

            // Eliminar archivo del storage
            if (Storage::exists($ruta)) {
                Storage::delete($ruta);
            }

            // Eliminar de la base de datos
            $documentos = $solicitud->documentos_adjuntos;
            array_splice($documentos, $index, 1);
            $solicitud->documentos_adjuntos = $documentos;
            $solicitud->save();

            return [
                'success' => true,
                'message' => 'Documento eliminado con éxito'
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => $e->getMessage()
            ];
        }
    }

    public function generarPDF($id)
    {
        try {
            $solicitud = Solicitud::findOrFail($id);
            $detalle = SolicitudDetalle::where('solicitud_id', $id)->get();

            $plantilla = 'tenant.solicitudes.pdf.' . $solicitud->tipo;

            // Verificar si existe la plantilla específica
            if (!view()->exists($plantilla)) {
                $plantilla = 'tenant.solicitudes.pdf.default';
            }

            $pdf = PDF::loadView($plantilla, [
                'solicitud' => $solicitud,
                'detalle' => $detalle
            ]);

            $filename = 'Solicitud_' . $solicitud->tipo . '_' . $solicitud->id . '.pdf';

            return $pdf->download($filename);
        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function upload(Request $request)
    {

        $validate_upload = UploadFileHelper::validateUploadFile($request, 'file', 'pdf', false);

        if (!$validate_upload['success']) {
            return $validate_upload;
        }

        if ($request->hasFile('file')) {
            $new_request = [
                'file' => $request->file('file'),
                'type' => $request->input('type'),
            ];

            return $this->upload_image($new_request);
        }
        return [
            'success' => false,
            'message' =>  __('app.actions.upload.error'),
        ];
    }

    function upload_image($request)
    {
        $file = $request['file'];
        $type = $request['type'];

        $temp = tempnam(sys_get_temp_dir(), $type);
        file_put_contents($temp, file_get_contents($file));

        $mime = mime_content_type($temp);
        $data = file_get_contents($temp);

        return [
            'success' => true,
            'data' => [
                'filename' => $file->getClientOriginalName(),
                'temp_path' => $temp,
                'temp_image' => 'data:' . $mime . ';base64,' . base64_encode($data)
            ]
        ];
    }
}
