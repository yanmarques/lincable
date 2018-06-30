<?php

namespace Lincable\Formatters;

use Lincable\Contracts\Formatters\Formatter;

/**
 * This class formats a random string. 
 */
class RandomFormatter
{
    /**
     * @inheritdoc
     */
    public function format($value = null)
    {
        return str_random($value);
    }
}