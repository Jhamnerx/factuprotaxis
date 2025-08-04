<?php

namespace App\Models\Tenant;


use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

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
        'title'
    ];

    protected $casts = [
        'raw_notification' => 'array',
        'notification_date' => 'datetime',
        'amount' => 'decimal:2'
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
}
