<?php

namespace App\Http\Controllers\Tenant;

use setasign\Fpdi\Fpdi;
use App\Models\Tenant\Company;
use Barryvdh\DomPDF\Facade as PDF;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Taxis\Solicitud;
use App\Models\Tenant\Taxis\Vehiculos;
use App\Models\Tenant\Taxis\Declaracion;
use App\Models\Tenant\Taxis\ConstanciaBaja;

class PdfController extends Controller
{
    public function contrato($id)
    {
        $vehiculo = Vehiculos::findOrFail($id);
        $company = Company::active();
        $establishment = auth()->user()->establishment;
        $path_css = resource_path('views/pdf/style.css');
        $stylesheet = file_get_contents($path_css);

        // Generar el PDF directamente sin usar PdfService
        $pdf = PDF::loadView(
            'pdf.taxis.contrato',
            compact('vehiculo', 'company', 'stylesheet', 'establishment')
        );
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('contrato_' . $vehiculo->placa . '.pdf');
    }


    public function constancias($id)
    {
        $constancia = ConstanciaBaja::findOrFail($id);
        $company = Company::active();
        $establishment = auth()->user()->establishment;
        $path_css = resource_path('views/pdf/style.css');
        $stylesheet = file_get_contents($path_css);        // Generar el PDF directamente sin usar PdfService
        $pdf = PDF::loadView(
            'pdf.unidades.constancia_baja',
            compact('constancia', 'company', 'stylesheet', 'establishment')
        );
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true
        ]);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('constancia_' . $constancia->id . '.pdf');
    }



    public function solicitudes($id)
    {
        $solicitud = Solicitud::findOrFail($id);
        $company = Company::active();
        $establishment = auth()->user()->establishment;
        $path_css = resource_path('views/pdf/style.css');
        $stylesheet = file_get_contents($path_css);

        $tipo = $solicitud->tipo;
        $tipo_baja = $solicitud->tipo_baja;

        // Definir vista según el tipo de solicitud
        switch ($tipo) {
            case 'baja':
                if ($tipo_baja == 'vinculo') {
                    $view = 'pdf.unidades.solicitud_baja_vinculo';
                } elseif ($tipo_baja == 'constancia') {
                    $view = 'pdf.unidades.solicitud_baja_constancia';
                } elseif ($tipo_baja == 'independiente') {
                    $view = 'pdf.unidades.solicitud_baja_independiente';
                }
                break;
            case 'registro':
                $view = 'pdf.unidades.solicitud_registro';
                break;
            case 'emision':
                $view = 'pdf.unidades.solicitud_emision';
                break;
            case 'correccion_datos':
                $view = 'pdf.unidades.solicitud_correccion_datos';
                break;
            default:
                abort(404, 'Tipo de solicitud no soportado');
        }        // Generar el PDF principal
        $pdf = PDF::loadView(
            $view,
            compact('solicitud', 'company', 'stylesheet', 'establishment')
        );
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true
        ]);
        $pdf->setPaper('A4', 'portrait');

        // Si es una solicitud de baja y tiene habilitada la opción de constancia, combinar con constancia
        $requiereConstancia = false;

        if ($tipo == 'baja' && $tipo_baja == 'constancia') {
            $requiereConstancia = true;
        }


        if ($requiereConstancia) {

            // Implementar la combinación de PDFs
            return $this->combinarPdfs($pdf, $solicitud);
        }

        return $pdf->stream('solicitud_' . $solicitud->id . '.pdf');
    }
    /**
     * Combina el PDF de solicitud con la constancia de baja
     *
     * @param \Barryvdh\DomPDF\PDF $pdfSolicitud
     * @param Solicitud $solicitud
     * @return \Illuminate\Http\Response
     */
    private function combinarPdfs($pdfSolicitud, $solicitud)
    {
        try {
            // Crear directorio temporal si no existe
            $tempDir = storage_path('app/temp');
            if (!file_exists($tempDir)) {
                mkdir($tempDir, 0755, true);
            }            // Guardar el PDF de solicitud en un archivo temporal
            $solicitudPath = $tempDir . '/solicitud_' . $solicitud->id . '.pdf';
            file_put_contents($solicitudPath, $pdfSolicitud->output());

            // Generar constancia de baja
            $company = Company::active();
            $establishment = auth()->user()->establishment;
            $path_css = resource_path('views/pdf/style.css');
            $stylesheet = file_get_contents($path_css);

            // Obtenemos la constancia de baja relacionada con la solicitud
            $constancia = $solicitud->constanciaBaja;            // Si no existe la constancia relacionada, creamos una vista con los datos de solicitud
            // Si existe, usamos la constancia para generar el PDF
            if (!$constancia) {
                // Aquí se define la vista para la constancia usando datos de la solicitud
                $pdfConstancia = PDF::loadView(
                    'pdf.unidades.constancia_baja',
                    compact('solicitud', 'company', 'stylesheet', 'establishment')
                );
                $pdfConstancia->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isPhpEnabled' => true
                ]);
            } else {
                // Aquí se define la vista para la constancia usando la constancia relacionada
                $pdfConstancia = PDF::loadView(
                    'pdf.unidades.constancia_baja',
                    compact('constancia', 'company', 'stylesheet', 'establishment')
                );
                $pdfConstancia->setOptions([
                    'isHtml5ParserEnabled' => true,
                    'isRemoteEnabled' => true,
                    'isPhpEnabled' => true
                ]);
            }

            $pdfConstancia->setPaper('A4', 'portrait');

            // Guardar la constancia en un archivo temporal
            $constanciaPath = $tempDir . '/constancia_' . $solicitud->id . '.pdf';
            file_put_contents($constanciaPath, $pdfConstancia->output());

            // Combinar los PDFs usando FPDI
            $fpdi = new Fpdi();

            // Agregar páginas del primer PDF (solicitud)
            $pageCount = $fpdi->setSourceFile($solicitudPath);
            for ($i = 1; $i <= $pageCount; $i++) {
                $template = $fpdi->importPage($i);
                $size = $fpdi->getTemplateSize($template);
                $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $fpdi->useTemplate($template);
            }

            // Agregar páginas del segundo PDF (constancia)
            $pageCount = $fpdi->setSourceFile($constanciaPath);
            for ($i = 1; $i <= $pageCount; $i++) {
                $template = $fpdi->importPage($i);
                $size = $fpdi->getTemplateSize($template);
                $fpdi->AddPage($size['orientation'], [$size['width'], $size['height']]);
                $fpdi->useTemplate($template);
            }

            // Generar el PDF combinado
            $combinedPdfContent = $fpdi->Output('S');

            // Limpiar archivos temporales
            @unlink($solicitudPath);
            @unlink($constanciaPath);

            // Devolver el PDF combinado como respuesta
            return response($combinedPdfContent)
                ->header('Content-Type', 'application/pdf')
                ->header('Content-Disposition', 'inline; filename="solicitud_constancia_' . $solicitud->id . '.pdf"');
        } catch (\Exception $e) {
            // Si hay un error en la combinación, al menos devolver la solicitud original
            return $pdfSolicitud->stream('solicitud_' . $solicitud->id . '.pdf');
        }
    }
    public function declaraciones($id)
    {
        // Intentar encontrar la declaración, si existe
        $declaracion = null;
        $vehiculo = null;

        // Comprobar si es un ID de declaración o un ID de vehículo
        try {
            $declaracion = Declaracion::findOrFail($id);
        } catch (\Exception $e) {
            // Si no encuentra la declaración, intentamos buscar el vehículo
            try {
                $vehiculo = Vehiculos::findOrFail($id);
            } catch (\Exception $ex) {
                // Si no encuentra ni declaración ni vehículo, devolvemos error 404
                abort(404, 'Declaración o Vehículo no encontrado');
            }
        }

        $company = Company::active();
        $establishment = auth()->user()->establishment;
        $path_css = resource_path('views/pdf/style.css');
        $stylesheet = file_get_contents($path_css);

        if ($declaracion) {
            // Si encontramos la declaración, la usamos para el PDF
            $pdf = PDF::loadView(
                'pdf.unidades.declaracion',
                compact('declaracion', 'company', 'stylesheet', 'establishment')
            );
            $filename = 'declaracion_' . $declaracion->id . '.pdf';
        } else {
            // Si encontramos el vehículo, lo usamos para el PDF
            $pdf = PDF::loadView(
                'pdf.unidades.declaracion',
                compact('vehiculo', 'company', 'stylesheet', 'establishment')
            );
            $filename = 'declaracion_jurada_' . $vehiculo->placa . '.pdf';
        }

        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true,
            'dpi' => 150,
            'defaultFont' => 'Arial',
            'isFontSubsettingEnabled' => true,
            'debugCss' => false
        ]);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream($filename);
    }

    public function permisoViaje($id)
    {
        $permiso = \App\Models\Tenant\Taxis\PermisoUnidad::findOrFail($id);
        $company = Company::active();
        $establishment = auth()->user()->establishment;
        $path_css = resource_path('views/pdf/style.css');
        $stylesheet = file_get_contents($path_css);

        $pdf = PDF::loadView(
            'pdf.taxis.permiso_viaje',
            compact('permiso', 'company', 'stylesheet', 'establishment')
        );
        $pdf->setOptions([
            'isHtml5ParserEnabled' => true,
            'isRemoteEnabled' => true,
            'isPhpEnabled' => true
        ]);
        $pdf->setPaper('A4', 'portrait');

        return $pdf->stream('permiso_viaje_' . $permiso->id . '.pdf');
    }
}
