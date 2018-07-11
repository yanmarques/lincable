<?php

namespace Lincable\Formatters;

use Carbon\Carbon;
use Lincable\Contracts\Formatters\Formatter;

/**
 * This class formats the current day.
 */
class DayFormatter implements Formatter
{
    /**
     * {@inheritdoc}
     */
    public function format($value = null)
    {
        return Carbon::now()->day;
    }
}
