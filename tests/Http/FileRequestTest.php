<?php

namespace Tests\Lincable\Http;

use Illuminate\Http\Request;
use Tests\Lincable\TestCase;
use Lincable\Http\FileRequest;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Validation\ValidationException;

class FileRequestTest extends TestCase
{
    /**
     * Should boot the file request with the request with an file.
     *
     * @return void
     */
    public function testThatBootSetTheRequest()
    {
        $fileRequest = $this->createFileRequest('png');

        // Assert the request on foo file.
        $this->assertInstanceOf(
            Request::class,
            $fileRequest->getRequest()
        );
    }

    /**
     * Should return the file sent on request.
     *
     * @return void
     */
    public function testThatBootSetThePngFile()
    {
        $png = $this->getRandom('png');
        $request = $this->createRequest('file', $png);
        $file = $this->createFileRequest('png', false);
        $file->boot($request);
        
        // Assert the request on foo file.
        $this->assertEquals(
            $png,
            $file->getFile()->name
        );
    }

    /**
     * Should return true method on isBooted after has booted.
     * 
     * @return void
     */
    public function testThatIsBootedReturnTrueAfterBoot()
    {
        $fileRequest = $this->createFileRequest('txt');
        $this->assertTrue($fileRequest->isBooted());
    }

    /**
     * Should return false method on isBooted.
     * 
     * @return void
     */
    public function testThatIsBootedReturnFalseAfterBoot()
    {
        $fileRequest = $this->createFileRequest('txt', false);
        $this->assertFalse($fileRequest->isBooted());
    }

    /**
     * Should throw a validation exception for invalid file mimetype.
     * 
     * @return void
     */
    public function testThatBootThrowsAValidationException()
    {
        $invalidFile = $this->getRandom('xyz');
        $request = $this->createRequest('file', $invalidFile);
        $fileRequest = $this->createFileRequest('png', false);
        $this->expectException(ValidationException::class);
        $fileRequest->boot($request);
    }

    /**
     * Should move the file to another location before
     * sending it, when prepareFile is called.
     * 
     * @return void
     */
    public function testThatBeforeSendChangesTheFile()
    {
        $text = $this->getRandom('txt');
        $request = $this->createRequest('foo', $text);
        $destination = str_finish('/tmp/'.str_random(), '/');
        $foo = new FooFileRequest($destination);
        $foo->boot($request);
        $file = $foo->prepareFile(new Container);
        $expected = $destination.$file->getFilename();
        $this->assertEquals($expected, $file->getPathName());
        (new Filesystem)->deleteDirectory($destination);
    }

}

class FooFileRequest extends FileRequest
{
    /**
     * File to move the file before send.
     * 
     * @var string
     */
    private $destination;

    /**
     * 
     * 
     * @param  string $file
     * @return void
     */
    public function __construct(string $destination)
    {
        $this->destination = $destination;
    }

    /**
     * Rules to validate the file on request.
     *
     * @return mixed
     */
    protected function rules()
    {
        return 'mimes:txt';
    }

    /**
     * Executed before sending the file.
     * 
     * @return mixed
     */
    public function beforeSend($file)
    {
        return $file->move($this->destination);
    }
}