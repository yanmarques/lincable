<?php

namespace Lincable\Eloquent;

use Illuminate\Http\File;
use Lincable\MediaManager;
use Lincable\UrlGenerator;
use Illuminate\Container\Container;
use Lincable\Http\File\FileResolver;
use Illuminate\Filesystem\Filesystem;
use League\Flysystem\FileNotFoundException;
use Lincable\Eloquent\Events\UploadFailure;
use Lincable\Eloquent\Events\UploadSuccess;
use Illuminate\Filesystem\FilesystemAdapter;
use Lincable\Exceptions\LinkNotFoundException;
use Lincable\Exceptions\ConflictFileUploadHttpException;

trait Lincable
{
    /**
     * The lincable media manager instance.
     *
     * @var \Lincable\MediaManager
     */
    protected static $mediaManager;

    /**
     * Boot the trait with model.
     *
     * @return void
     */
    public static function bootLincable()
    {
        // Set media manager instance from container.
        static::setMediaManager(Container::getInstance()->make(MediaManager::class));
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
        throw new LinkNotFoundException('Model [{static::class}] does not a file linked with.');
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

        // Handle the file upload to the disk storage. All errors on upload are covered
        // for better handling upload events. One the upload has been executed with
        // success, the model is auto updated, setting the url_field model configuration
        // with the path to the new file. On error, an event of failure is dispatched and
        // a HTTPException is also reported, if not covered will return a 409 HTTP status.
        $this->handleUpload($file);

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
        // Get the current media manager of application.
        $mediaManager = static::getMediaManager();

        // Create a temporary file with the model file contents. Provide the
        // the default disk registered on media manager.
        $file = $this->createTemporaryFile($mediaManager->getDisk());

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
     * @param  \Illuminate\Http\File $media
     * @param  \Lincable\MediaManager|null $mediaManager
     * @return void
     */
    protected function handleUpload(File $media, MediaManager $mediaManager = null)
    {
        // Use provided media manager instance or use global from model.
        $mediaManager = $mediaManager ?: static::getMediaManager();

        // Get the original fillable array from model.
        $originalFillables = $this->getFillable();

        // Add the lincable fields to fillable attributes, this way we can insert the url
        // on model with the field previously configured.
        $this->addLincableFields();

        $this->wrapUpload($media, $mediaManager->getDisk(), $mediaManager->buildUrlGenerator());

        // Re-set the original fillables.
        $this->fillable($originalFillables);
    }

    /**
     * Return the base url from the disk storage.
     *
     * @param  \Illuminate\Filesystem\FilesystemAdapter $storage
     * @return string
     */
    protected function urlFromStorage(FilesystemAdapter $storage)
    {
        return $storage->url($this->getUrl());
    }

    /**
     * Execute the upload operation, reporting correct events and exceptions.
     *
     * @param  \Illuminate\Http\File $media
     * @param  \Illuminate\Filesystem\FilesystemAdapter $storage
     * @param  \Lincable\UrlGenerator $generator
     * @return void
     */
    protected function wrapUpload(File $media, FilesystemAdapter $storage, UrlGenerator $generator)
    {
        // Set the model instance to seed the generator and generate
        // the url injecting the model attributes.
        $url = $generator->forModel($this)->generate();

        rescue(function () use ($storage, $url, $media) {
            $urlField = $this->getUrlField();

            // Put the file on storage and get the full url to location.
            if (isset($this->attributes[$urlField])) {
                $url = $storage->putFileAs($url, $media, $this->getFileName());
            } else {
                $url = $storage->putFile($url, $media);
            }

            // Update the model with the url of the uploaded file.
            $this->fill([$urlField => $url]);

            // Send the event that the upload has been executed with success.
            event(new UploadSuccess($this, $media));

            $this->save();
        }, function () use ($media) {

            // Send the event that the upload has failed.
            event(new UploadFailure($this, $media));

            $this->throwUploadFailureException();
        });
    }

    /**
     * If the fire_url attribute already exists return the name of the file.
     *
     * @return string
     */
    protected function getFileName()
    {
        return last(explode('/', $this->attributes[$this->getUrlField()] ?? ''));
    }

    /**
     * Create a temporary file from the model link.
     *
     * @param  \Illuminate\Filesystem\FilesystemAdapter $storage
     * @return \Illuminate\Http\File
     */
    protected function createTemporaryFile(FilesystemAdapter $storage)
    {
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

    /**
     * Return the manager instance.
     *
     * @return \Lincable\MediaManager
     */
    public static function getMediaManager()
    {
        return static::$mediaManager;
    }

    /**
     * Return the manager instance.
     *
     * @return \Lincable\MediaManager
     */
    public static function setMediaManager(MediaManager $mediaManager)
    {
        static::$mediaManager = $mediaManager;
    }

    /**
     * Dynamically retrieve attributes on the model.
     *
     * @param  string  $key
     * @return mixed
     */
    public function __get($key)
    {
        if ($key == $this->getUrlField()) {
            $mediaManager = static::getMediaManager();

            // Create the full path from disk storage.
            return $this->urlFromStorage($mediaManager->getDisk());
        }

        return $this->getAttribute($key);
    }
}
