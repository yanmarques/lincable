<?php

namespace Tests\Lincable\Http\FileRequests;

use Lincable\Http\FileRequest;

class GenericFileRequest extends FileRequest
{
    /**
     * The extension rule.
     *
     * @var string
     */
    public static $extension;

    /**
     * Rules to validate the file on request.
     *
     * @return mixed
     */
    protected function rules()
    {
        return 'mimes:'.static::$extension;
    }
}