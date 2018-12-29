<?php

namespace Tests\Lincable;

use Storage;
use Illuminate\Http\File;
use Illuminate\Http\Request;
use Lincable\Http\FileRequest;
use Illuminate\Config\Repository;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Factory;
use Illuminate\Container\Container;
use Illuminate\Filesystem\FilesystemManager;
use PHPUnit\Framework\TestCase as UnitTestCase;
use Orchestra\Testbench\TestCase as OrchestraTestCase;
use Tests\Lincable\Http\FileRequests\GenericFileRequest;

class TestCase extends OrchestraTestCase
{
    protected $temps = [];

    /**
    * Set the test configuration.
    *
    * @return void
    */
    public function setUp()
    {
        parent::setUp();

        $this->loadMigrationsFrom(__DIR__.'/database/migrations');

        Storage::fake('local');

        $this->setDisk('local');
    }

    /**
     * 
     * 
     * @return void
     */
    public function tearDown()
    {
        foreach ($this->temps as $file) {
            unlink($file);
        }
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
        $request =  $this->app['request'];
        $request->files->set(
            $file, 
            UploadedFile::fake()->create($originalName)
        );
        return $request;
    }

    /**
     * Create a image file request with the extension rule.
     *
     * @param  string $extension
     * @param  bool $boot
     * @return \Tests\Lincable\FileFileRequest
     */
    public function createFileRequest(string $extension, bool $boot = null)
    {
        GenericFileRequest::$extension = $extension;
        $file = new GenericFileRequest;

        if ($boot || is_null($boot)) {
            $pathName = $this->getRandom($extension);
            $request = $this->createRequest('generic', $pathName);
            $file->boot($request);
        }

        return $file;
    }

    /**
     * Define environment setup.
     *
     * @param  \Illuminate\Foundation\Application  $app
     * @return void
     */
    protected function getEnvironmentSetUp($app)
    {
        $configuration = require __DIR__.'/../config/lincable.php';

        $app['config']->set('lincable', $configuration);
    }

    /**
     * Set the new disk to test.
     *
     * @param  string $disk
     * @return void
     */
    protected function setDisk(string $disk)
    {
        $this->app['config']->set('lincable.disk', $disk);
    }

    /**
     * Create a temporary file with the data.
     *
     * @param  mixed  $data
     * @param  string  $extension
     * @return \Illuminate\Http\File
     */
    protected function createFile($data, string $extension = 'txt') 
    {
        return new File(tap(
            $this->registerTempFile($this->getRandom($extension)), 
            function ($file) use ($data) {
                file_put_contents($file, $data);
            }
        ));
    }

    /**
     * Register and create a temporary file.
     *
     * @param  string  $file
     * @return string
     */
    protected function registerTempFile(string $file) 
    {
        $file = str_start($file, '/tmp/');
        touch($file);
        $this->temps[] = $file;
        return $file;
    }

    protected function getPackageProviders($app)
    {
        return [\Lincable\Providers\MediaManagerServiceProvider::class];
    }
}
