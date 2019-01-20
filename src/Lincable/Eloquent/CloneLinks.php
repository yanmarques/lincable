<?php

namespace Lincable\Eloquent;

use Lincable\MediaManager;
use Lincable\UrlGenerator;

/**
 * @method bool replicate()
 * @method string getUrlField()
 * @method mixed runQuiet()
 * @static \Lincable\MediaManager getMediaManager()
 * @static \Illuminate\Contracts\Events\Dispatcher getEventDispatcher()
 * @static \Illuminate\Contracts\Events\Dispatcher setEventDispatcher()
 * @static void unsetEventDispatcher()
 */
trait CloneLinks
{
    /**
     * The source model where this one was cloned from.
     * 
     * It's null when is not a clone.
     *
     * @var parent
     */
    protected $sourceModel;

    /**
     * Boots the trait.
     * 
     * @return void
     */
    protected static function bootCloneLinks() 
    {
        static::created(function ($model) {
            static::copyCloneMedia($model);
        });
    }

    /**
     * Copy the media from source to the new cloned model. 
     *
     * @param  self  $model
     * @return void
     */
    protected static function copyCloneMedia(self $model)
    {
        if ($model->isClone()) {
            // Run copy in silence mode as this is just a callback
            // function when clone is created.
            $currentDispatcher = static::getEventDispatcher();
            static::unsetEventDispatcher();

            static::getMediaManager()->copy(
                $model->getSourceModel(), 
                $model,
                $model->preservesFilename()
            )->save();

            static::setEventDispatcher($currentDispatcher);
        }
    }

    /**
     *{@inheritDoc}
     */
    public function replicate(array $except = null)
    {
        $clone = parent::replicate($except);
        
        $clone->setSourceModel($this);

        return $clone;
    }

    /**
     * Set the newly source model.
     *
     * @param  parent  $model
     * @return void
     */
    public function setSourceModel(parent $model)
    {
        $this->sourceModel = $model;
    }

    /**
     * Return the model where this one was cloned.
     * 
     * @return parent|null
     */
    public function getSourceModel()
    {
        return $this->sourceModel;
    }

    /**
     * Defines wheter the class is an clone.
     * 
     * @return bool
     */
    public function isClone()
    {
        return $this->sourceModel !== null;
    }

    /**
     * Defines wheter the original filename should be preserved
     * when cloning the model.
     * 
     * @return bool
     */
    public function preservesFilename()
    {
        return $this->preserveName ?? false;
    }
}