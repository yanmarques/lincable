<?php

namespace Lincable;

use Exception;
use Illuminate\Http\File;
use Lincable\Eloquent\Lincable;
use Lincable\Http\File\FileFactory;
use Illuminate\Database\Eloquent\Model;
use League\Flysystem\FileNotFoundException;
use Lincable\Eloquent\Events\UploadFailure;
use Lincable\Eloquent\Events\UploadSuccess;
use Illuminate\Support\Traits\ForwardsCalls;
use Illuminate\Contracts\Container\Container;
use Lincable\Exceptions\ConflictFileUploadHttpException;

class MediaManager
{
    use ForwardsCalls;

    /**
     * The container implementation.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;

    /**
     * The url generator instance.
     *
     * @var \Lincable\UrlGenerator
     */
    protected $urlGenerator;

    /**
     * The disk for file storage.
     *
     * @var \Illuminate\Contracts\Filesystem\Clooud
     */
    protected $disk;

    /**
     * Create a new class instance.
     *
     * @param  \Illuminate\Contracts\Container\Container  $app
     * @param  \Lincable\UrlGenerator  $urlGenerator
     * @return void
     */
    public function __construct(Container $app, UrlGenerator $urlGenerator)
    {
        $this->app = $app;
        $this->urlGenerator = $urlGenerator;
        $this->disk = $app['filesystem']->disk($this->config('disk'));
    }

    /**
     * Return the disk storage.
     *
     * @return \Illuminate\Contracts\Filesystem\Clooud
     */
    public function getDisk()
    {
        return $this->disk;
    }

    /**
     * Return a url generator instance with the manager configuration.
     *
     * @return \Lincable\UrlGenerator
     */
    public function getUrlGenerator()
    {
        return $this->urlGenerator;
    }

    /**
     * Set the new url generator instance.
     *
     * @param  \Lincable\UrlGenerator  $urlGenerator
     * @return this
     */
    public function setUrlGenerator(UrlGenerator $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
        return $this;
    }

    /**
     * Return the container instance.
     * 
     * @return \Illuminate\Contracts\Container\Container
     */
    public function getContainer()
    {
        return $this->app;
    }

    /**
     * Return the full url from disk for the model.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return string|null
     */
    public function url(Model $model)
    {
        $this->supportLincable($model);

        if ($model->getRawUrl()) {
            return $this->disk->url($model->getRawUrl());
        }   
    }

    /**
     * Determine wheter the model has a valid file
     * on disk storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return bool
     */
    public function has(Model $model)
    {
        $this->supportLincable($model);

        $url = $model->getRawUrl();

        return $url !== null && $this->disk->exists($url);
    }

    /**
     * Execute the upload operation, reporting correct events and exceptions.
     *
     * @param  \Illuminate\Http\File  $media
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  mixed|null  $callback
     * @param  mixed  $options
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function upload(
        File $media, 
        Model $model,
        $options = []
    ) {
        $this->supportLincable($model);

        // Create the model link.        
        $link = $this->newLink($model);

        return rescue(function () use ($link, $media, $model, $options) {
            // Define the arguments list and method to the media upload.
            list($arguments, $putMethod) = [[$link, $media], 'putFile'];

            // Determine if the model should overwrite the same filename.
            // Then the media filename is inserted into the arguments 
            // and we change the upload method to accept a filename. 
            if ($model->shouldOverwrite()) {
                $arguments[] = $model->getFileName();
                $putMethod = 'putFileAs';
            } 

            // Here we allow custom upload options that is independent from 
            // the method used.
            $arguments[] = $options;
            
            $url = $this->disk->$putMethod(...$arguments);

            $model->fillUrl($url);

            // Send the event that the upload has been executed with success.
            event(new UploadSuccess($model, $media));
            
            return $model;
        }, function () use ($model, $media) {
            // Send the event that the upload has failed.
            event(new UploadFailure($model, $media));

            $this->throwUploadFailureException();
        });
    }

     /**
      * Copy the file from the source model to the destiny model.
      *
      * @param  \Illuminate\Database\Eloquent\Model  $from
      * @param  \Illuminate\Database\Eloquent\Model  $to
      * @param  bool|null  $preserveName
      * @return \Illuminate\Database\Eloquent\Model
      */
    public function copy(Model $from, Model $to, bool $preserveName = null)
    {
        $this->supportLincable([$from, $to]);

        $path = $this->newLink(
            $to,
            $preserveName ? 
                $from->getFileName() : 
                str_random(40).'.'.$from->getExtension()
        );
        
        $this->disk->copy($from->getRawUrl(), $path);

        return $to->fillUrl($path);
    }

