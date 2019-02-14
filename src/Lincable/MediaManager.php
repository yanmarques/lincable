<?php

namespace Lincable;

use Exception;
use Lincable\Parsers\Parser;
use Lincable\Contracts\Compilers\Compiler;
use Illuminate\Contracts\Container\Container;

class MediaManager
{
    /**
     * The container implementation.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;

    /**
     * The compiler implementation.
     *
     * @var \Lincable\Contracts\Compilers\Compiler
     */
    protected $compiler;

    /**
     * The model url configuration.
     *
     * @var \Lincable\UrlConf
     */
    protected $urlConf;

    /**
     * The disk for file storage.
     *
     * @var \Illuminate\Contracts\Filesystem\Filesystem
     */
    protected $disk;

    /**
     * The collection with the url parsers.
     *
     * @var \Illuminate\Support\Collection
     */
    protected $parsers;

    /**
     * Create a new class instance.
     *
     * @param  \Illuminate\Contracts\Container\Container $app
     * @return void
     */
    public function __construct(Container $app, Compiler $compiler)
    {
        $this->app = $app;
        $this->compiler = $compiler;
        $this->parsers = collect();
        $this->readConfig();
    }

    /**
     * Set the media url compiler.
     *
     * @param  \Illuminate\Contracts\Compilers\Compiler $compiler
     * @return this
     */
    public function setCompiler(Compiler $compiler)
    {
        $this->compiler = $compiler;
    }

    /**
     * Return the compiler class.
     *
     * @return \Illuminate\Contracts\Compilers\Compiler
     */
    public function getCompiler()
    {
        return $this->compiler;
    }

    /**
     * Return the disk storage.
     *
     * @return \Illuminate\Contracts\Filesystem\Filesystem
     */
    public function getDisk()
    {
        return $this->disk;
    }

    /**
     * Return the model url configuration.
     *
     * @return \Lincable\UrlConf
     */
    public function getUrlConf()
    {
        return $this->urlConf;
    }

    /**
     * Set the new disk to use.
     *
     * @param  string $disk
     * @return this
     */
    public function setDisk(string $disk)
    {
        $this->disk = $this->app['filesystem']->disk($disk);

        return $this;
    }

    /**
     * Set a root path for the urls.
     *
     * @param  string|null $root
     * @return this
     */
    public function setRoot(string $root = null)
    {
        $this->urlConf = $this->createUrlConfWithRoot($root);

        return $this;
    }

    /**
     * Add a new parser to the manager.
     *
     * @param  mixed $parser
     * @param  mixed $formatters
     * @return this
     */
    public function addParser($parser, $formatters = [])
    {
        if (is_string($parser)) {

            // Resolve the parser instance.
            $parser = $this->app->make($parser);
        }

        if (! $parser instanceof Parser) {
            $type = gettype($parser);

            // Force the parser to be a Parser class.
            throw new Exception("Parser must be a {Parser::class}, [{$type}] given.");
        }

        $this->parsers->push($parser->addFormatters($formatters));

        return $this;
    }

    /**
     * Return a url generator instance with the manager configuration.
     *
     * @return \Lincable\UrlGenerator
     */
    public function buildUrlGenerator()
    {
        return new UrlGenerator($this->compiler, $this->parsers, $this->urlConf);
    }

    /**
     * Read the configuration from container.
     *
     * @return void
     */
    protected function readConfig()
    {
        $this->setRoot($this->getConfig('root'));

        // Create the default parsers from config.
        $parsers = array_merge(
            $this->getConfig('default_parsers', []),
            $this->getConfig('parsers', [])
        );

        foreach ($parsers as $parser => $formatters) {
            $this->addParser($parser, $formatters);
        }

        // Create a new disk filesystem.
        $this->disk = $this->app['filesystem']->disk($this->getConfig('disk'));
    }

    /**
     * Return the configuration value.
     *
     * @param  string $key
     * @param  mixed  $default
     * @return mixed
     */
    protected function getConfig(string $key, $default = null)
    {
        return $this->app['config']["lincable.{$key}"] ?? $default;
    }

    /**
     * Create a new url conf with a root url.
     *
     * @param  string|null $root
     * @return \Licable\UrlConf
     */
    protected function createUrlConfWithRoot(string $root = null)
    {
        // Create the url conf class.
        $urlConf = new UrlConf($this->getConfig('models.namespace', ''));

        // Determine wheter should add a root url for each url in configuration.
        $hasRoot = $root && $root !== '';

        if ($hasRoot) {

            // Trim backslashs from right part of string.
            $root = rtrim($root, '\/');
        }

        // Add the url configuration.
        foreach ($this->getConfig('urls', []) as $model => $url) {
            $url = str_start($url, '/');

            // Push the new mode url configuration.
            $urlConf->push($model, $hasRoot ? $root.$url : $url);
        }

        return $urlConf;
    }
}
