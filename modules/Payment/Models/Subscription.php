<?php

declare(strict_types=1);

namespace Modules\Payment\Models;

use Carbon\Carbon;
use LogicException;
use App\Services\Period;
use App\Traits\BelongsToPlan;
use Modules\Payment\Models\Plan;
use Spatie\Sluggable\SlugOptions;



use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Hyn\Tenancy\Traits\UsesTenantConnection;
use Illuminate\Database\Eloquent\SoftDeletes;
use Modules\Payment\Models\SubscriptionUsage;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class Subscription extends Model
{
    use UsesTenantConnection;

    use BelongsToPlan;
    use SoftDeletes;

    protected $table = 'subscriptions';
    protected $fillable = [
        'subscriber_id',
        'subscriber_type',
        'plan_id',
        'slug',
        'name',
        'description',
        'trial_ends_at',
        'starts_at',
        'ends_at',
        'cancels_at',
        'canceled_at',
        'is_indeterminate',
        'metadata',
    ];

    protected $casts = [
        'subscriber_type' => 'string',
        'slug' => 'string',
        'trial_ends_at' => 'datetime',
        'starts_at' => 'datetime',
        'ends_at' => 'datetime',
        'cancels_at' => 'datetime',
        'canceled_at' => 'datetime',
        'deleted_at' => 'datetime',
        'is_indeterminate' => 'boolean',
        'metadata' => 'array',
    ];

    protected $with = [
        'plan',
    ];

    /**
     * The attributes that are translatable.
     *
     * @var array
     */
    public $translatable = [
        'name',
        'description',
    ];

    public function getTable()
    {
        return 'subscriptions';
    }
    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (self $model): void {
            // Verificar si el plan es indeterminado
            if ($model->plan && $model->plan->type === 'indeterminate') {
                $model->is_indeterminate = true;
                // Para planes indeterminados solo establecemos la fecha de inicio
                if (!$model->starts_at) {
                    $model->starts_at = Carbon::now();
                }
                // No establecemos ends_at para planes indeterminados
            }
            // Para planes normales, calculamos el período si no está establecido
            elseif (!$model->starts_at || !$model->ends_at) {
                $model->setNewPeriod();
            }
        });

        static::deleted(function (self $subscription): void {
            $subscription->usage()->delete();
        });
    }

    public function subscriber(): MorphTo
    {
        return $this->morphTo('subscriber', 'subscriber_type', 'subscriber_id', 'id');
    }

    public function usage(): HasMany
    {
        return $this->hasMany(SubscriptionUsage::class);
    }
    public function active(): bool
    {
        // Si es un plan indeterminado, siempre está activo a menos que esté cancelado
        if ($this->isIndeterminate()) {
            return !$this->canceled();
        }

        return !$this->ended() || $this->onTrial();
    }

    public function inactive(): bool
    {
        return !$this->active();
    }

    public function onTrial(): bool
    {
        return $this->trial_ends_at && Carbon::now()->lt($this->trial_ends_at);
    }

    public function canceled(): bool
    {
        return $this->canceled_at && Carbon::now()->gte($this->canceled_at);
    }

    public function ended(): bool
    {
        // Un plan indeterminado nunca termina por fecha, solo por cancelación
        if ($this->isIndeterminate()) {
            return false;
        }

        return $this->ends_at && Carbon::now()->gte($this->ends_at);
    }

    /**
     * Determina si la suscripción es de tipo indeterminado (sin fecha de fin)
     *
     * @return bool
     */
    public function isIndeterminate(): bool
    {
        return $this->is_indeterminate || ($this->plan && $this->plan->type === 'indeterminate');
    }

    public function cancel(bool $immediately = false): self
    {
        $this->canceled_at = Carbon::now();

        if ($immediately) {
            $this->ends_at = $this->canceled_at;
        }

        $this->save();

        return $this;
    }

    public function changePlan(Plan $plan): self
    {
        // If plans does not have the same billing frequency
        // (e.g., invoice_interval and invoice_period) we will update
        // the billing dates starting today, and since we are basically creating
        // a new billing cycle, the usage data will be cleared.
        if ($this->plan->invoice_interval !== $plan->invoice_interval || $this->plan->invoice_period !== $plan->invoice_period) {
            $this->setNewPeriod($plan->invoice_interval, $plan->invoice_period);
            $this->usage()->delete();
        }

        // Attach new plan to subscription
        $this->plan_id = $plan->getKey();
        $this->save();

        return $this;
    }

    /**
     * Renew subscription period.
     *
     * @return $this
     *
     * @throws LogicException
     */
    public function renew(): self
    {
        if ($this->ended() && $this->canceled()) {
            throw new LogicException('Unable to renew canceled ended subscription.');
        }

        $subscription = $this;

        DB::transaction(function () use ($subscription): void {
            // Clear usage data
            $subscription->usage()->delete();

            // Renew period
            $subscription->setNewPeriod();
            $subscription->canceled_at = null;
            $subscription->save();
        });

        return $this;
    }

    /**
     * Get bookings of the given subscriber.
     */
    public function scopeOfSubscriber(Builder $builder, Model $subscriber): Builder
    {
        return $builder->where('subscriber_type', $subscriber->getMorphClass())
            ->where('subscriber_id', $subscriber->getKey());
    }

    /**
     * Scope subscriptions with ending trial.
     */
    public function scopeFindEndingTrial(Builder $builder, int $dayRange = 3): Builder
    {
        $from = Carbon::now();
        $to = Carbon::now()->addDays($dayRange);

        return $builder->whereBetween('trial_ends_at', [$from, $to]);
    }

    /**
     * Scope subscriptions with ended trial.
     */
    public function scopeFindEndedTrial(Builder $builder): Builder
    {
        return $builder->where('trial_ends_at', '<=', Carbon::now());
    }

    /**
     * Scope subscriptions with ending periods.
     */
    public function scopeFindEndingPeriod(Builder $builder, int $dayRange = 3): Builder
    {
        $from = Carbon::now();
        $to = Carbon::now()->addDays($dayRange);

        return $builder->whereBetween('ends_at', [$from, $to]);
    }

    /**
     * Scope subscriptions with ended periods.
     */
    public function scopeFindEndedPeriod(Builder $builder): Builder
    {
        return $builder->where('ends_at', '<=', Carbon::now());
    }

    /**
     * Scope all active subscriptions for a user.
     */
    public function scopeFindActive(Builder $builder): Builder
    {
        return $builder->where('ends_at', '>', Carbon::now());
    }
    /**
     * Set new subscription period.
     *
     * @return $this
     */
    protected function setNewPeriod(string $invoice_interval = '', ?int $invoice_period = null, ?Carbon $start = null): self
    {
        if (empty($invoice_interval)) {
            $invoice_interval = $this->plan->invoice_interval;
        }

        if (empty($invoice_period)) {
            $invoice_period = $this->plan->invoice_period;
        }

        // Si el intervalo es indeterminado, establecemos la bandera y solo definimos la fecha de inicio
        if ($invoice_interval === 'indeterminate' || $this->plan->type === 'indeterminate') {
            $this->is_indeterminate = true;
            $this->starts_at = $start ?? Carbon::now();
            // No establecemos ends_at para planes indeterminados
            return $this;
        }

        // Para planes normales (no indeterminados), continuamos con el comportamiento estándar
        $this->is_indeterminate = false;

        $period = new Period(
            $invoice_interval,
            $invoice_period,
            $start ?? Carbon::now()
        );

        $this->starts_at = $period->getStartDate();
        $this->ends_at = $period->getEndDate();

        return $this;
    }

    public function recordFeatureUsage(string $featureSlug, int $uses = 1, bool $incremental = true): SubscriptionUsage
    {
        $feature = $this->plan->features()->where('slug', $featureSlug)->first();

        $usage = $this->usage()->firstOrNew([
            'subscription_id' => $this->getKey(),
            'feature_id' => $feature->getKey(),
        ]);

        if ($feature->resettable_period) {
            // Set expiration date when the usage record is new or doesn't have one.
            if ($usage->valid_until === null) {
                // Set date from subscription creation date so the reset
                // period match the period specified by the subscription's plan.
                $usage->valid_until = $feature->getResetDate($this->created_at);
            } elseif ($usage->expired()) {
                // If the usage record has been expired, let's assign
                // a new expiration date and reset the uses to zero.
                $usage->valid_until = $feature->getResetDate($usage->valid_until);
                $usage->used = 0;
            }
        }

        $usage->used = $incremental ? $usage->used + $uses : $uses;

        $usage->save();

        return $usage;
    }

    public function reduceFeatureUsage(string $featureSlug, int $uses = 1): ?SubscriptionUsage
    {
        $usage = $this->usage()->byFeatureSlug($featureSlug, $this->plan_id)->first();

        if ($usage === null) {
            return null;
        }

        $usage->used = max($usage->used - $uses, 0);

        $usage->save();

        return $usage;
    }

    /**
     * Determine if the feature can be used.
     */
    public function canUseFeature(string $featureSlug): bool
    {
        $featureValue = $this->getFeatureValue($featureSlug);
        $usage = $this->usage()->byFeatureSlug($featureSlug, $this->plan_id)->first();

        if ($featureValue === 'true') {
            return true;
        }

        // If the feature value is zero, let's return false since
        // there's no uses available. (useful to disable countable features)
        if (! $usage || $usage->expired() || $featureValue === null || $featureValue === '0' || $featureValue === 'false') {
            return false;
        }

        // Check for available uses
        return $this->getFeatureRemainings($featureSlug) > 0;
    }

    /**
     * Get how many times the feature has been used.
     */
    public function getFeatureUsage(string $featureSlug): int
    {
        $usage = $this->usage()->byFeatureSlug($featureSlug, $this->plan_id)->first();

        return (! $usage || $usage->expired()) ? 0 : $usage->used;
    }

    /**
     * Get the available uses.
     */
    public function getFeatureRemainings(string $featureSlug): int
    {
        return $this->getFeatureValue($featureSlug) - $this->getFeatureUsage($featureSlug);
    }

    public function getFeatureValue(string $featureSlug): ?string
    {
        $feature = $this->plan->features()->where('slug', $featureSlug)->first();

        return $feature->value ?? null;
    }
    public function getCollectionData(): array
    {
        $data = [
            'id' => $this->id,
            'subscriber_type' => $this->subscriber_type,
            'subscriber_id' => $this->subscriber_id,
            'plan_id' => $this->plan_id,
            'plan' => $this->plan->getCollectionData(),
            'name' => $this->name,
            'slug' => $this->slug,
            'description' => $this->description,
            'timezone' => $this->timezone,
            'trial_ends_at' => $this->trial_ends_at ? $this->trial_ends_at->format('Y-m-d H:i:s') : null,
            'starts_at' => $this->starts_at ? $this->starts_at->format('Y-m-d H:i:s') : null,
            'ends_at' => $this->ends_at ? $this->ends_at->format('Y-m-d H:i:s') : null,
            'cancels_at' => $this->cancels_at ? $this->cancels_at->format('Y-m-d H:i:s') : null,
            'canceled_at' => $this->canceled_at ? $this->canceled_at->format('Y-m-d H:i:s') : null,
            'comentario' => $this->comentario,
            'vehiculo' => $this->vehiculo,
            'status' => $this->status,
            'created_at' => $this->created_at ? $this->created_at->format('Y-m-d H:i:s') : null,
            'updated_at' => $this->updated_at ? $this->updated_at->format('Y-m-d H:i:s') : null,
            'deleted_at' => $this->deleted_at ? $this->deleted_at->format('Y-m-d H:i:s') : null,
            'is_active' => $this->active(),
            'is_inactive' => $this->inactive(),
            'is_on_trial' => $this->onTrial(),
            'is_canceled' => $this->canceled(),
            'is_ended' => $this->ended(),
            'is_indeterminate' => $this->isIndeterminate(),
            'metadata' => $this->metadata,
        ];

        return $data;
    }
}
