<?php

namespace Tests\Lincable\Formatters;

use Lincable\Contracts\Formatters\Formatter;

class ALongNameFormatter implements Formatter
{
    public function format($value = null)
    {
        return 'A long text returned here';
    }
}