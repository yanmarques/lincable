<?php

namespace Lincable\Providers;

use Lincable\UrlConf;
use Lincable\UrlGenerator;
use Lincable\MediaManager;
use Lincable\Http\FileRequest;
use Illuminate\Support\ServiceProvider;
use Lincable\Contracts\Compilers\Compiler;

class MediaManagerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = true;

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__.'/../../../config/lincable.php';

        $this->publishes([
            $configPath => config_path('lincable.php'),
        ]);

        $this->mergeConfigFrom($configPath, 'lincable');

        $this->app['events']->subscribe($this->app['config']['lincable.upload_subscriber']);
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->registerCompiler();
        $this->registerUrlGenerator();
        $this->registerMediaManager();
    }

    /**
     * Get the services provided by the provider.
     *
     * @return array
     */
    public function provides()
    {
        return [MediaManager::class];
    }

    /**
     * Register the media manager singleton.
     *
     * @return void
     */
    public function registerMediaManager()
    {
        $this->app->singleton(MediaManager::class, function ($app) {
            return new MediaManager($app, $app->make(UrlGenerator::class));
        });
    }

    /**
     * Regiter the compiler default implementation.
     *
     * @return void
     */
    protected function registerCompiler()
    {
        $this->app->bind(Compiler::class, \Lincable\UrlCompiler::class);
    }

    /**
     * Create a new url conf with an optional root name.
     *
     * @return \Licable\UrlConf
     */
    protected function createUrlConf()
    {
        // Create the url conf class.
        $urlConf = new UrlConf(config('lincable.models.namespace', ''));

        $root = config('lincable.root', '');
        $urls = config('lincable.urls');
        
        // Determine wheter a root is present for each url and trim 
        // backslashs from right part of string.
        if ($root !== '') {
            $root = str_finish(ltrim($root, '/'), '/');
        }

        // Add the new url configuration.
        foreach ($urls as $model => $url) {
            $urlConf->push($model, $root.ltrim($url, '/'));
        }

        return $urlConf;
    }

    /**
     * Register the url generator singleton. 
     *
     * @return void
     */
    public function registerUrlGenerator()
    {
        $this->app->singleton(UrlGenerator::class, function ($app) {
            return new UrlGenerator(
                $app->make(Compiler::class), 
                $this->createParsers(), 
                $this->createUrlConf()
            );
        });
    }

    /**
     * Create the registered parsers and respective formatters.
     * 
     * @return mixed
     */
    protected function createParsers() 
    {
        // Create the default parsers from config.
        $registeredParsers = collect(array_merge(
            config('lincable.default_parsers', []),
            config('lincable.parsers', [])
        ));

        return $registeredParsers->map(function ($formatters, $parser) {
            return tap($this->app->make($parser), function ($parser) use ($formatters) {
                $parser->addFormatters($formatters);
            });
        });
    }
}
