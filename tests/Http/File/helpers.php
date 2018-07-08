<?php

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
        $file = dirname(dirname(dirname(__DIR__))).'/config/lincable.php';
        $configuration = require $file;
        return data_get(['lincable' => $configuration], $key) ?: $default;
    }
}