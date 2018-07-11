<?php

namespace Lincable\Exceptions;

class NotResolvableFileException extends \Exception
{
    /**
     * Create a new exception class instance.
     *
     * @return void
     */
    public function __construct($file)
    {
        if (is_object($file)) {
            $file = get_class($file);
        }

        parent::__construct("Could not resolve [{$file}] to a file.");
    }
}
