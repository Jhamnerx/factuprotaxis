<?php

namespace App\Models\Tenant\Taxis;

use Illuminate\Database\Eloquent\Model;
use Modules\Payment\Models\SubscriptionInvoice;

class YapeNotification extends Model
{
    protected $table = 'yape_notifications';

    protected $fillable = [
        'sender',
        'amount',
        'notification_date',
        'message',
        'raw_notification',
        'package_name',
        'title',
        'subscription_invoice_id',
        'is_used',
        'used_at'
    ];

    protected $casts = [
        'raw_notification' => 'array',
        'notification_date' => 'datetime',
        'used_at' => 'datetime',
        'is_used' => 'boolean',
        'amount' => 'decimal:2'
    ];

    /**
     * Relación con SubscriptionInvoice
     */
    public function subscriptionInvoice()
    {
        return $this->belongsTo(SubscriptionInvoice::class, 'subscription_invoice_id');
    }

    /**
     * Scope para notificaciones no utilizadas
     */
    public function scopeUnused($query)
    {
        return $query->where('is_used', false);
    }

    /**
     * Scope para notificaciones utilizadas
     */
    public function scopeUsed($query)
    {
        return $query->where('is_used', true);
    }

    /**
     * Scope para buscar por monto y fecha
     */
    public function scopeByAmountAndDate($query, $amount, $date)
    {
        return $query->where('amount', $amount)
            ->whereDate('notification_date', $date);
    }

    /**
     * Scope para búsqueda flexible de nombre/titular
     */
    public function scopeByFlexibleSender($query, $sender)
    {
        // Remover caracteres especiales y espacios extras para comparación flexible
        $cleanSender = preg_replace('/[^a-zA-Z\s]/', '', $sender);
        $cleanSender = preg_replace('/\s+/', ' ', trim($cleanSender));

        return $query->where(function ($q) use ($sender, $cleanSender) {
            // Búsqueda exacta
            $q->where('sender', 'LIKE', "%{$sender}%")
                // Búsqueda flexible sin caracteres especiales
                ->orWhere('sender', 'LIKE', "%{$cleanSender}%")
                // Búsqueda por palabras individuales
                ->orWhere(function ($subQ) use ($cleanSender) {
                    $words = explode(' ', $cleanSender);
                    foreach ($words as $word) {
                        if (strlen($word) > 2) { // Solo palabras de más de 2 caracteres
                            $subQ->where('sender', 'LIKE', "%{$word}%");
                        }
                    }
                });
        });
    }

    /**
     * Marcar la notificación como utilizada
     */
    public function markAsUsed($subscriptionInvoiceId = null)
    {
        $this->update([
            'is_used' => true,
            'used_at' => now(),
            'subscription_invoice_id' => $subscriptionInvoiceId
        ]);
    }

    /**
     * Desmarcar la notificación como utilizada
     */
    public function markAsUnused()
    {
        $this->update([
            'is_used' => false,
            'used_at' => null,
            'subscription_invoice_id' => null
        ]);
    }

    /**
     * Buscar notificaciones compatibles para validación
     */
    public static function findCompatibleNotifications($sender, $amount, $date, $flexibleSearch = true)
    {
        $query = static::unused()
            ->byAmountAndDate($amount, $date);

        if ($flexibleSearch) {
            $query->byFlexibleSender($sender);
        } else {
            $query->where('sender', 'LIKE', "%{$sender}%");
        }

        return $query->orderBy('notification_date', 'desc')->get();
    }
}
