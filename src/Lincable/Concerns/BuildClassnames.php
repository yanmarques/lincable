<?php

namespace Lincable\Concerns;

use Illuminate\Support\Str;

trait BuildClassnames
{
    /**
     * Determine wheter the case to use is camel.
     *
     * @var bool
     */
    protected $camelCase = true;

    /**
     * Return the basename of class do camel case removing
     * unecessary suffixes.
     *
     * @param  mixed  $class
     * @param  string $suffix
     * @return mixed
     */
    protected function nameFromClass($class, string $suffix = null)
    {
        $name = $this->convertCase($class);

        return Str::endsWith($name, $suffix)
            ? substr($name, 0, strlen($suffix) * -1)
            : $name;
    }

    /**
     * Return the namespace from array of classes.
     *
     * @param  array $classes
     * @return string
     */
    protected function buildNamespace(array $classes)
    {
        // Transform all class names.
        array_walk($classes, function (&$class, $key) {

            // Determine wheter class starts with backslash.
            $appends = starts_with($class, '\\');

            // Remove backslash from class and studly case it.
            $class = Str::studly($this->removeBackslash($class));

            if ($key == 0 && $appends) {

                // Appends a backslash for first class if required.
                $class = '\\'.$class;
            }
        });

        return implode('\\', $classes);
    }

    /**
     * Return the class basename to camel case.
     *
     * @param  mixed $class
     * @return string
     */
    protected function classToCamelCase($class)
    {
        return Str::camel($class);
    }

    /**
     * Return the class basename to snake case.
     *
     * @param  mixed $class
     * @return string
     */
    protected function classToSnakeCase($class)
    {
        return Str::snake($class);
    }

    /**
     * Remove the backslash on start of class.
     *
     * @param  string $class
     * @return string
     */
    protected function removeBackslash(string $class)
    {
        if (starts_with($class, '\\')) {

            // Get the class without backslash.
            $class = str_after($class, '\\');
        }

        return $class;
    }

    /**
     * Convert the class basenmae.
     *
     * @param  string|object $class
     * @return string
     */
    protected function convertCase($class)
    {
        $basename = class_basename($class);

        if ($this->camelCase) {
            return $this->classToCamelCase($basename);
        }

        return $this->classToSnakeCase($basename);
    }
}
