<?php

namespace Lincable\Concerns;

use Illuminate\Support\Str;

trait BuildClassnames
{
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
        $name = $this->classToCamelCase($class);
            
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
        return array_reduce($classes, function ($namespace, $class) {
            $studlyClass = Str::studly($class);
            return $namespace.Str::start($studlyClass, '\\');
        });
    }

    /**
     * Return the class basename to camel case.
     *
     * @param  mixed $class
     * @return string
     */
    protected function classToCamelCase($class)
    {
        return Str::camel(class_basename($class));
    }
}
