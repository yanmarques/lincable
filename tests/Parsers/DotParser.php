<?php

namespace Tests\Lincable\Parsers;

use Lincable\Parsers\Parser;
use Lincable\Parsers\Options;
use Illuminate\Contracts\Container\Container;
use Lincable\Contracts\Parsers\ParameterInterface;

class DotParser extends Parser
{
    /**
     * Create a new class instance.
     *
     * @param  \Illuminate\Contracts\Container\Container  $app
     * @return void
     */
    public function __construct(Container $app)
    {
        $this->boot($app);
    }

    /**
     *{@inheritDoc}
     */
    protected function parseMatches(array $matches): ParameterInterface
    {
        return new Options(last($matches));
    }

    /**
     *{@inheritDoc}
     */
    protected function getDynamicPattern(): string
    {
        return '/^([a-zA-Z_]+)\.([a-zA-Z_]+)$/';
    }
}