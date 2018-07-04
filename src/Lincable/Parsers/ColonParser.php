<?php

namespace Lincable\Parsers;

use Illuminate\Contracts\Container\Container;
use Lincable\Contracts\Parsers\ParameterInterface;

class ColonParser extends Parser
{
    /**
     * @inheritdoc
     */
    protected function parseMatches(array $matches): ParameterInterface
    {
        return new Options(head($matches));
    }

    /**
     * @inheritdoc
     */
    protected function getDynamicPattern(): string
    {
        return '/^:[a-zA-Z_-]+$/';
    }
}