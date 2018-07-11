<?php

namespace Lincable\Parsers;

use Lincable\Contracts\Parsers\ParameterInterface;

class Options implements ParameterInterface
{
    /**
     * The options value name.
     *
     * @var string
     */
    protected $value;

    /**
     * The options parameters.
     *
     * @var array
     */
    protected $params;

    /**
     * {@inheritdoc}
     */
    public function __construct(string $value, $params = null)
    {
        $this->value = $value;
        $this->params = array_wrap($params);
    }

    /**
     * {@inheritdoc}
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * {@inheritdoc}
     */
    public function getParams(): array
    {
        return $this->params;
    }
}
