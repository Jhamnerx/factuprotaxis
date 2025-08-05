<?php

namespace App\Http\Resources\Tenant;

use Illuminate\Http\Resources\Json\JsonResource;

class YapeNotificationResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'sender' => $this->sender,
            'amount' => $this->amount,
            'formatted_amount' => $this->formatted_amount,
            'notification_date' => $this->notification_date ? $this->notification_date->format('Y-m-d H:i:s') : null,
            'notification_date_formatted' => $this->notification_date ? $this->notification_date->format('d/m/Y H:i') : null,
            'message' => $this->message,
            'raw_notification' => $this->raw_notification,
            'package_name' => $this->package_name,
            'title' => $this->title,
            'subscription_invoice_id' => $this->subscription_invoice_id,
            'codigo_seguridad' => $this->codigo_seguridad,
            'is_used' => $this->is_used,
            'used_at' => $this->used_at ? $this->used_at->format('Y-m-d H:i:s') : null,
            'used_at_formatted' => $this->used_at ? $this->used_at->format('d/m/Y H:i') : null,
            'status_text' => $this->is_used ? 'Usado' : 'Disponible',
            'status_class' => $this->is_used ? 'badge-danger' : 'badge-success',
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'subscription_invoice' => $this->whenLoaded('subscriptionInvoice'),
            'is_available_for_use' => $this->isAvailableForUse(),
        ];
    }
}
