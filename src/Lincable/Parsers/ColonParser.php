<?php

namespace Lincable\Parsers;

use Illuminate\Contracts\Container\Container;
use Lincable\Contracts\Parsers\ParameterInterface;

class ColonParser extends Parser
{
    /**
     * Create a new class instance.
     *
     * @parama Illuminate\Contracts\Container\Container $app
     * @return void
     */
    public function __construct(Container $app)
    {
        $this->boot($app);
    }

    /**
     * {@inheritdoc}
     */
    protected function parseMatches(array $matches): ParameterInterface
    {
        return new Options(head($matches));
    }

    /**
     * {@inheritdoc}
     */
    protected function getDynamicPattern(): string
    {
        return '/^\:([a-zA-Z_-]+)$/';
    }
}
