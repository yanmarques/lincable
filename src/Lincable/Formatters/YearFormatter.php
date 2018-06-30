<?php

namespace Lincable\Formatters;

use Carbon\Carbon;
use Lincable\Contracts\Formatters\Formatter;

/**
 * This class formats the current year. 
 */
class YearFormatter
{
    /**
     * @inheritdoc
     */
    public function format($value = null)
    {
        return Carbon::now()->year;
    }
}