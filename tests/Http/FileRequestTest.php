<?php

namespace Tests\Lincable\Http;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use PHPUnit\Framework\TestCase;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Factory;
use Illuminate\Container\Container;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\Translator;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\File\File;
use Lincable\Http\FileRequest as BaseFileRequest;

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
    public function testThatBootSetTheRequest()
    {
        $request = $this->createRequest('image', $this->getRandom('png'));
        $image = new ImageFileRequest;
        $image->boot($request);

        // Assert the request on foo file.
        $this->assertEquals(
            $request,
            $image->getRequest()
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
        $request = $this->createRequest('image', $png);
        $image = new ImageFileRequest;
        $image->boot($request);
        
        // Assert the request on foo file.
        $this->assertEquals(
            $png,
            $image->getFile()->name
        );
    }

    /**
     * Should return true method on isBooted after has booted.
     * 
     * @return void
     */
    public function testThatIsBootedReturnTrueAfterBoot()
    {
        $png = $this->getRandom('png');
        $request = $this->createRequest('image', $png);
        $image = new ImageFileRequest;
        $image->boot($request);
        $this->assertTrue($image->isBooted());
    }

    /**
     * Should return false method on isBooted.
     * 
     * @return void
     */
    public function testThatIsBootedReturnFalseAfterBoot()
    {
        $png = $this->getRandom('png');
        $request = $this->createRequest('image', $png);
        $image = new ImageFileRequest;
        $this->assertFalse($image->isBooted());
    }

    /**
     * Should throw a validation exception for invalid file mimetype.
     * 
     * @return void
     */
    public function testThatBootThrowsAValidationException()
    {
        $invalidFile = $this->getRandom('xyz');
        $request = $this->createRequest('image', $invalidFile);
        $image = new ImageFileRequest;
        $this->expectException(ValidationException::class);
        $image->boot($request);
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
        $request = $this->createRequest('foo', $text, 50);
        $destination = str_finish('/tmp/'.str_random(), '/');
        $foo = new FooFileRequest($destination);
        $foo->boot($request);
        $file = $foo->prepareFile(new Container);
        $expected = $destination.$file->getFilename();
        $this->assertEquals($expected, $file->getPathName());
        (new Filesystem)->deleteDirectory($destination);
    }

    /**
     * Return a random filename with and extension.
     * 
     * @param  string $extension
     * @return string
     */
    public function getRandom(string $extension)
    {
        return sprintf('%s.%s', Str::random(), $extension);
    }

    /**
     * Create an HTTP request instance with a file
     *
     * @param  string $file
     * @param  string $originalName
     * @param  int    $kiloBytes
     * @return \Illuminate\Http\Request
     */
    public function createRequest(string $file, string $originalName, int $kiloBytes = 0)
    {
        $request =  Request::capture();
        $request->files->set($file, UploadedFile::fake()->create($originalName, $kiloBytes));
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

class ImageFileRequest extends BaseFileRequest
{
    /**
     * Rules to validate the file on request.
     *
     * @return mixed
     */
    protected function rules()
    {
        return 'mimes:png,jpg';
    }
}

class FooFileRequest extends BaseFileRequest
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
