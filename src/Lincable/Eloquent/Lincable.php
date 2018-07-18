<?php

namespace Lincable\Eloquent;

use RuntimeException;
use Illuminate\Http\File;
use Lincable\MediaManager;
use Lincable\UrlGenerator;
use Illuminate\Container\Container;
use Lincable\Http\File\FileResolver;
use Illuminate\Filesystem\Filesystem;
use League\Flysystem\AwsS3v3\AwsS3Adapter;
use League\Flysystem\Cached\CachedAdapter;
use League\Flysystem\FileNotFoundException;
use Lincable\Eloquent\Events\UploadSuccess;
use Lincable\Eloquent\Events\UploadFailure;
use Illuminate\Filesystem\FilesystemAdapter;
use Lincable\Exceptions\LinkNotFoundException;
use League\Flysystem\Rackspace\RackspaceAdapter;
use League\Flysystem\Adapter\Local as LocalAdapter;
use Lincable\Exceptions\ConflictFileUploadHttpException;

trait Lincable
{
    /**
     * The filesystem adapter.
     *
     * @var \Illuminate\Filesystem\FilesystemAdapter
     */
    protected static $driver;

    /**
     * Boot the trait with model.
     *
     * @return void
     */
    public static function bootLincable()
    {
        // Set driver from media manager configuration.
        static::$driver = Container::getInstance()->make(MediaManager::class)->getDisk();
    }

    /**
     * Add the lincable fields to model fillables.
     *
     * @return void
     */
    public function addLincableFields()
    {
        // Get the model fillable fields.
        $fillables = $this->getFillable();

        $this->fillable(array_merge($fillables, (array) $this->getUrlField()));
    }

    /**
     * Return the raw url saved on database.
     *
     * @throws \Lincable\Exceptions\LinkNotFoundException
     *
     * @return string
     */
    public function getUrl()
    {
        if (isset($this->attributes[$this->getUrlField()])) {
            return $this->attributes[$this->getUrlField()];
        }

        // The preview image could not be found for model.
        throw new LinkNotFoundException("Model [{static::class}] does not a file linked with.");
    }

    /**
     * Link the model to a file.
     *
     * @param  mixed $file
     * @return this
     */
    public function link($file)
    {
        // Resolve the file object to link the model. It can
        // be a symfony uploaded file or a file request, which
        // is preferable for linking.
        $file = FileResolver::resolve($file);

        // Get the current media manager of application.
        $mediaManager = Container::getInstance()->make(MediaManager::class);
        
        // Handle the file upload to the disk storage. All errors on upload are covered
        // for better handling upload events. One the upload has been executed with
        // success, the model is auto updated, setting the url_field model configuration
        // with the path to the new file. On error, an event of failure is dispatched and
        // a HTTPException is also reported, if not covered will return a 409 HTTP status.
        $this->handleUpload($mediaManager->getDisk(), $mediaManager->buildUrlGenerator(), $file);

        return $this;
    }

    /**
     * Execute a container callable with the file as argument.
     *
     * @param  mixed $callback
     * @return this
     */
    public function withMedia($callback)
    {
        $file = $this->createTemporaryFile();
        
        // Execute the callable with the temporary file. You also can receive the
        // model instance as second argument.
        Container::getInstance()->call($callback, [$file, $this]);

        // Delete the temporary file wheter it exists.
        (new Filesystem)->delete($file->path());

        return $this;
    }

    /**
     * Return the url field to link the model.
     *
     * @return string
     */
    public function getUrlField()
    {
        return config('lincable.models.url_field');
    }

    /**
     * Throw a HTTP exception indicating that file could not be uploaded.
     *
     * @throws \Lincable\Exceptions\ConflictFileUploadHttpException
     * @return void
     */
    protected function throwUploadFailureException()
    {
        throw new ConflictFileUploadHttpException('Could not store the file on disk.');
    }

    /**
     * Handle the file upload for the model.
     *
     * @param  \Illuminate\Filesystem\FilesystemAdapter $storage
     * @param  \Lincable\UrlGenerator $generator
     * @param  \Illuminate\Http\File $file
     * @return void
     */
    protected function handleUpload(FilesystemAdapter $storage, UrlGenerator $generator, File $media)
    {
        // Get the original fillable array from model.
        $originalFillables = $this->getFillable();
            
        // Set the model instance to seed the generator and generate
        // the url injecting the model attributes.
        $url = $generator->forModel($this)->generate();

        $this->addLincableFields();

        rescue(function () use ($storage, $url, $media) {
            // Put the file on storage and get the full url to location.
            $fileUrl = $storage->putFile($url, $media);
            
            // Update the model with the url of the uploaded file.
            $this->fill([$this->getUrlField() => $storage->url($fileUrl)]);

            // Send the event that the upload has been executed with success.
            event(new UploadSuccess($this, $media));
            
            $this->save();
        }, function () use ($media) {

            // Send the event that the upload has failed.
            event(new UploadFailure($this, $media));

            $this->throwUploadFailureException();
        });

        // Re-set the original fillables.
        $this->fillable($originalFillables);
    }

    /**
     * Return the link from model.
     *
     * @throws \Lincable\Exceptions\LinkNotFoundException
     *
     * @return string
     */
    protected function retrieveLink()
    {
        if ($link = $this->{$this->getUrlField()}) {

            // Return the link file from model.
            return $link;
        }

        // The preview image could not be found for model.
        throw new LinkNotFoundException("Model [{static::class}] does not a file linked with.");
    }

    /**
     * Return the url from the link storage.
     *
     * @return string
     */
    public function getUrl()
    {
        return str_after($this->retrieveLink(), $this->urlFromStorage($this->getDriver()));
    }

    /**
     * Return the base url from the disk storage.
     *
     * @param  \Illuminate\Filesystem\FilesystemAdapter $storage
     * @return string
     */
    protected function urlFromStorage(FilesystemAdapter $storage)
    {
        $adapter = $storage->getAdapter();

        if ($adapter instanceof CachedAdapter) {
            $adapter = $adapter->getAdapter();
        }

        // If an explicit url has been set on the disk configuration then we will use
        // it as the url instead of the default path.
        $url = $storage->getConfig()->get('url');

        if ($adapter instanceof AwsS3Adapter) {
            if ($url) {
                return $url;
            }

            // This is a workaround to find the full url from the storage. We just use the same
            // method to get url from a object, but we provide an empty string and then decode
            // the url returned.
            return rawurldecode($adapter->getClient()->getObjectUrl($adapter->getBucket(), ' '));
        }
    
        if ($adapter instanceof LocalAdapter) {
            if ($url) {
                return $url;
            }

            // Return the default path configuration from filesystem adapter.
            return '/storage/';
        }
            
        throw new RuntimeException('This driver does not support retrieving URLs.');
    }

    /**
     * Return the filesystem driver.
     *
     * @return \Illuminate\Filesystem\FilesystemAdapter
     */
    public function getDriver()
    {
        return static::$driver;
    }

    /**
     * Create a temporary file from the model link.
     *
     * @return \Illuminate\Http\File
     */
    protected function createTemporaryFile()
    {
        $storage = $this->getDriver();

        // Return the path url from storage driver.
        $url = $this->getUrl();

        if ($storage->has($url)) {

            // Generate a temp file from url.
            $filename = sprintf('%s%s.%s', '/tmp/', str_random(), pathinfo($url, PATHINFO_EXTENSION));
            file_put_contents($filename, $storage->get($url));

            // Create the illuminate file from temp filename.
            return new File($filename);
        }

        throw new FileNotFoundException($url);
    }
}
