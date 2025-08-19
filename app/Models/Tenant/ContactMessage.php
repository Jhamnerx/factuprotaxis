<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;

class ContactMessage extends Model
{
    use UsesTenantConnection;
    protected $fillable = [
        'name',
        'phone',
        'email',
        'message',
        'status',
        'admin_notes',
        'read_at',
        'replied_at'
    ];

    protected $dates = [
        'read_at',
        'replied_at',
        'created_at',
        'updated_at'
    ];

    protected $casts = [
        'read_at' => 'datetime',
        'replied_at' => 'datetime',
    ];

    /**
     * Scope para mensajes pendientes
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope para mensajes leídos
     */
    public function scopeRead($query)
    {
        return $query->where('status', 'read');
    }

    /**
     * Marcar como leído
     */
    public function markAsRead()
    {
        $this->update([
            'status' => 'read',
            'read_at' => now()
        ]);
    }

    /**
     * Marcar como respondido
     */
    public function markAsReplied($notes = null)
    {
        $this->update([
            'status' => 'replied',
            'replied_at' => now(),
            'admin_notes' => $notes
        ]);
    }

    /**
     * Obtener color del estado
     */
    public function getStatusColorAttribute()
    {
        $colors = [
            'pending' => 'warning',
            'read' => 'info',
            'replied' => 'success',
            'closed' => 'secondary'
        ];

        return $colors[$this->status] ?? 'secondary';
    }

    /**
     * Obtener texto del estado
     */
    public function getStatusTextAttribute()
    {
        $statuses = [
            'pending' => 'Pendiente',
            'read' => 'Leído',
            'replied' => 'Respondido',
            'closed' => 'Cerrado'
        ];

        return $statuses[$this->status] ?? 'Desconocido';
    }
    public function getCollectionData()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'phone' => $this->phone,
            'email' => $this->email,
            'message' => $this->message,
            'status' => $this->status,
            'status_text' => $this->status_text,
            'status_color' => $this->status_color,
            'admin_notes' => $this->admin_notes,
            'read_at' => $this->read_at ? $this->read_at->format('Y-m-d H:i:s') : null,
            'replied_at' => $this->replied_at ? $this->replied_at->format('Y-m-d H:i:s') : null,
            'created_at' => $this->created_at->format('Y-m-d H:i:s'),
            'created_at_diff' => $this->created_at->diffForHumans(),
            'updated_at' => $this->updated_at->format('Y-m-d H:i:s'),
        ];
    }
}
