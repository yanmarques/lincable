<?php

namespace Tests\Lincable\Http\File;

use Illuminate\Http\File;
use Tests\Lincable\TestCase;
use Lincable\Http\FileRequest;
use Illuminate\Http\UploadedFile;
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
        $tempFile = '/tmp/'.$this->getRandom('txt'); 
        touch($tempFile);
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
        $result = FileResolver::resolve($pathname);
        $this->assertInstanceOf(File::class, $result);
        $this->assertEquals($expected, file_get_contents($result->path()));
        unlink($pathname);
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
    }

    /**
     * Should return the illuminate file from the symfony file.
     * 
     * @return void
     */
    public function testThatResolveWithIlluminateFile()
    {
        $tempFile = '/tmp/'.$this->getRandom('txt'); 
        touch($tempFile);
        $file = new SymfonyFile($tempFile);
        $result = FileResolver::resolve($file);
        $this->assertInstanceOf(File::class, $result);
        $this->assertEquals($file->getPathname(), $result->path());
        unlink($tempFile);
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
}