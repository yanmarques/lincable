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
     * Get all dynamic parameters on url.
     *
     * @param  string $url
     * @return array
     */
    public function compile(string $url): array
    {
        $parameters = $this->parseUrlFragments($url);

        $dynamicParameters = array_filter($parameters, function ($parameter) {

            // Determine wheter the parameter is dynamic on parser
            // and should be kept.
            return $this->parser->isParameterDynamic($parameter);
        });

        return array_flatten(array_map(function ($parameter) {

            // Return the matches for the dynamic parameter.
            return $this->parser->getMatches($parameter);
        }, $dynamicParameters));
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
}
