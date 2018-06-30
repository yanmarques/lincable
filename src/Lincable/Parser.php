<?php

namespace Lincable;

use LogicException;
use Illuminate\Support\Collection;
use Illuminate\Contracts\Container\Container;
use Lincable\Concerns\BuildClassnames;
use Lincable\Contracts\Formatters\Formatter;
use Lincable\Exceptions\NotDynamicOptionException;

abstract class Parser
{
    use BuildClassnames;

    /**
     * List with availables formatters.
     * 
     * @var \Illuminate\Support\Collection
     */
    protected $formatters;

    /**
     * The application container implementation.
     * 
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;

    /**
     * Return the formatter call for the matches on parse.
     * 
     * @param  array $matches
     * @return mixed
     */
    abstract protected function parseMatches(array $matches);

    /**
     * Return the dynamic regex pattern.
     * 
     * @return string
     */
    abstract protected function getDynamicPattern();

    /**
     * Push a new formatter to collection. 
     *
     * @param  mixed $formatter
     * @return this
     */
    public function addFormatter($formatter)
    {
        $this->formatters->push($formatter);
        return $this;
    }

    /**
     * Parse an option through formatters.
     * 
     * @throws Lincable\Exceptions\NotDynamicOption
     * 
     * @param  string $option
     * @return mixed
     */
    public function parse(string $option)
    {
        if ($this->shouldParse($option)) {
            
            // We have verified the option is dynamic and 
            // has matches. Now we pass the controll to the implemented
            // abstract function.
            return $this->parseMatches($this->getMatches($option));
        }

        throw new NotDynamicOptionException("Can not parse non dynamics parameter [$option].");
    }

    /**
     * Append a list of formatters. 
     *
     * @param  mixed $formatters
     * @return this
     */
    public function addFormatters($formatters)
    {
        array_map(function ($formatter) {
            $this->addFormatter($formatter);
        }, $formatters);

        return $this;
    }

    /**
     * Set the list with the new formatters.
     * 
     * @param  \Illuminate\Support\Collection $formatters
     * @return this
     */
    public function setFormatters(Collection $formatters)
    {
        $this->formatters = $formatters;
        return $this;
    }

    /**
     * Return array with formatter classes. 
     *
     * @return array
     */
    public function getFormatters()
    {
        return $this->formatters->toArray();
    }

    /**
     * Return the containter instance.
     * 
     * @return \Illuminate\Contracts\Container\Container
     */
    public function getContainer()
    {
        return $this->app;
    }

    /**
     * Set the new container instance. 
     *
     * @param  \Illuminate\Contracts\Container\Container $app
     * @return this
     */
    public function setContainer(Container $app)
    {
        $this->app = $app;
        return $this;
    }

    /**
     * Return the first formatter that matches the option name. 
     *
     * @param  string $option
     * @return mixed
     */
    public function findFormatter(string $option)
    {
        return $this->firstClassName(
            $option, 
            $this->formatters->toArray(), 
            'Formatter'
        );
    }

    /**
     * Determine wheter the parameter should be parsed.
     * 
     * @param  string $option
     * @return bool
     */
    protected function shouldParse(string $option)
    {
        return $this->isParameterDynamic($option) 
            && $this->getMatches($option);
    }

    /**
     * Call the formatter class finding it by name. 
     *
     * @throws \LogicException
     * 
     * @param  string $name
     * @param  array  $params
     * @return mixed
     */
    protected function callFormatter(string $name, array $params = [])
    {
        if ($formatter = $this->findFormatter($name)) {
            
            // Execute the formatter class with the params.
            return $this->executeFormatter(
                $this->resolveFormatter($formatter), 
                $params
            );
        }

        throw new LogicException("Call to undefined formatter [$name].");
    }

    /**
     * Resolve the formatter object call with the array parameters. 
     *
     * @param  Lincable\Contracts\Formatters\Formatter $formatter
     * @param  array $params
     * @return mixed
     */
    protected function executeFormatter(Formatter $formatter, array $params = [])
    {
        // Call the format method of formatter class.  
        return $formatter->format(...$params);
    }

    /**
     * Resolve a formatter to class instance.
     * 
     * @param  mixed $formatter
     * @return void
     */
    protected function resolveFormatter($formatter)
    {
        if (is_string($formatter)) {

            // Resolve the formatter instance using the container.
            $formatter = $this->app->make($formatter);
        }

        return $formatter;
    }

    /**
     * Determine wheter the parameter is dynamic.
     * 
     * @param  string $parameter
     * @return bool
     */
    protected function isParameterDynamic(string $parameter)
    {
        return preg_match($this->getDynamicPattern(), $parameter);
    }

    /**
     * Return the matches.
     * 
     * @param  string $parameter
     * @return array
     */
    public function getMatches(string $parameter)
    {
        $isDynamic = preg_match(
            $this->getDynamicPattern(), 
            $parameter, 
            $matches
        );

        if ($isDynamic) {

            // The parameter is dynamic and has matches, so remove the first 
            // match, that only matches the whole word. 
            array_shift($matches);
        } 
        
        return $matches;
    }
}