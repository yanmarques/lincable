<?php

namespace Lincable\Http;

use Illuminate\Http\Request;
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
     * The file parameter on request.
     *
     * @var string
     */
    protected $parameter;

    /**
     * Determine wheter has been booted with a request.
     *
     * @var bool
     */
    protected $booted = false;

    /**
     * Rules to validate the file on request.
     *
     * @return mixed
     */
    abstract protected function rules();

    /**
     * Boot the instance with the request.
     *
     * @param  \Illuminate\Http\Request $request
     * @return void
     */
    public function boot(Request $request)
    {
        $this->request = $request;

        if (! $this->getParameter()) {

            // Set the file parameter.
            $this->setParameter($this->retrieveParameter());
        }

        // Guard the file through validations.
        $this->validate();

        $this->file = $request->file($this->getParameter());

        $this->booted = true;
    }

    /**
     * Return wheter the file request is booted.
     *
     * If is booted that means the file has been validated and
     * a request instance is available on instance.
     *
     * @return bool
     */
    public function isBooted()
    {
        return $this->booted;
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
     * Return the parameter name.
     *
     * @return string
     */
    public function getParameter()
    {
        return $this->parameter;
    }

    /**
     * Set the parameter name.
     *
     * @param  string $parameter
     * @return this
     */
    public function setParameter(string $parameter)
    {
        $this->parameter = $parameter;

        return $this;
    }

    /**
     * Shortcut for @method setParameter.
     *
     * @param  string $parameter
     * @return this
     */
    public function as(string $parameter)
    {
        return $this->setParameter($parameter);
    }

    /**
     * Prepared the file to send.
     *
     * @param  \Illuminate\Contracts\Container\Container $app
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    public function prepareFile(Container $app)
    {
        $file = $this->moveFileToTempDirectory();

        return $this->executeFileEvents($app, $file);
    }

    /**
     * Validate the file with the defined rules.
     *
     * @return void
     */
    public function validate()
    {
        $validationRules = $this->parseValidationRules();

        // Validate the request file from rules.
        $this->request->validate($validationRules);
    }

    /**
     * Return the parameter name from class name.
     *
     * @return string
     */
    protected function retrieveParameter()
    {
        $className = static::class;

        return $this->nameFromClass($className, 'FileRequest');
    }

    /**
     * Get the rules for the file validation.
     *
     * @return array
     */
    protected function parseValidationRules()
    {
        return [$this->getParameter() => $this->rules()];
    }

    /**
     * Move the file to a temporary destination.
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    protected function moveFileToTempDirectory()
    {
        $destination = $this->file->hashName();

        return $this->file->move(config('lincable.temp_directory'), $destination);
    }

    /**
     * Execute some generic event methods on class if available.
     *
     * Here the file can be changed, optimized, etc...
     *
     * @param  \Illuminate\Contracts\Container\Container $app
     * @param  \Symfony\Component\HttpFoundation\File\File $file
     * @return mixed
     */
    protected function executeFileEvents(Container $app, File $file)
    {
        $callable = [$this, 'beforeSend'];

        if (method_exists($callable[0], $callable[1])) {

            // Handle the result from event call.
            if ($result = $app->call($callable, [$file])) {
                return $result;
            }
        }

        return $file;
    }
}
