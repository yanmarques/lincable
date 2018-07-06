<?php

namespace Tests\Lincable\Http;

use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Illuminate\Validation\Factory;
use Illuminate\Container\Container;
use Illuminate\Translation\Translator;
use Illuminate\Translation\ArrayLoader;

class FileRequest extends TestCase
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
     * Should boot the file request with the request with an file.
     *
     * @return void
     */
    public function testThatBootSetTheRequestAndGetFileFromRequest()
    {
        $request = $this->createRequest('foo', $this->getPngFile());
        $fooFile = $this->createFileRequest('png');
        dd($fooFile->getSuffix());
        $fooFile->boot($request);
        
        // Assert the request on foo file.
        $this->assertEquals(
            $request,
            $fooFile->getRequest()
        );
    }

    /**
     * Return an instance of the file request to only accept
     * the given extension.
     *
     * @param  string $extension
     * @return \Tests\Lincable\Http\FooFileRequest
     */
    public function createFileRequest(string $extension)
    {
        FooFileRequest::setExtension($extension);
        return new FooFileRequest;
    }

    /**
     * Return the png file.
     *
     * @return string
     */
    public function getPngFile()
    {
        return __DIR__.'/resources/1.png';
    }

    /**
     * Return the jpg file.
     *
     * @return string
     */
    public function getJpgFile()
    {
        return __DIR__.'/resources/2.jpg';
    }

    /**
     * Create an HTTP request instance with a file
     *
     * @param  string $file
     * @return \Illuminate\Http\Request
     */
    public function createRequest(string $name, string $file)
    {
        $request =  Request::capture();
        $request->files->set($name, [
            'error' => null,
            'name' => $file,
            'size' => null,
            'tmp_name' => $file,
            'type' => pathinfo($file, PATHINFO_EXTENSION)
        ]);
        return $request;
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
