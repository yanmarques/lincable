<?php

namespace Lincable\Contracts\Compilers;

use Lincable\Parsers\Parser;

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

    /**
     * Build an url from array fragments.
     *
     * @param  array $fragments
     * @return string
     */
    public function buildUrlFragments(array $fragments): string;

    /**
     * Set the parser used on compiler.
     *
     * @param  \Lincable\Parsers\Parser $parser
     * @return void
     */
    public function setParser(Parser $parser);

    /**
     * Get the current parser used on compiler.
     *
     * @return \Lincable\Parsers\Parser
     */
    public function getParser(): Parser;
}
