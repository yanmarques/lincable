<?php

namespace Lincable\Eloquent;

use File;
use Lincable\MediaManager;
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
        // for better handling upload events. Once the upload has been executed with
        // success, the model is auto updated, setting the url_field model configuration
        // with the path to the new file. On error, an event of failure is dispatched and
        // a HTTPException is also reported, if not covered will return a 409 HTTP status.
        static::getMediaManager()->upload($file, $this)->save();

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
        return config('lincable.models.url_field');
    }

    /**
     * Return the name of the file.
     *
     * @return string
     */
    public function getFileName()
    {
        $this->ensureHasUrl();
        return pathinfo($this->getRawUrl(), PATHINFO_BASENAME);
    }

    /**
     * Return the file extension.
     *
     * @return string
     */
    public function getExtension()
    {
        return pathinfo($this->getFileName(), PATHINFO_EXTENSION);
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
            $this->fill([$this->getUrlField() => $url]);
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
     * Forward getter call.
     *
     * @param  mixed  $key
     * @return mixed
     */
    public function __get($key)
    {
        if ($key === $this->getUrlField()) {
            return static::getMediaManager()->url($this);
        }

        return $this->getAttribute($key);
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
