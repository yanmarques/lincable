<?php

namespace Tests\Lincable\Http\FileRequests;

use Lincable\Http\FileRequest;

class GenericFileRequest extends FileRequest
{
    /**
     * The extension rule.
     *
     * @var string
     */
    protected $extension;

    /**
     * Set the extension rule to follow.
     *
     * @param  string  $extension
     * @return void
     */
    public function setExtension(string $extension)
    {
        $this->extension = $extension;
    }

    /**
     * Rules to validate the file on request.
     *
     * @return mixed
     */
    public function rules()
    {
        return ['required', $this->extension ? 'mimes:'.$this->extension : null];
    }
}