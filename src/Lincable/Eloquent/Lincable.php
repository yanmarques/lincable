<?php

namespace Lincable\Eloquent;

use Illuminate\Http\File;
<<<<<<< HEAD
=======
use Lincable\UrlGenerator;
>>>>>>> 4c8a26f5a973bebf5b69c343119e2161a489f17b
use Lincable\MediaManager;
use Lincable\UrlGenerator;
use Illuminate\Container\Container;
use Lincable\Http\File\FileResolver;
<<<<<<< HEAD
use Lincable\Eloquent\Events\UploadFailure;
use Lincable\Eloquent\Events\UploadSuccess;
=======
use Lincable\Eloquent\Events\UploadSuccess;
use Lincable\Eloquent\Events\UploadFailure;
>>>>>>> 4c8a26f5a973bebf5b69c343119e2161a489f17b
use Illuminate\Filesystem\FilesystemAdapter;
use Lincable\Exceptions\ConflictFileUploadHttpException;

trait Lincable
{
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
<<<<<<< HEAD

=======
        
>>>>>>> 4c8a26f5a973bebf5b69c343119e2161a489f17b
        // Handle the file upload to the disk storage. All errors on upload are covered
        // for better handling upload events. One the upload has been executed with
        // success, the model is auto updated, setting the url_field model configuration
        // with the path to the new file. On error, an event of failure is dispatched and
        // a HTTPException is also reported, if not covered will return a 409 HTTP status.
        $this->handleUpload($mediaManager->getDisk(), $mediaManager->buildUrlGenerator(), $file);

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
<<<<<<< HEAD
        throw new ConflictFileUploadHttpException('Could not store the file on disk.');
=======
        throw new ConflictFileUploadHttpException("Could not store the file on disk.");
>>>>>>> 4c8a26f5a973bebf5b69c343119e2161a489f17b
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
<<<<<<< HEAD

=======
            
>>>>>>> 4c8a26f5a973bebf5b69c343119e2161a489f17b
        // Set the model instance to seed the generator and generate
        // the url injecting the model attributes.
        $url = $generator->forModel($this)->generate();

        $this->addLincableFields();

        rescue(function () use ($storage, $url, $media) {
            // Put the file on storage and get the full url to location.
            $fileUrl = $storage->putFile($url, $media);
<<<<<<< HEAD

=======
            
>>>>>>> 4c8a26f5a973bebf5b69c343119e2161a489f17b
            // Update the model with the url of the uploaded file.
            $this->fill([$this->getUrlField() => $storage->url($fileUrl)]);

            // Send the event that the upload has been executed with success.
            event(new UploadSuccess($this, $media));
<<<<<<< HEAD

=======
            
>>>>>>> 4c8a26f5a973bebf5b69c343119e2161a489f17b
            $this->save();
        }, function () use ($media) {

            // Send the event that the upload has failed.
            event(new UploadFailure($this, $media));

            $this->throwUploadFailureException();
        });

        // Re-set the original fillables.
        $this->fillable($originalFillables);
    }
}
