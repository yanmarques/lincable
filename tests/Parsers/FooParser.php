<?php

namespace Tests\Lincable\Parsers;

use Lincable\Parsers\Parser;
use Lincable\Parsers\Options;
use Illuminate\Contracts\Container\Container;
use Lincable\Contracts\Parsers\ParameterInterface;

class FooParser extends Parser
{
    public function __construct(Container $container)
    {
        $this->boot($container);
    }

    /**
     *{@inheritDoc}
     */
    protected function parseMatches(array $matches): ParameterInterface
    {
        return new Options(head($matches));
    }

    /**
     *{@inheritDoc}
     */
    protected function getDynamicPattern(): string
    {
        return '/^foo@([a-zA-Z]+)$/';
    }
}