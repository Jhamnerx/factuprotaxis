<?php

namespace App\Helpers;

class DateHelper
{
    /**
     * Obtiene el nombre del mes en español
     * 
     * @param int $month Número del mes (1-12)
     * @return string Nombre del mes en español
     */
    public static function nombreMes($month)
    {
        $meses = [
            1 => 'enero',
            2 => 'febrero',
            3 => 'marzo',
            4 => 'abril',
            5 => 'mayo',
            6 => 'junio',
            7 => 'julio',
            8 => 'agosto',
            9 => 'septiembre',
            10 => 'octubre',
            11 => 'noviembre',
            12 => 'diciembre'
        ];

        return $meses[$month] ?? '';
    }

    /**
     * Formatea una fecha en formato español
     * 
     * @param \Carbon\Carbon|string|null $fecha La fecha a formatear
     * @param bool $conDia Incluir el día en el formato
     * @param bool $conAnio Incluir el año en el formato
     * @return string Fecha formateada en español
     */
    public static function formatoEspanol($fecha = null, $conDia = true, $conAnio = true)
    {
        if (is_null($fecha)) {
            $fecha = now();
        } elseif (is_string($fecha)) {
            $fecha = \Carbon\Carbon::parse($fecha);
        }

        $dia = $fecha->day;
        $mes = self::nombreMes($fecha->month);
        $anio = $fecha->year;

        if ($conDia && $conAnio) {
            return "{$dia} de {$mes} de {$anio}";
        } elseif ($conDia) {
            return "{$dia} de {$mes}";
        } elseif ($conAnio) {
            return "{$mes} de {$anio}";
        } else {
            return $mes;
        }
    }
}
