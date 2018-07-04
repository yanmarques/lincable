<?php

namespace Lincable\Contracts\Compilers;

interface Compiler
{
    /**
     * Get all dynamic parameters on url.
     *
     * @param  string $url
     * @return array
     */
    public function compile(string $url): array;

    /**
     * Return all url fragments.
     *
     * @param  string $url
     * @return array
     */
    public function parseUrlFragments(string $url): array;
}
