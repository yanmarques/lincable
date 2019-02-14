<?php

namespace Lincable\Eloquent\Events;

use Illuminate\Http\File;
use Illuminate\Database\Eloquent\Model;

abstract class Event
{
    /**
     * The model instance that failed the upload.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    public $model;

    /**
     * The failure file.
     *
     * @var \Illuminate\Http\File
     */
    public $file;

    /**
     * Construtor da classe.
     *
     * @return void
     */
    public function __construct(Model $model, File $file)
    {
        $this->model = $model;
        $this->file = $file;
    }
}
