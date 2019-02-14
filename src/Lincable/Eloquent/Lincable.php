<?php

namespace Lincable\Eloquent;

use File;
use Lincable\MediaManager;
use Lincable\Http\FileRequest;
use Lincable\Http\File\FileFactory;
use Illuminate\Container\Container;
use Lincable\Http\File\FileResolver;
use Illuminate\Http\File as IlluminateFile;
use Lincable\Exceptions\LinkNotFoundException;

trait Lincable
{
    use CloneLinks;

    /**
     * Resolved media manager instance.
     *
     * @var \Lincable\MediaManager
     */
    protected static $mediaManager;

    /**
     * Boots the trait.
     *
     * @return void
     */
    protected static function bootLincable()
    {
        static::deleted(function ($model) {
            if (! $model->shouldKeepMediaWhenDeleted()) {
                static::getMediaManager()->delete($model);
            }
        });
    }

    /**
     * Return the raw url saved on database.
     *
     * @return string
     *
     * @throws \Lincable\Exceptions\LinkNotFoundException
     */
    public function getRawUrl()
    {
        return $this->getAttributeFromArray($this->getUrlField());
    }

    /**
     * Return the full url media from model.
     *
     * @return void
     */
    public function getUrl()
    {
        return static::getMediaManager()->url($this);
    }

    /**
     * Escope to easily create a fresh model from a FileRequest class.
     *
     * @param  mixed  $query
     * @param  \Lincable\Http\FileRequest  $fileRequest
     * @return mixed
     */
    public function scopeCreateWithFileRequest($query, FileRequest $fileRequest)
    {
        return tap(
            $query->newModelInstance($fileRequest->all()),
            function ($instance) use ($fileRequest) {
                $instance->perfomCreateWithFileRequest($fileRequest);
            }
        );
    }

    /**
     * Execute creation events and upload process for the file request.
     *
     * @param  \Lincable\Http\FileRequest  $request
     * @return void
     */
    public function perfomCreateWithFileRequest(FileRequest $request)
    {
        if ($request->getFile() === null) {
            return $this->save();
        }

        if ($this->fireModelEvent('creating') === false) {
            return false;
        }

        $silentEvents = $this->getSilentUploadEvents();
        
        \Event::fakeFor(
            function () use ($request) {
                // First we create the model on database, then we are allowed to proceed
                // sending the file to storage, no more breaks stops us from finishing,
                // unless upload failed.
                $this->save();
                $this->link($request);
            },
            array_map(function ($event) {
                return "eloquent.{$event}: ".static::class;
            }, $silentEvents)
        );

        $this->fireModelEvent('created');
    }

    /**
     * Link the model to a file.
     *
     * @param  mixed  $file
     * @return bool
     */
    public function link($file)
    {
        // Resolve the file object to link the model. It can
        // be a symfony uploaded file or a file request, which
        // is preferable for linking.
        $file = FileResolver::resolve($file);
        
        // Handle the file upload to the disk storage. All errors on upload are covered
        // for better handling upload events. Once the upload has been executed with
        // success, the model is auto updated, setting the url_field model configuration
        // with the path to the new file. On error, an event of failure is dispatched and
        // a HTTPException is also reported, if not covered will return a 409 HTTP status.
        return static::getMediaManager()
            ->upload($file, $this, $this->getCustomUploadHeaders())
            ->save();
    }

    /**
     * Execute a container callable with the file as argument.
     *
     * @param  mixed $callback
     * @return this
     */
    public function withMedia($callback)
    {
        $this->ensureHasUrl();

        // Get the current media manager of application.
        $mediaManager = static::getMediaManager();

        // Create a temporary file with the model file contents. Provide the
        // the default disk registered on media manager.
        $file = $mediaManager->get($this);

        try {
            // Execute the callable with the temporary file. You also can receive the
            // model instance as second argument.
            Container::getInstance()->call($callback, [$file, $this]);
        } catch (\Exception $ex) {
            // Delete the temporary file wheter it exists.
            File::delete($file->path());

            // Throw exception again to show developer something bad happened.
            throw $ex;
        }

        return $this;
    }

