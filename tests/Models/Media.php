<?php

namespace Tests\Lincable\Models;

use Lincable\Eloquent\Lincable;
use Illuminate\Contracts\Support\Htmlable;

class Media extends Foo implements Htmlable
{
    use Lincable;

    protected $table = 'media_test';

    public $preserveName = false;

    public $keepMediaOnDelete = null;

    public $urlField = null;
}