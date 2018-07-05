<?php

namespace Lincable;

use Lincable\Parsers\Parser;
use Lincable\Contracts\Compilers\Compiler;

class UrlCompiler implements Compiler
{
    /**
     * The parser instance.
     *
     * @var \Lincable\Parsers\Parser
     */
    protected $parser;

    /**
     * Create a new class instance.
     *
     * @param  \Lincable\Parsers\Parser $parser
     * @return void
     */
    public function __construct(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Compile a given url through the parser.
     *
     * @param  string $url
     * @return string
     */
    public function compile(string $url): string
    {
        // Parse each fragment on url.
        $fragments = array_map(function ($fragment) {
            if ($this->parser->isParameterDynamic($fragment)) {

                // We assume the parameter fragment is dynamic for
                // parser, then we can parse it without receiving an exception
                // in case the fragment is not dynamic.
                return $this->parser->parse($fragment);
            }

            return $fragment;
        }, $this->parseUrlFragments($url));

        return $this->buildUrlFragments($fragments);
    }

    /**
     * Get all dynamic parameters on url based on parser.
     *
     * @return array
     */
    public function parseDynamics(string $url): array
    {
        $fragments = $this->parseUrlFragments($url);

        $dynamicParameters = array_filter($fragments, function ($parameter) {

            // Determine wheter the parameter is dynamic on parser
            // and should be kept.
            return $this->parser->isParameterDynamic($parameter);
        });

        return array_map(function ($parameter) {

            // Return the matches for the dynamic parameter.
            return $this->parser->getMatches($parameter);
        }, array_values($dynamicParameters));
    }

    /**
     * Determine wheter the url has dynamic parameters.
     *
     * @param  string $url
     * @return bool
     */
    public function hasDynamics(string $url): bool
    {
        return ! empty(array_values($this->parseDynamics($url)));
    }

    /**
     * Return all url fragments.
     *
     * @param  string $url
     * @return array
     */
    public function parseUrlFragments(string $url): array
    {
        return explode('/', $url);
    }

    /**
     * Build an url from array fragments.
     *
     * @param  array $fragments
     * @return string
     */
    public function buildUrlFragments(array $fragments): string
    {
        return implode('/', $fragments);
    }

    /**
     * Set the parser used on compiler.
     *
     * @param  \Lincable\Parsers\Parser $parser
     * @return void
     */
    public function setParser(Parser $parser)
    {
        $this->parser = $parser;
    }

    /**
     * Get the current parser used on compiler.
     *
     * @return \Lincable\Parsers\Parser
     */
    public function getParser(): Parser
    {
        return $this->parser;
    }
}
