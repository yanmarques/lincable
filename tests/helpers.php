<?php

use Illuminate\Container\Container;

if (! function_exists('config')) {
    /**
     * Read configuration from package and get items.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    function config(string $key, $default = null)
    {
        $configuration = Container::getInstance()['config'];
        return data_get($configuration, $key, $default);
    }
}

if (! function_exists('storage_path')) {
    /**
     * Return the storage path.
     *
     * @param  string|null $path
     * @return string
     */
    function storage_path(string $path = null)
    {
        return '/tmp/';
    }
}

if (! function_exists('event')) {
    /**
     * Fire the given event with the registered dispatcher.
     *
     * @param  string|object  $event
     * @param  mixed  $payload
     * @param  bool  $halt
     * @return void
     */
    function event()
    {
        Container::getInstance()['events']->fire(...func_get_args());
    }
}

if (! function_exists('rescue')) {
    /**
     * Catch a potential exception and return a default value.
     *
     * @param  callable  $callback
     * @param  mixed  $rescue
     * @return mixed
     */
    function rescue(callable $callback, $rescue = null)
    {
        try {
            return $callback();
        } catch (Throwable $e) {
            return value($rescue);
        }
    }
}
