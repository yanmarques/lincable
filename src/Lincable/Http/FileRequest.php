<?php

namespace Lincable\Http;

use Illuminate\Http\Request;
use Lincable\Concerns\BuildClassnames;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Factory;
use Symfony\Component\HttpFoundation\File\File;

abstract class FileRequest extends FormRequest
{
    use BuildClassnames;

    /**
     * Rules to validate the file on request.
     *
     * @return mixed
     */
    abstract public function rules();

    /**
     * Return the file on request.
     *
     * @return \Illuminate\Http\UploadedFile
     */
    public function getFile()
    {
        return $this->file($this->getParameter());
    }

    /**
     * Return the parameter name.
     *
     * @return string
     */
    public function getParameter()
    {
        if (! $this->parameter) {
            $this->parameter = $this->retrieveParameter();
        }

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
    public function prepareFile()
    {
        $file = $this->moveFileToTempDirectory($this->getFile());

        return $this->executeFileEvents($file);
    }

    /**
     *{@inheritDoc}
     */
    public function validator(Factory $factory)
    {
        return $factory->make(
            $this->validationData(), $this->parseValidationRules(),
            $this->messages(), $this->attributes()
        );
    }

    /**
     * Return the parameter name from class name.
     *
     * @return string
     */
    protected function retrieveParameter()
    {
        $className = static::class;
        
        return $this->nameFromClass($className, class_basename(self::class));
    }

    /**
     * Get the rules for the file validation.
     *
     * @return array
     */
    protected function parseValidationRules()
    {
        return [$this->getParameter() => $this->container->call([$this, 'rules'])];
    }

    /**
     * Move the file to a temporary destination.
     *
     * @return \Symfony\Component\HttpFoundation\File\File
     */
    protected function moveFileToTempDirectory()
    {
        $file = $this->getFile();

        return $file->move(config('lincable.temp_directory'), $file->hashName());
    }

    /**
     * Execute some generic event methods on class if available.
     *
     * Here the file can be changed, optimized, etc...
     *
     * @param  \Symfony\Component\HttpFoundation\File\File  $file
     * @return mixed
     */
    protected function executeFileEvents(File $file)
    {
        $callable = [$this, 'beforeSend'];

        if (method_exists($callable[0], $callable[1])) {

            // Handle the result from event call.
            if ($result = $this->container->call($callable, [$file])) {
                return $result;
            }
        }

        return $file;
    }
}
