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
