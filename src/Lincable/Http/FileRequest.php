<?php

namespace Lincable\Http;

use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Lincable\Concerns\BuildClassnames;
use Illuminate\Contracts\Container\Container;
use Symfony\Component\HttpFoundation\File\File;

abstract class FileRequest
{
    use BuildClassnames;

    /**
     * The uploaded file instance.
     *
     * @var \Illuminate\Http\File
     */
    protected $file;

    /**
     * The request instance.
     *
     * @var \Illuminate\Http\Request
     */
    protected $request;

    /**
     * The directory where the uploaded file will be temporary moved.
     *
     * @var string
     */
    protected $tempDirectory = '/tmp';

    /**
     * Rules to validate the file on request.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return mixed
     */
    abstract protected function rules(UploadedFile $file): mixed;

    /**
     * Boot the instance with the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function boot(Request $request)
    {
        $this->request = $request;

        $this->guardFile($request->file($this->getParameter()));
    }

    /**
     * Return the file on request.
     *
     * @return \Illuminate\Http\UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    /**
     * Return the current request instance.
     *
     * @return \Illuminate\Http\Request
     */
    public function getRequest()
    {
        return $this->request;
    }

    /**
     * Prepared the file to send.
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function prepareFile()
    {
        $file = $this->moveFileToTempDirectory();

        $this->executeFileEvents(app(), $file);

        return $file;
    }

    /**
     * Return the file parameter on request.
     *
     * @return string
     */
    protected function getParameter()
    {
        $className = static::class;

        return $this->nameFromClass($className);
    }

    /**
     * Guard the file through validations and then set
     * the file on class instance.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return void
     */
    protected function guardFile(UploadedFile $file)
    {
        $validationRules = $this->parseValidationRules($file);

        // Validate the request file from rules.
        $this->request->validate($validationRules);

        $this->file = $file;
    }

    /**
     * Get the rules for the file validation.
     *
     * @param  \Illuminate\Http\UploadedFile $file
     * @return array
     */
    protected function parseValidationRules(UploadedFile $file)
    {
        return [$this->getParameter() => $this->rules($file)];
    }

    /**
     * Move the file to a temporary destination.
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    protected function moveFileToTempDirectory()
    {
        $destination = $this->file->hashName($this->tempDirectory);

        return $this->file->move($destination);
    }

    /**
     * Execute some generic event methods on class if available.
     *
     * Here the file can be changed, optimized, etc...
     *
     * @return void
     */
    protected function executeFileEvents(Container $app, File $file)
    {
        $eventMethod = 'beforeSend';

        if (method_exists($this, $eventMethod)) {
            $app->call([$this, $method], $file);
        }
    }

    /**
     * Return the class suffix convention.
     *
     * @return string
     */
    protected function getSuffix()
    {
        return 'FileRequest';
    }
}
