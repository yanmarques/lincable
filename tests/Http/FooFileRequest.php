<?php

namespace Tests\Lincable\Http;

use Lincable\Http\FileRequest;
use Illuminate\Http\UploadedFile;

class FooFileRequest extends FileRequest
{
    /**
     * The current accepted file extension.
     *
     * @var string
     */
    private static $extension = 'jpg';

    /**
     * The parameter do get the file on request.
     *
     * @var string
     */
    private static $parameter = 'foo';

    /**
     * Rules to validate the file on request.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return mixed
     */
    protected function rules(UploadedFile $file)
    {
        return 'required';
    }

    /**
     * Change the extension accepted for instance.
     *
     * @param  string $extension
     * @return void
     */
    public static function setExtension(string $extension)
    {
        static::$extension = $extension;
    }

    /**
     * Set the parameter on request.
     *
     * @param  string $parameter
     * @return void
     */
    public static function setParameter(string $parameter)
    {
        static::$parameter = $parameter;
    }

    /**
     * Get the file parameter on request.
     *
     * @return string
     */
    protected function getParameter()
    {
        return static::$parameter;
    }
}
