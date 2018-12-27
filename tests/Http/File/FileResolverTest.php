<?php

namespace Tests\Lincable\Http\File;

use Illuminate\Http\File;
use Illuminate\Http\Request;
use Tests\Lincable\TestCase;
use Lincable\Http\FileRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Container\Container;
use Lincable\Http\File\FileResolver;
use Lincable\Exceptions\NotResolvableFileException;
use Symfony\Component\HttpFoundation\File\File as SymfonyFile;
use Symfony\Component\HttpFoundation\File\Exception\FileNotFoundException;

class FileResolverTest extends TestCase
{
    /**
     * Should resolve a string file.
     *
     * @return void
     */
    public function testResolveWithValidPathname()
    {
        $tempFile = $this->registerTempFile($this->getRandom('txt'));
        $result = FileResolver::resolve($tempFile);
        $this->assertInstanceOf(File::class, $result);
        $this->assertEquals($tempFile, $result->path());
    }

    /**
     * Should throw a file not found exception.
     *
     * @return void
     */
    public function testResolveWithInvalidPathname()
    {
        $this->expectException(FileNotFoundException::class);
        FileResolver::resolve(str_random());
    }

    /**
     * Should resolve a valid file request instance.
     *
     * @return void
     */
    public function testResolveWithValidFileRequest()
    {
        $fileRequest = $this->createFileRequest('txt');
        $pathname = $fileRequest->getFile()->getPathname();
        $expected = str_random();
        file_put_contents($pathname, $expected);
        $result = FileResolver::resolve($fileRequest);
        $this->assertInstanceOf(File::class, $result);
        $this->assertEquals($expected, file_get_contents($result->path()));
        unlink($result->path());
    }

    /**
     * Should move the uploaded file to a temporary location and return a illuminate file.
     *
     * @return void
     */
    public function testThatResolveWithUploadedFile()
    {
        $expected = 'txt';
        $uploadedFile = UploadedFile::fake()->create($this->getRandom($expected));
        $result = FileResolver::resolve($uploadedFile);
        $this->assertInstanceOf(File::class, $result);
        $this->assertEquals(pathinfo($result, PATHINFO_EXTENSION), $expected);
        unlink($result->path());
    }

    /**
     * Should return the illuminate file from the symfony file.
     *
     * @return void
     */
    public function testThatResolveWithIlluminateFile()
    {
        $tempFile = $this->registerTempFile($this->getRandom('txt'));
        $file = new SymfonyFile($tempFile);
        $result = FileResolver::resolve($file);
        $this->assertInstanceOf(File::class, $result);
        $this->assertEquals($file->getPathname(), $result->path());
    }

    /**
     * Should throw an exception because object can not be resolved to a file.
     *
     * @return void
     */
    public function testThatResolveWithInvalidFile()
    {
        $invalidObject = new \stdClass;
        $this->expectException(NotResolvableFileException::class);
        FileResolver::resolve($invalidObject);
    }

    /**
     * Should boot a file request not booted.
     *
     * @return void
     */
    public function testThatResolveWithNotBootedFileRequest()
    {
        $request = $this->createRequest('generic', $this->getRandom('txt'));
        Container::getInstance()->instance(Request::class, $request);
        $fileRequest = $this->createFileRequest('txt', false);
        $result = FileResolver::resolve($fileRequest);
        $this->assertInstanceOf(File::class, $result);
        $this->assertTrue($fileRequest->isBooted());
        unlink($result->path());
    }
}
