<?php

namespace App\Services;

use Barryvdh\DomPDF\Facade as PDF;
use App\Models\Tenant\Company;

class PdfService
{
    /**
     * Genera un PDF con las configuraciones estándar (incluido footer)
     *
     * @param string $view Vista a renderizar
     * @param array $data Datos a pasar a la vista
     * @param string $filename Nombre del archivo
     * @param string $paper Tamaño del papel (A4, letter, etc)
     * @param string $orientation Orientación (portrait, landscape)
     * @return \Barryvdh\DomPDF\PDF
     */
    public static function generatePdf($view, $data = [], $filename = 'document.pdf', $paper = 'A4', $orientation = 'portrait')
    {
        // Añadir stylesheet si no está en los datos
        if (!isset($data['stylesheet'])) {
            $path_css = resource_path('views/pdf/style.css');
            $data['stylesheet'] = file_get_contents($path_css);
        }

        // Añadir company si no está en los datos
        if (!isset($data['company'])) {
            $data['company'] = Company::active();
        }

        // Cargar la vista
        $pdf = PDF::loadView($view, $data);

        // Configuración del PDF
        $pdf->setPaper($paper, $orientation);

        return $pdf;
    }

    /**
     * Genera un PDF sin el footer estándar
     *
     * @param string $view Vista a renderizar
     * @param array $data Datos a pasar a la vista
     * @param string $filename Nombre del archivo
     * @param string $paper Tamaño del papel (A4, letter, etc)
     * @param string $orientation Orientación (portrait, landscape)
     * @return \Barryvdh\DomPDF\PDF
     */
    public static function generatePdfWithoutFooter($view, $data = [], $filename = 'document.pdf', $paper = 'A4', $orientation = 'portrait')
    {
        // Añadir stylesheet si no está en los datos
        if (!isset($data['stylesheet'])) {
            $path_css = resource_path('views/pdf/style.css');
            $data['stylesheet'] = file_get_contents($path_css);
        }

        // Añadir company si no está en los datos
        if (!isset($data['company'])) {
            $data['company'] = Company::active();
        }

        // Cargar la vista directamente sin el footer
        $pdf = PDF::loadView($view, $data);

        // Configuración del PDF
        $pdf->setPaper($paper, $orientation);

        return $pdf;
    }
}
