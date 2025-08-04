<?php

namespace Modules\Payment\Models;

use App\Models\Tenant\Taxis\Vehiculos;
use Illuminate\Database\Eloquent\Model;
use Modules\Payment\Models\Subscription;
use Hyn\Tenancy\Traits\UsesTenantConnection;


class SubscriptionInvoice extends Model
{
    use UsesTenantConnection;

    protected $table = 'subscription_invoices';
    protected $fillable = [
        'subscription_id',
        'vehiculo_id',
        'monto',
        'monto_por_mes',
        'fecha_cobro',
        'estado',
        'data',
        'tipo',
        'descuento',
        'descuento_por_mes',
        'moneda',
        'metodo_pago',
        'payed_total',
        'user_id',
        'es_pago_multiple',
        'grupo_pago_id',
        'cantidad_meses',
        'payment_details',
        'mes',
        'year',
    ];

    protected $casts = [
        'data' => 'array',
        'mes' => 'integer',
        'year' => 'integer',
        'vehiculo_id' => 'integer',
        'subscription_id' => 'integer',
        'payed_total' => 'boolean',
        'es_pago_multiple' => 'boolean',
        'payment_details' => 'array',
        'cantidad_meses' => 'integer',
    ];

    public function subscription()
    {
        return $this->belongsTo(Subscription::class);
    }

    public function vehiculo()
    {
        return $this->belongsTo(Vehiculos::class);
    }

    /**
     * Obtener los meses pagados por año
     * @return array
     */
    public function getMesesPagadosPorAno()
    {
        return $this->data ?? [];
    }

    /**
     * Agregar mes pagado para un año específico
     * @param int $year
     * @param int $month
     * @return $this
     */
    public function agregarMesPagado(int $year, int $month)
    {
        $data = $this->data ?? [];

        if (!isset($data[$year])) {
            $data[$year] = [];
        }

        if (!in_array($month, $data[$year])) {
            $data[$year][] = $month;
        }

        $this->data = $data;

        return $this;
    }

    /**
     * Establecer múltiples meses pagados para un año
     * @param int $year
     * @param array $months
     * @return $this
     */
    public function establecerMesesPagados(int $year, array $months)
    {
        $data = $this->data ?? [];
        $data[$year] = $months;
        $this->data = $data;

        return $this;
    }

    public function getCollectionData()
    {
        return [
            'id' => $this->id,
            'subscription_id' => $this->subscription_id,
            'subscription' => $this->subscription ? $this->subscription->getCollectionData() : null,
            'vehiculo' => $this->vehiculo ? $this->vehiculo->getCollectionData() : null,
            'vehiculo_id' => $this->vehiculo_id,
            'monto' => $this->monto,
            'monto_por_mes' => $this->monto_por_mes,

            'fecha_cobro' => $this->fecha_cobro,
            'mes' => $this->mes,
            'year' => $this->year,
            'estado' => $this->estado,
            'data' => $this->data,
            'tipo' => $this->tipo,
            'descuento' => $this->descuento,
            'descuento_por_mes' => $this->descuento_por_mes,
            'moneda' => $this->moneda,
            'metodo_pago' => $this->metodo_pago,
            'payed_total' => $this->payed_total,
            'es_pago_multiple' => $this->es_pago_multiple,
            'grupo_pago_id' => $this->grupo_pago_id,
            'cantidad_meses' => $this->cantidad_meses,
            'payment_details' => $this->payment_details,
            'meses_pagados' => $this->data ? $this->getMesesPagadosFormateados() : []
        ];
    }

    /**
     * Obtiene los meses pagados en un formato más legible
     * @return array
     */
    public function getMesesPagadosFormateados()
    {
        $result = [];
        $data = $this->data ?? [];

        foreach ($data as $year => $meses) {
            foreach ($meses as $mes) {
                $result[] = [
                    'year' => $year,
                    'mes' => $mes,
                    'nombre_mes' => $this->getNombreMes($mes),
                    'fecha_completa' => $this->getNombreMes($mes) . ' ' . $year
                ];
            }
        }

        return $result;
    }

    /**
     * Obtiene el nombre del mes a partir de su número
     * @param int $mes
     * @return string
     */
    private function getNombreMes($mes)
    {
        $meses = [
            1 => 'Enero',
            2 => 'Febrero',
            3 => 'Marzo',
            4 => 'Abril',
            5 => 'Mayo',
            6 => 'Junio',
            7 => 'Julio',
            8 => 'Agosto',
            9 => 'Septiembre',
            10 => 'Octubre',
            11 => 'Noviembre',
            12 => 'Diciembre'
        ];

        return $meses[$mes] ?? 'Desconocido';
    }
}
