<?php

namespace Tests\Lincable\Models;

use Lincable\Eloquent\Lincable;

class Media extends Foo
{
    protected $table = 'media_test';

    public $preserveName = false;

    use Lincable;
}