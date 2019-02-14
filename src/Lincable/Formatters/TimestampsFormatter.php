<?php

namespace Lincable\Formatters;

use Carbon\Carbon;
use Lincable\Contracts\Formatters\Formatter;

/**
 * This class formats a hash of the current timestamps.
 */
class TimestampsFormatter implements Formatter
{
    /**
     * {@inheritdoc}
     */
    public function format($value = null)
    {
        return sha1(Carbon::now()->timestamp);
    }
}
