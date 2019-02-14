<?php

namespace Tests\Lincable\Http;

use Illuminate\Http\Request;
use Tests\Lincable\TestCase;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Validation\ValidationException;
use Tests\Lincable\Http\FileRequests\FooFileRequest;
use Lincable\Http\File\FileResolver;
use Tests\Lincable\Http\FileRequests\FooFormRequest;

class FileRequestTest extends TestCase
{   
    /**
     * Should return the file sent on request.
     *
     * @return void
     */
    public function testGetParameterResolvesClassname()
    {
        $this->assertEquals(
            'generic',
            $this->createFileRequest('txt')->getParameter()
        );
    }

    /**
     * Should return true method on isBooted after has booted.
     * 
     * @return void
     */
    public function testGetParameterChangesTheParameterWhenSet()
    {
        $this->assertEquals(
            'bar', 
            $this->createFileRequest('txt')->as('bar')->getParameter()
        );
    }

    /**
     * Should move the file to another location before
     * sending it, when prepareFile is called.
     * 
     * @return void
     */
    public function testThatBeforeSendChangesTheFile()
    {
        $name = $this->getRandom('txt');
        
        $request = $this->createFooFileRequest('txt', '/tmp', $name);
        
        $file = $request->prepareFile();

        $this->assertEquals('/tmp/'.$name, $file->getPathName());
    }

    /**
     * Set a new file on currest request.
     *
     * @param  string  $key
     * @param  string  $path
     * @param  string|null  $name
     * @return void
     */
    protected function createFooFileRequest(string $extension, string $path, string $name = null)
    {
        $this->createRequest('foo', $this->getRandom($extension));

        return tap($this->app->make(FooFileRequest::class), function ($request) use ($path, $name) {
            $request->setDestination($path, $name);
        });
    }
}
