<?php

namespace Lincable\Eloquent;

use Lincable\MediaManager;
use Illuminate\Container\Container;
use Lincable\Http\File\FileResolver;

trait Lincable
{
    /**
     * Boot the model statically.
     * 
     * @return void
     */
    public static function bootLincable()
    {
        static::creating(function ($model) {
            $model->addLincableFields();
        });
    }

    /**
     * Add the lincable fields to model fillables.
     * 
     * @return void
     */
    public function addLincableField()
    {
        // Get the model fillable fields.
        $fillables = $this->getFillable();

        $this->fillable(array_merge($fillables, [$this->getUrlField()]));
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
     * Return a media manager instance.
     * 
     * @return \Lincable\MediaManager
     */
    protected function getMediaManager() 
    {
        return Container::getInstance()->make(MediaManager::class);
    }
}