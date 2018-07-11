<?php

namespace Lincable\Contracts\Parsers;

interface ParameterInterface
{
    /**
     * Create a new parameter instance.
     *
     * @param  string $value
     * @param  mixed|null  $params
     * @return void
     */
    public function __construct(string $value, $params = null);

    /**
     * Get the parameter value.
     *
     * @return string
     */
    public function getValue(): string;

    /**
     * Get all parameters.
     *
     * @return array
     */
    public function getParams(): array;
}
