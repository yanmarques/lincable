<?php

namespace Lincable\Eloquent;

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
    public function addLincableFields()
    {
        $this->fillable(array_merge( 
            $this->getFillable(),
            $this->getLincableFields()
        ));
    }

    /**
     * Return the fields to link the model.
     * 
     * @return array
     */
    public function getLincableFields()
    {
        return [
            'preview',
            'filename'
        ];
    }
}