<?php

namespace Lincable\Parsers;

class ColonParser extends Parser
{
    /**
     * @inheritdoc
     */
    protected function parseMatches(array $matches): Options
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