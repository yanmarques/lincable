<?php

namespace Lincable\Contracts\Compilers;

use Lincable\Parsers\Parser;

interface Compiler
{
    /**
     * Compile a given url through the parser.
     *
     * @param  string $url
     * @return string
     */
    public function compile(string $url): string;

    /**
     * Get all dynamic parameters on url based on parser.
     *
     * @param  string $url
     * @return array
     */
    public function parseDynamics(string $url): array;

    /**
     * Determine wheter the url has dynamic parameters.
     *
     * @param  string $url
     * @return bool
     */
    public function hasDynamics(string $url): bool;

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
