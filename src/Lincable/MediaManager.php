<?php

namespace Lincable;

use Exception;
use Illuminate\Http\File;
use Lincable\Eloquent\Lincable;
use Illuminate\Database\Eloquent\Model;
use League\Flysystem\FileNotFoundException;
use Lincable\Eloquent\Events\UploadFailure;
use Lincable\Eloquent\Events\UploadSuccess;
use Illuminate\Contracts\Container\Container;
use Lincable\Exceptions\ConflictFileUploadHttpException;

class MediaManager
{
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
     * @return string
     */
    public function url(Model $model)
    {
        $this->supportLincable($model);
        return $this->disk->url(ltrim($model->getRawUrl(), '/'));   
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

            // Determine wheter the model already has a file linked to reuse 
            // the same url. Then the media filename is inserted into the arguments 
            // and we change the upload method to accept a filename. 
            if ($model->exists && $model->getRawUrl()) {
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
            // Generate a temp file from url.
            $filename = sprintf(
                '%s%s.%s', 
                str_finish($this->config('temp_directory'), '/'), 
                str_random(), 
                pathinfo($url, PATHINFO_EXTENSION)
            );

            file_put_contents($filename, $this->disk->get($url));

            // Create the illuminate file from temp filename.
            return new File($filename);
        }

        throw new FileNotFoundException($url);
    }

    /**
     * Create a new a link for model with a file name. 
     * 
     * @param  
     * @param  string|null  $fileName
     * @return string
     */
    public function newLink(Model $model, string $fileName = null) 
    {
        $this->supportLincable($model);

        // Set the model instance to seed the generator and generate
        // the url injecting the model attributes.
        $url = $this->urlGenerator
            ->forModel($model)
            ->generate();

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
        
        if (! in_array(Lincable::class, class_uses_recursive($modelClass))) {
            throw new Exception("The model [$modelClass] does not support Lincable.");
        } 
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
}
