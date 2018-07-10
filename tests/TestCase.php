<?php

namespace Tests\Lincable;

use Illuminate\Http\Request;
use Lincable\Http\FileRequest;
use Illuminate\Config\Repository;
use Illuminate\Http\UploadedFile;
use Illuminate\Validation\Factory;
use Illuminate\Container\Container;
use Illuminate\Translation\Translator;
use Illuminate\Support\Facades\Storage;
use Illuminate\Translation\ArrayLoader;
use Illuminate\Events\EventServiceProvider;
use Illuminate\Filesystem\FilesystemManager;
use PHPUnit\Framework\TestCase as UnitTestCase;
use Illuminate\Contracts\Container\Container as ContainerInterface;

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
        $this->registerEvents();
        $container = Container::getInstance();
        $container->bind('config', function () {
            return $this->getConfiguration();
        });
        $container->bind(ContainerInterface::class, Container::class);
        $container->singleton('filesystem', function ($app) {
            return new FilesystemManager($app);
        });
        Storage::setFacadeApplication($container);
        Storage::fake('s3');
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

    /**
     * Return the repository configuration.
     *
     * @return \Illuminate\Config\Repository
     */
    protected function getConfiguration()
    {
        $configuration = require __DIR__.'/../config/lincable.php';
        return new Repository([
            'lincable' => $configuration,
            'filesystems.disks.s3' => [
                'driver' => 's3',
                'key' => 'fake',
                'secret' => 'fake',
                'region' => 'fake',
                'bucket' => 'fake',
            ],
            'filesystems.disks.local' => [
                'driver' => 'local',
                'root' => '/tmp'
            ],
            'filesystems.default' => 'local'
        ]);
    }

    /**
     * Set a new configuration for application.
     *
     * @param  string $key
     * @param  mixed $value
     * @return void
     */
    protected function setConfig(string $key, $value)
    {
        $config = Container::getInstance()['config']->all();
        data_set($config, $key, $value);
        Container::getInstance()['config'] = new Repository($config);
    }

    /**
     * Set the new disk to test.
     *
     * @param  string $disk
     * @return void
     */
    protected function setDisk(string $disk)
    {
        $this->setConfig('lincable.disk', $disk);
        Storage::setFacadeApplication(Container::getInstance());
        Storage::fake($disk);

        // Rebind the filesystem instance.
        Container::getInstance()['filesystem'] = Storage::getFacadeRoot();
    }

    /**
     * Register the events service provider.
     *
     * @return void
     */
    protected function registerEvents()
    {
        (new EventServiceProvider(Container::getInstance()))->register();
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
