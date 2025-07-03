<?php

declare(strict_types=1);

namespace App\Services;

use Carbon\Carbon;

final class Period
{
    private $start;

    private $end;

    private $interval;

    private $period;

    private $is_indeterminate = false;

    /**
     * Create a new Period instance.
     *
     *
     * @return void
     */
    public function __construct($interval = 'month', $count = 1, $start = null)
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
