<?php

namespace Lincable;

use Lincable\Parsers\Parser;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;
use Lincable\Contracts\Compilers\Compiler;
use Lincable\Exceptions\NoModelConfException;

class UrlGenerator
{
    /**
     * The model instance.
     *
     * @var \Illuminate\Database\Eloquent\Model
     */
    protected $model;

    /**
     * The parsers collection.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $parsers;

    /**
     * The url configuration for models.
     *
     * @var \Lincable\UrlConf
     */
    protected $urlConf;

    /**
     * The compiler implementation.
     *
     * @var \Lincable\Contracts\Compilers\Compiler
     */
    protected $compiler;

    /**
     * The parsers available for the current model.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $availableParsers;

    /**
     * The formatter resolver for dynamic parameters on model.
     *
     * @var mixed
     */
    protected $parameterResolver;

    /**
     * Create a new class instance.
     *
     * @param  \Lincable\Contracts\Compilers\Compiler $compiler
     * @param  \Illuminate\Support\Collection $parsers
     * @param  \Lincable\UrlConf $urlConf
     * @return void
     */
    public function __construct(Compiler $compiler, Collection $parsers, UrlConf $urlConf)
    {
        $this->parsers = $parsers;
        $this->urlConf = $urlConf;
        $this->compiler = $compiler;
    }

    /**
     * Set the current model to generate the url.
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @param  array $params
     * @return this
     */
    public function forModel(Model $model, array $params = [])
    {
        // Verify wheter we have the model fully configured on the url configuration.
        $this->guardModel($model);

        // Set the formatters for the model attributes.
        $this->setModelFormatters($params);

        return $this;
    }

    /**
     * Guard the model setting verifying wheter the model is also configured
     * on url configuration.
     *
     * @throws \Lincable\Exceptions\NoModelConfException
     *
     * @param  \Illuminate\Database\Eloquent\Model $model
     * @return void
     */
    protected function guardModel(Model $model)
    {
        // The model class name.
        $className = get_class($model);

        if (! $this->urlConf->has($className)) {
            throw new NoModelConfException("Model [{$className}] is not configured. Check your lincable url configuration.");
        }

        // We assume the model is configured on url conf
        // then we can set the model for url generation.
        $this->model = $model;
    }

    /**
     * Add the formatters model parameters based on url dynamic parameters.
     *
     * @param  array $params
     * @return void
     */
    protected function setModelFormatters(array $customParams = [])
    {
        $attributes = $this->getModel()->getAttributes();

        // Merge model attributes with the an array on custom parameters.
        $parameters = array_merge($attributes, $customParams);

        // Filter only the parameters been used on url.
        $formatterParameters = $this->filterDynamicParameters($parameters);

        // Create the formatters for the dynamic parsers.
        $this->injectFormatterToAvailableParsers($formatterParameters);
    }

    /**
     * Generate the url for the current model.
     *
     * @return string
     */
    public function generate()
    {
        return $this->availableParsers->reduce(function ($url, Parser $parser) {

            // Set the compiler current parser.
            $this->compiler->setParser($parser);

            // Return the compiled url.
            return $this->compiler->compile($url);
        }, $this->getRawUrl());
    }

    /**
     * Return the raw model url.
     *
     * @return string
     */
    public function getRawUrl()
    {
        return $this->urlConf->get(get_class($this->getModel()));
    }

    /**
     * Return the model instance.
     *
     * @throws \Exception
     *
     * @return \Illuminate\Database\Eloquent\Model
     */
    public function getModel()
    {
        if ($this->model) {
            return $this->model;
        }

        throw new \Exception('Any model related with generator');
    }

    /**
     * Return the compiler class instance.
     *
     * @return \Lincable\Contracts\Compilers\Compiler
     */
    public function getCompiler()
    {
        return $this->compiler;
    }

    /**
     * Return the collection parsers.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getParsers()
    {
        return $this->parsers;
    }

    /**
     * Return the collection with available parsers for model.
     *
     * @return \Illuminate\Support\Collection
     */
    public function getAvailableParsers()
    {
        return $this->availableParsers;
    }

    /**
     * Return the model url configuration.
     *
     * @return \Lincable\UrlConf
     */
    public function getUrlConf()
    {
        return $this->urlConf;
    }

    /**
     * Set the function to resolve parameter formatter.
     *
     * @param  mixed $resolver
     * @return this
     */
    public function setParameterResolver($resolver)
    {
        $this->parameterResolver = $resolver;

        return $this;
    }

    /**
     * Set the compiler class instance.
     *
     * @param  \Lincable\Contracts\Compilers\Compiler
     * @return this
     */
    public function setCompiler(Compiler $compiler)
    {
        $this->compiler = $compiler;

        return $this;
    }

    /**
     * Return the filtered dynamic parameters to apply as a formatter
     * on the parsers classes.
     *
     * @param  array $parameters
     * @return array
     */
    protected function filterDynamicParameters(array $parameters)
    {
        $this->parseAvailableParsers();

        // Get the url configured for the model.
        $url = $this->getRawUrl();

        // Get all dynamic parameters on url from model parsers.
        $dynamics = $this->availableParsers->map(function (Parser $parser) use ($url) {

            // Set the current parser.
            $this->compiler->setParser($parser);

            return $this->compiler->parseDynamics($url);
        })->flatten()->all();

        return array_filter($parameters, function ($key) use ($dynamics) {
            return in_array($key, $dynamics);
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * Add the formatter for the parameters on the parsers based on current model url.
     *
     * @param  array $dynamicParameters
     * @return void
     */
    protected function injectFormatterToAvailableParsers(array $dynamicParameters)
    {
        $this->availableParsers->each(function (Parser $parser) use ($dynamicParameters) {
            foreach ($dynamicParameters as $key => $value) {

                // Register the formatter for the key, and the logic function is to return
                $parser->addFormatter(function () use ($value, $parser) {
                    if ($this->parameterResolver) {

                        // Get the container instance on parser class.
                        $container = $parser->getContainer();

                        // Call the parameter resolver for the value returned.
                        $value = $container->call($this->parameterResolver, [$value]);
                    }

                    return $value;
                }, $key);
            }
        });
    }

    /**
     * Generate the available parsers for the model.
     *
     * @return void
     */
    protected function parseAvailableParsers()
    {
        // Get the url configured for the model.
        $url = $this->getRawUrl();

        // Get parsers that has dynamic parameters for model url.
        $availableParsers = $this->parsers->filter(function (Parser $parser) use ($url) {

            // Change the current compiler parser.
            $this->compiler->setParser($parser);

            return $this->compiler->hasDynamics($url);
        });

        $this->availableParsers = clone $availableParsers;
    }
}
