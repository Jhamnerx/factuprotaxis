<?php

namespace Modules\Payment\Models;

use Illuminate\Support\Collection;
use Modules\Payment\Models\Feature;
use App\Models\Tenant\Taxis\Vehiculos;
use Illuminate\Database\Eloquent\Model;
use Modules\Payment\Models\Subscription;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Laravelcm\Subscriptions\Models\Plan as BasePlan;

class Plan extends Model
{
    use UsesTenantConnection;

    use SoftDeletes;
    protected $table = 'plans';

    protected $fillable = [
        'slug',
        'name',
        'description',
        'is_active',
        'price',
        'signup_fee',
        'currency',
        'trial_period',
        'trial_interval',
        'invoice_period',
        'invoice_interval',
        'grace_period',
        'grace_interval',
        'prorate_day',
        'prorate_period',
        'prorate_extend_due',
        'active_subscribers_limit',
        'sort_order',
        'is_socio',
        'discounts',
        'type',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'price' => 'float',
        'signup_fee' => 'float',
        'deleted_at' => 'datetime',
        'discounts' => 'array',
        'is_socio' => 'boolean',
    ];

    protected static function boot(): void
    {
        parent::boot();
        static::deleted(function ($plan): void {
            $plan->features()->delete();
            $plan->subscriptions()->delete();
        });
    }

    public function features(): HasMany
    {
        return $this->hasMany(Feature::class);
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function lifetime(): bool
    {
        return $this->type == 'indeterminate' ? true : false;
    }

    public function isFree(): bool
    {
        return $this->price <= 0.00;
    }

    public function hasTrial(): bool
    {
        return $this->trial_period && $this->trial_interval;
    }

    public function hasGrace(): bool
    {
        return $this->grace_period && $this->grace_interval;
    }

    public function getFeatureBySlug(string $featureSlug): ?Feature
    {
        return $this->features()->where('slug', $featureSlug)->first();
    }

    public function activate(): self
    {
        $this->update(['is_active' => true]);

        return $this;
    }

    public function deactivate(): self
    {
        $this->update(['is_active' => false]);

        return $this;
    }

    public function vehiculos(): HasMany
    {
        return $this->hasMany(Vehiculos::class, 'plan_id');
    }
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
            'trial_period' => $this->trial_period,
            'trial_interval' => $this->trial_interval,
            'grace_period' => $this->grace_period,
            'grace_interval' => $this->grace_interval,
            'active_subscribers_limit' => $this->active_subscribers_limit,
            'sort_order' => $this->sort_order,
            'is_socio' => $this->is_socio,
            'type' => $this->type,
            'slug' => $this->slug,
            'features' => $this->features ?? [],
            'discounts' => $this->discounts ?? [],
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
            'deleted_at' => $this->deleted_at,
            // Propiedades de los métodos auxiliares
            'is_free' => $this->isFree(),
            'has_trial' => $this->hasTrial(),
            'has_grace' => $this->hasGrace(),
            'is_lifetime' => $this->lifetime(),
        ];
    }
}
