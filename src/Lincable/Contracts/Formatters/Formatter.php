<?php

namespace Lincable\Contracts\Formatters;

interface Formatter 
{
    /**
     * Return a formatted string option.
     * 
     * @param  mixed|null $value
     * @return string
     */
    public function format($value = null);
}