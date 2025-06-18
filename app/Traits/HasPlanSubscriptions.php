<?php

declare(strict_types=1);

namespace App\Traits;

use Carbon\Carbon;
use App\Services\Period;


use Modules\Payment\Models\Plan;
use Modules\Payment\Models\Subscription;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Relations\MorphMany;


trait HasPlanSubscriptions
{
    protected static function bootHasSubscriptions()
    {
        static::deleted(function ($plan): void {
            $plan->subscriptions()->delete();
        });
    }

    /**
     * The subscriber may have many plan subscriptions.
     */
    public function planSubscriptions()
    {
        return $this->morphMany(
            Subscription::class,
            'subscriber',
            'subscriber_type',
            'subscriber_id'
        );
    }

    public function activePlanSubscriptions()
    {
        return $this->planSubscriptions->reject->inactive();
    }

    public function planSubscription(string $subscriptionSlug)
    {
        return $this->planSubscriptions()->where('slug', 'like', '%' . $subscriptionSlug . '%')->first();
    }

    public function subscribedPlans()
    {
        $planIds = $this->planSubscriptions->reject
            ->inactive()
            ->pluck('plan_id')
            ->unique();

        return tap(new Plan())->whereIn('id', $planIds)->get();
    }

    public function subscribedTo(int $planId)
    {
        $subscription = $this->planSubscriptions()
            ->where('plan_id', $planId)
            ->first();

        return $subscription && $subscription->active();
    }
    public function newPlanSubscription(string $subscription, Plan $plan, ?Carbon $startDate = null)
    {
        $startDate = $startDate ?? Carbon::now();

        // Verificar si es un plan indeterminado
        if ($plan->lifetime()) {
            return $this->planSubscriptions()->create([
                'name' => $subscription,
                'slug' => $subscription . '-' . $plan->slug,
                'plan_id' => $plan->getKey(),
                'starts_at' => $startDate,
                'is_indeterminate' => true,
            ]);
        }

        // Para planes normales, seguimos el flujo estándar con período
        $trial = new Period(
            $plan->trial_interval,
            $plan->trial_period,
            $startDate
        );

        $period = new Period(
            $plan->invoice_interval,
            $plan->invoice_period,
            $trial->getEndDate()
        );

        return $this->planSubscriptions()->create([
            'name' => $subscription,
            'slug' => $subscription . '-' . $plan->slug,
            'plan_id' => $plan->getKey(),
            'trial_ends_at' => $trial->getEndDate(),
            'starts_at' => $period->getStartDate(),
            'ends_at' => $period->getEndDate(),
            'is_indeterminate' => false,
        ]);
    }
}
