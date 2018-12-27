<?php 

namespace Tests\Lincable\Formatters;

use Lincable\Contracts\Formatters\Formatter;

class ArgumentFormatter implements Formatter
{
    private $param;

    /**
     * Create a new class instance.
     * 
     * @param  \Tests\Lincable\Formatters\Param  $param
     * @return void
     */
    public function __construct(Param $param)
    {
        $this->param = $param;
    }

    public function format($value = null)
    {
        return $this->param->getValue();
    }
}