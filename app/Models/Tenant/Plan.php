<?php

namespace App\Models\Tenant;

use Illuminate\Database\Eloquent\Model;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\SoftDeletes;

class Plan extends Model
{
    use UsesTenantConnection, SoftDeletes;

    protected $table = 'plans';
    protected $guarded = ['id'];
    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'features' => 'array',
        'discounts' => 'array',
        'is_active' => 'boolean',
        'is_socio' => 'boolean',
        'active_subscribers_limit' => 'integer',
        'sort_order' => 'integer',
        'price' => 'decimal:2',
        'signup_fee' => 'decimal:2',
    ];

    public function getCollectionData()
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'description' => $this->description,
            'is_active' => $this->is_active,
            'price' => $this->price,
            'signup_fee' => $this->signup_fee,
            'currency' => $this->currency,
            'invoice_period' => $this->invoice_period,
            'invoice_interval' => $this->invoice_interval,
            'active_subscribers_limit' => $this->active_subscribers_limit,
            'sort_order' => $this->sort_order,
            'is_socio' => $this->is_socio,
            'slug' => $this->slug,
            'features' => $this->features ?? [],
            'discounts' => $this->discounts ?? [],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
