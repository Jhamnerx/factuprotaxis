<?php

declare(strict_types=1);

namespace App\Services;

use Carbon\Carbon;

final class Period
{
    /** @var Carbon|string */
    private $start;

    /** @var Carbon|string */
    private $end;

    private string $interval;

    private int $period;

    private $is_indeterminate = false;

    /**
     * Create a new Period instance.
     *
     *
     * @return void
     */
    public function __construct(string $interval = 'month', int $count = 1, ?Carbon $start = null)
    {
        $this->interval = $interval;

        if (empty($start)) {
            $this->start = Carbon::now();
        } elseif (! $start instanceof Carbon) {
            $this->start = new Carbon($start);
        } else {
            $this->start = $start;
        }

        $this->period = $count;
        $start = clone $this->start;
        $method = 'add' . ucfirst($this->interval) . 's';

        $this->end = $start->{$method}($this->period);
    }

    public function getStartDate(): Carbon
    {
        return $this->start;
    }

    public function getEndDate(): Carbon
    {
        return $this->end;
    }

    public function getInterval(): string
    {
        return $this->interval;
    }

    public function getIntervalCount(): int
    {
        return $this->period;
    }
}
