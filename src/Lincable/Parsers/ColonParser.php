<?php

namespace Lincable\Parsers;

class ColonParser extends Parser
{
    /**
     * @inheritdoc
     */
    protected function parseMatches(array $matches)
    {
        if (count($matches) > 1) {

            // Get the last match on array, which contains the
            // formatter options.
            $formatter = last($matches);
            
            return $this->callFormatter(...explode(':', $formatter));
        }

        return false;
    }

    /**
     * @inheritdoc
     */
    protected function getDynamicPattern()
    {
        return '/^\@([a-zA-Z_]+)(?:\<([a-zA-Z0-9_:]+)\>)?$/';
    }
}