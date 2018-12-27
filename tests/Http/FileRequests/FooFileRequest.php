<?php

namespace Tests\Lincable\Http\FileRequests;

use Lincable\Http\FileRequest;

class FooFileRequest extends FileRequest
{
    /**
     * File to move the file before send.
     * 
     * @var string
     */
    private $destination;

    /**
     * 
     * 
     * @param  string $file
     * @return void
     */
    public function __construct(string $destination)
    {
        $this->destination = $destination;
    }

    /**
     * Rules to validate the file on request.
     *
     * @return mixed
     */
    protected function rules()
    {
        return 'mimes:txt';
    }

    /**
     * Executed before sending the file.
     * 
     * @return mixed
     */
    public function beforeSend($file)
    {
        return $file->move($this->destination);
    }
}

