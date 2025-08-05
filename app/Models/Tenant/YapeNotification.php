<?php

namespace App\Models\Tenant;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Modules\Payment\Models\SubscriptionInvoice;

class YapeNotification extends Model
{
    use UsesTenantConnection;

    protected $fillable = [
        'sender',
        'amount',
        'notification_date',
        'message',
        'raw_notification',
        'package_name',
        'title',
        'subscription_invoice_id',
        'codigo_seguridad',
        'is_used',
        'used_at'
    ];

    protected $casts = [
        'raw_notification' => 'array',
        'notification_date' => 'datetime',
        'amount' => 'decimal:2',
        'is_used' => 'boolean',
        'used_at' => 'datetime'
    ];

    // Scope para filtrar por fecha
    public function scopeByDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('notification_date', [$startDate, $endDate]);
    }

    // Scope para filtrar por remitente
    public function scopeBySender($query, $sender)
    {
        return $query->where('sender', 'like', '%' . $sender . '%');
    }

    // Accessor para formatear el monto
    public function getFormattedAmountAttribute()
    {
        return 'S/ ' . number_format($this->amount, 2);
    }

    // Relación con SubscriptionInvoice
    public function subscriptionInvoice()
    {
        return $this->belongsTo(SubscriptionInvoice::class);
    }

    // Scope para obtener notificaciones no utilizadas
    public function scopeUnused($query)
    {
        return $query->where('is_used', false)->orWhereNull('is_used');
    }

    // Scope para obtener notificaciones utilizadas
    public function scopeUsed($query)
    {
        return $query->where('is_used', true);
    }

    // Scope para búsqueda flexible de nombres
    public function scopeFlexibleSenderSearch($query, $searchTerm)
    {
        // Remover caracteres especiales y espacios extras
        $cleanSearchTerm = preg_replace('/[^\p{L}\p{N}\s]/u', '', $searchTerm);
        $cleanSearchTerm = preg_replace('/\s+/', ' ', trim($cleanSearchTerm));

        // Dividir en palabras
        $words = explode(' ', $cleanSearchTerm);

        return $query->where(function ($q) use ($words, $searchTerm) {
            // Búsqueda exacta primero
            $q->where('sender', 'like', '%' . $searchTerm . '%');

            // Luego búsqueda por palabras individuales
            foreach ($words as $word) {
                if (strlen($word) >= 2) { // Solo palabras de 2+ caracteres
                    $q->orWhere('sender', 'like', '%' . $word . '%');
                }
            }
        });
    }

    // Método para marcar como utilizada
    public function markAsUsed($subscriptionInvoiceId = null)
    {
        $this->update([
            'is_used' => true,
            'used_at' => now(),
            'subscription_invoice_id' => $subscriptionInvoiceId
        ]);
    }

    // Método para verificar si está disponible para uso
    public function isAvailableForUse()
    {
        return !$this->is_used;
    }

    // Método estático para encontrar notificaciones compatibles
    public static function findCompatibleNotifications($titularYape, $monto, $fechaPago, $busquedaFlexible = true, $codigoSeguridad = null)
    {
        $query = self::unused() // Solo notificaciones no utilizadas
            ->where('amount', $monto);

        // Búsqueda de remitente
        if ($busquedaFlexible) {
            $query->flexibleSenderSearch($titularYape);
        } else {
            $query->bySender($titularYape);
        }

        // Rango de fechas (el día del pago ± 1 día para tolerar diferencias horarias)
        $fechaInicio = Carbon::parse($fechaPago)->startOfDay()->subDay();
        $fechaFin = Carbon::parse($fechaPago)->endOfDay()->addDay();

        $query->byDateRange($fechaInicio, $fechaFin);

        // Filtrar por código de seguridad si se proporciona
        if (!empty($codigoSeguridad)) {
            $query->where(function ($q) use ($codigoSeguridad) {
                $q->where('raw_notification', 'like', '%' . $codigoSeguridad . '%')
                    ->orWhere('codigo_seguridad', 'like', '%' . $codigoSeguridad . '%')
                    ->orWhere('message', 'like', '%' . $codigoSeguridad . '%');
            });
        }

        return $query->orderBy('notification_date', 'desc')->get();
    }

    /**
     * Obtener datos para la colección (DataTable)
     */
    public function getCollectionData($withRelations = false)
    {
        return [
            'id' => $this->id,
            'sender' => $this->sender,
            'amount' => $this->amount,
            'notification_date' => $this->notification_date ? $this->notification_date->format('d/m/Y H:i') : null,
            'message' => $this->message,
            'codigo_seguridad' => $this->codigo_seguridad,
            'is_used' => $this->is_used,
            'used_at' => $this->used_at ? $this->used_at->format('d/m/Y H:i') : null,
            'created_at' => $this->created_at ? $this->created_at->format('d/m/Y H:i') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('d/m/Y H:i') : null,
        ];
    }
}