    /**
     * Return the url field to link the model.
     *
     * @return string
     */
    public function getUrlField()
    {
        return isset($this->urlField)
            ? $this->urlField
            : config('lincable.models.url_field');
    }

    /**
     * Return the name of the file.
     *
     * @return string
     */
    public function getFileName()
    {
        $this->ensureHasUrl();
        return FileFactory::fileName($this->getRawUrl());
    }

    /**
     * Return the file extension.
     *
     * @return string
     */
    public function getExtension()
    {
        return FileFactory::extension($this->getFileName());
    }

    /**
     * Fulfill the url on correct attribute name.
     *
     * @param  string  $url
     * @return void
     */
    public function fillUrl(string $url)
    {
        // Fill the url with unguarded permissions.
        static::unguarded(function () use ($url) {
            $this->fill([$this->getUrlField() => ltrim($url, '/')]);
        });

        return $this;
    }

    /**
     * Return the media manager instance.
     *
     * @return \Lincable\MediaManager
     */
    public static function getMediaManager()
    {
        if (static::$mediaManager === null) {
            static::$mediaManager = resolve(MediaManager::class);
        }

        return static::$mediaManager;
    }
    
    /**
     * Set the newly media manager.
     *
     * @param  \Lincable\MediaManager  $manager
     * @return void
     */
    public static function setMediaManager(MediaManager $manager)
    {
        static::$mediaManager = $manager;
    }

    /**
     * Determine wheter shoul keep the media for the model.
     *
     * @return bool
     */
    public function shouldKeepMediaWhenDeleted()
    {
        return (bool) (
            isset($this->keepMediaOnDelete)
                ? $this->keepMediaOnDelete
                : config('lincable.keep_media_on_delete', false)
            );
    }

    /**
     * Returns the list of uploda headers for model.
     *
     * @return array
     */
    public function getCustomUploadHeaders()
    {
        return (array) (
            isset($this->customUploadHeaders)
                ? $this->customUploadHeaders
                : config('lincable.upload_headers', [])
        );
    }

    /**
     * List of events to not be fired when creating the model
     * from a FileRequest. As the model is only considered really
     * created after file upload is ready, we only fire events after that.
     *
     * @return array
     */
    protected function getSilentUploadEvents()
    {
        return (array) (
            $this->silentUploadEvents ??
            config('lincable.models.silent_upload_events', [])
        );
    }

    /**
     * Forward getter call.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function __get($key)
    {
        if ($key === $this->getUrlField()) {
            // Let the model to use own resolution logic to create the link.
            if ($this->hasGetMutator($key)) {
                return $this->mutateAttribute($key, $this->getRawUrl());
            }

            return $this->getUrl();
        }

        return $this->getAttribute($key);
    }

    /**
     * Get content as a string of HTML.
     *
     * @return string
     */
    public function toHtml()
    {
        $url = $this->{$this->getUrlField()};

        $options = collect($this->getHtmlOptions())
            ->map(function ($value, $key) {
                if (is_int($key)) {
                    $key = $value;
                    $value = '';
                }

                return $key.'="'.$value.'"';
            })
            ->implode(' ');

        return '<img src="'.$url.'" '.$options.'>';
    }

    /**
     * Return the key -> value array for htmlable element.
     *
     * @return array
     */
    protected function getHtmlOptions()
    {
        return [];
    }

    /**
     * Ensures the model will have an url stored in attributes.
     *
     * @return void
     *
     * @throws \Lincable\Exceptions\LinkNotFoundException
     */
    protected function ensureHasUrl()
    {
        if ($this->getRawUrl() === null) {
            // The preview image could not be found for model.
            throw new LinkNotFoundException(
                'Model ['.static::class.'] does not a file linked with.'
            );
        }
    }
}
