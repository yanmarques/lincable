<?php

namespace Lincable;

use Lincable\Concerns\BuildClassnames;
use Illuminate\Contracts\Config\Repository;
use Lincable\Exceptions\ConfModelNotFoundException;

class UrlConf implements Repository
{
    use BuildClassnames;

    /**
     * The url model configuration.
     *
     * @var array
     */
    protected $configuration = [];

    /**
     * The base namespace for the models.
     *
     * @var string
     */
    protected $modelsNamespace;

    /**
     * Create a new class instance.
     *
     * @param  array $conf
     * @return void
     */
    public function __construct(string $modelsNamespace)
    {
        $this->modelsNamespace = $modelsNamespace;
    }

    /**
     * Determine if the given configuration value exists.
     *
     * @param  string  $key
     * @return bool
     */
    public function has($key)
    {
        return isset($this->configuration[$key]);
    }

    /**
     * Get the specified configuration value.
     *
     * @param  array|string  $key
     * @param  mixed   $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return $this->has($key) ? $this->configuration[$key] : $default;
    }

    /**
     * Get all of the configuration items for the application.
     *
     * @return array
     */
    public function all()
    {
        return $this->configuration;
    }

    /**
     * Set a given configuration value.
     *
     * @param  array|string  $key
     * @param  mixed   $value
     * @return void
     */
    public function set($key, $value = null)
    {
        if (is_array($key)) {

            // Handle an array insert value.
            foreach ($key as $k => $v) {
                $this->set($k, $v);
            }
        } else {

            // Get model class name from key.
            $key = $this->getModelFromKey($key);

            // Set the configuration.
            $this->configuration[$key] = $value;
        }
    }

    /**
     * Prepend a value onto an array configuration value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function prepend($key, $value)
    {
        $this->push($key, $value);
    }

    /**
     * Push a value onto an array configuration value.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return void
     */
    public function push($key, $value)
    {
        $this->configuration = array_add(
            $this->all(),
            $this->getModelFromKey($key),
            $value
        );
    }

    /**
     * Set the models namespace.
     *
     * @param  string $namespace
     * @return this
     */
    public function setModelsNamespace(string $namespce)
    {
        $this->modelsNamespace = $namespce;
        return $this;
    }

    /**
     * Return the model namespace from key.
     *
     * @param  string $key
     * @return string
     */
    protected function getModelFromKey(string $key)
    {
        $model = $key;

        if (str_contains($key, '.') || ! str_contains($key, '\\')) {
            
            // Build the class namespace using the base model namespace
            // and the parts of the key splitted by dots.
            $model =  $this->buildNamespace(array_merge(
                [$this->modelsNamespace],
                array_map('ucfirst', explode('.', $key))
            ));
        }

        if (class_exists($model)) {
            return $model;
        }

        throw new ConfModelNotFoundException("Could not find model [{$key}]. We tried to load from [{$model}].");
    }
}
