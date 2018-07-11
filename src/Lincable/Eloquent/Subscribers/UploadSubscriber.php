<?php

namespace Lincable\Eloquent\Subscribers;

class UploadSubscriber
{
    /**
     * Listen when the upload has been executed with success.
     *
     * @return void
     */
    public function onSuccess($model)
    {
        //
    }

    /**
     * Listen when the upload has failed.
     *
     * @return void
     */
    public function onFailure($model)
    {
        // Delete the model from database once the file could not be stored.
        $model->delete();
    }

    /**
     * Register the listeners for the subscriber.
     *
     * @param  \Illuminate\Events\Dispatcher  $events
     * @return void
     */
    public function subscribe($events)
    {
        $events->listen(
            'Lincable\Eloquent\Events\UploadSuccess',
            'Lincable\Eloquent\Subscribers\UploadSubscriber@onSuccess'
        );

        $events->listen(
            'Lincable\Eloquent\Events\UploadFailure',
            'Lincable\Eloquent\Subscribers\UploadSubscriber@onFailure'
        );
    }
}
