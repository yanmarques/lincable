<?php

namespace Tests\Lincable\Models;

use Lincable\Eloquent\Lincable;

class MediaWithMutator extends Media
{
    public $prefix = '';

    public function getPreviewAttribute($url)
    {
        return $this->prefix.$url;
    }
}