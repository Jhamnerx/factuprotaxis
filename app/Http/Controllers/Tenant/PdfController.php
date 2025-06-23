<?php

namespace App\Http\Controllers\Tenant;

use App\Models\Tenant\Company;
use App\Http\Controllers\Controller;
use App\Models\Tenant\Taxis\Solicitud;
use App\Models\Tenant\Taxis\Vehiculos;
use Barryvdh\DomPDF\Facade as PDF;
use setasign\Fpdi\Fpdi;

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
        }

        // Generar el PDF principal
        $pdf = PDF::loadView(
            $view,
            compact('solicitud', 'company', 'stylesheet', 'establishment')
        );
        $pdf->setPaper('A4', 'portrait');

        // Si es una solicitud de baja y tiene habilitada la opción de constancia, combinar con constancia
        $requiereConstancia = false;

        // Verificar si se requiere constancia en diferentes formas posibles
        if ($tipo == 'baja') {
            if (isset($solicitud->documentos_adjuntos['constancia']) && $solicitud->documentos_adjuntos['constancia']) {
                $requiereConstancia = true;
            } elseif (isset($solicitud->documentos_adjuntos['requiere_constancia']) && $solicitud->documentos_adjuntos['requiere_constancia']) {
                $requiereConstancia = true;
            } elseif (isset($solicitud->documentos_adjuntos['generar_constancia']) && $solicitud->documentos_adjuntos['generar_constancia']) {
                $requiereConstancia = true;
            }
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
            }

            // Guardar el PDF de solicitud en un archivo temporal
            $solicitudPath = $tempDir . '/solicitud_' . $solicitud->id . '.pdf';
            file_put_contents($solicitudPath, $pdfSolicitud->output());

            // Generar constancia de baja
            $company = Company::active();
            $establishment = auth()->user()->establishment;
            $path_css = resource_path('views/pdf/style.css');
            $stylesheet = file_get_contents($path_css);

            // Aquí se define la vista para la constancia
            $pdfConstancia = PDF::loadView(
                'pdf.unidades.constancia_baja',
                compact('solicitud', 'company', 'stylesheet', 'establishment')
            );
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
}
