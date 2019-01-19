<?php

namespace Tests\Lincable\Http\FileRequests;

use Lincable\Http\FileRequest;

class FooFileRequest extends GenericFileRequest
{
    protected $path;
    protected $name;

    /**
     * Set the file destination to move before send it.
     *
     * @param  string  $path
     * @param  string|null  $name
     * @return void
     */
    public function setDestination(string $path, string $name = null)
    {
        $this->path = $path;
        $this->name = $name;
    }

    /**
     * Executed before sending the file.
     * 
     * @return mixed
     */
    public function beforeSend($file)
    {
        if ($this->path) {
            return $file->move($this->path, $this->name);
        }
    }
}

