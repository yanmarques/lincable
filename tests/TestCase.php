<?php

namespace Tests\Lincable;

use Illuminate\Http\Request;
use Lincable\Http\FileRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Factory;
use Illuminate\Container\Container;
use Illuminate\Translation\Translator;
use Illuminate\Translation\ArrayLoader;
use PHPUnit\Framework\TestCase as UnitTestCase;

class TestCase extends UnitTestCase
{
     /**
     * Set the test configuration.
     *
     * @return void
     */
    public function setUp()
    {
        $this->registerRequestValidateMacro();
    }

    /**
     * Return a random filename with and extension.
     * 
     * @param  string $extension
     * @return string
     */
    public function getRandom(string $extension)
    {
        return sprintf('%s.%s', str_replace('\/', '', str_random()), $extension);
    }

    /**
     * Create a HTTP request instance with a file.
     *
     * @param  string $file
     * @param  string $originalName
     * @return \Illuminate\Http\Request
     */
    public function createRequest(string $file, string $originalName)
    {
        $request =  Request::capture();
        $request->files->set($file, UploadedFile::fake()->create($originalName));
        return $request;
    }

    /**
     * Create a image file request with the extension rule.
     * 
     * @param  string $extension
     * @param  bool $boot
     * @return \Tests\Lincable\FileFileRequest
     */
    public function createFileRequest(string $extension, bool $boot = true)
    {
        FileFileRequest::$extension = $extension;
        $file = new FileFileRequest;

        if ($boot) {
            $pathName = $this->getRandom($extension);
            $request = $this->createRequest('file', $pathName);
            $file->boot($request);
        }

        return $file;
    }

    /**
     * Register the macro functions on request for validation.
     *
     * @return void
     */
    protected function registerRequestValidateMacro()
    {
        Request::macro('makeValidator', function () {
            $loader = new ArrayLoader;
            $translator = new Translator($loader, 'eng-us');
            $app = new Container;
            return new Factory($translator, $app);
        });

        Request::macro('validate', function (array $rules) {
            return $this->makeValidator()->validate($this->all(), $rules);
        });
    }
}

class FileFileRequest extends FileRequest
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