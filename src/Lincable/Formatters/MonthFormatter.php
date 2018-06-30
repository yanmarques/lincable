<?php

namespace Lincable\Formatters;

use Carbon\Carbon;
use Lincable\Contracts\Formatters\Formatter;

/**
 * This class formats the current month. 
 */
class MonthFormatter
{
    /**
     * @inheritdoc
     */
    public function format($value = null)
    {
        return Carbon::month;
    }
}