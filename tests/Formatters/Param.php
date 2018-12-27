<?php

namespace Tests\Lincable\Formatters;

class Param
{
    private $value;

    /**
     * Create a new class instance. 
     *
     * @param  mixed $value
     * @return void
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Return the value parameter.
     * 
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;        
    }
}