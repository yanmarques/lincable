<?php

namespace Lincable\Exceptions;

use Symfony\Component\HttpKernel\Exception\HttpException;

class ConflictFileUploadHttpException extends HttpException
{
    /**
     * Create a new exception instance.
     *
     * @param  string $message
     * @return void
     */
    public function __construct(string $message)
    {
        parent::__construct(409, $message);
    }
}
