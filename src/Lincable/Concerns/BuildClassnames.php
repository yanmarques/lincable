<?php

namespace Lincable\Concerns;

use Illuminate\Support\Str;

trait BuildClassnames
{
    /**
     * Return the first item where the class matches the 
     * formated class name. 
     *
     * @param  string $name
     * @param  array  $classes
     * @param  string $suffix
     * @return mixed
     */
    protected function firstClassName(string $name, array $classes, string $suffix)
    {
        return array_first($classes, function ($class) use ($name, $suffix) {
            $class = $this->nameToCamelCase($class);            
            
            return Str::endsWith($class, $suffix)
                ? $name === substr($class, 0, strlen($suffix) * -1)
                : $name === $class;
        });
    }

    /**
     * Return the class name to camel case.
     * 
     * @param  mixed $class
     * @return string
     */
    protected function nameToCamelCase($class)
    {
        return Str::camel(class_basename($class));
    }
}