    /**
     * Move a model media to another location.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  \Illuminate\Database\Eloquent\Model|string  $path
     * @param  bool|null  $dryRun
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function move(Model $model, $path, bool $dryRun = null) 
    {
        if ($path instanceof Model) {
            $this->supportLincable($path);
            $modelUrl = $path->getRawUrl();

            // Do not keep the model on.
            if (! $dryRun) {
                $path->delete();
            }

            $path = $modelUrl;
            unset($modelUrl);
        }

        $this->disk->move($model->getRawUrl(), (string) $path);
    }

    /**
     * Download the file contents and store in a temporary directory.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @return \Illuminate\Http\File
     */
    public function get(Model $model)
    {
        $this->supportLincable($model);

        $url = $model->getRawurl();

        if ($url && $this->disk->has($url)) {
            $resource = $this->disk->readStream($url);

            return FileFactory::createTemporary($resource, $url);
        }

        throw new FileNotFoundException($url);
    }

    /**
     * Create a new a link for model with a file name. 
     * 
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string|null  $fileName
     * @return string
     */
    public function newLink(Model $model, string $fileName = null) 
    {
        $this->supportLincable($model);

        // Set the model instance to seed the generator and generate
        // the url injecting the model attributes.
        $url = str_start(
            $this->urlGenerator->forModel($model)->generate(), 
            '/'
        );

        if ($fileName) {
            return str_finish($url, '/').$fileName;
        }

        return rtrim($url, '/');
    }

    /**
     * Ensure the given model give support to lincable.
     * 
     * @param  array|\Illuminate\Database\Eloquent\Model  $model
     * @return void
     * 
     * @throws \Exception When model does not support Lincable
     */
    public function supportLincable($model)
    {
        if (is_array($model)) {
            foreach ($model as $class) {
                $this->supportLincable($class);
            }

            return;
        }

        $modelClass = get_class($model);
        
        if (! $this->hasLincableTrait($modelClass)) {
            throw new Exception("The model [$modelClass] does not support Lincable.");
        } 
    }

    /**
     * Determinse wheter a model class uses lincable trait.
     *
     * @param  string  $model
     * @return bool
     */
    protected function hasLincableTrait(string $model)
    {
        return in_array(Lincable::class, class_uses_recursive($model));
    }

    /**
     * Return the configuration value.
     *
     * @param  string  $key
     * @param  mixed|null  $default
     * @return void
     */
    protected function config(string $key, $default = null) 
    {
        return config("lincable.$key", $default);
    }

    /**
     * Throw a HTTP exception indicating that file could not be uploaded.
     * 
     * @return void
     * 
     * @throws \Lincable\Exceptions\ConflictFileUploadHttpException
     */
    protected function throwUploadFailureException()
    {
        throw new ConflictFileUploadHttpException('Could not store the file on disk.');
    }

    /**
     * {@inheritDoc}
     */
    public function __call($name, $arguments)
    {
        if (empty($arguments)) {
            static::throwBadMethodCallException($name);
        } 

        if (
            $arguments[0] instanceof Model && 
            $this->hasLincableTrait(get_class($arguments[0]))
        ) {    
            $arguments[0] = $arguments[0]->getRawUrl();
        }

        return $this->forwardCallTo($this->disk, $name, $arguments);
    }
}
