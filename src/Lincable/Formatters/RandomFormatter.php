<?php

namespace Lincable\Formatters;

use Lincable\Contracts\Formatters\Formatter;

/**
 * This class formats a random string. 
 */
class RandomFormatter implements Formatter
{
    /**
     * The default random length.
     * 
     * @var int
     */
    private $length;

    /**
     * Create a new class instance.
     * 
     * @param  int $length
     * @return void
     */
    public function __construct(int $length = 32)
    {
        $this->length = $length;
    }

    /**
     * @inheritdoc
     */
    public function format($value = null)
    {
        return str_random($value ?: $this->length);
    }
}