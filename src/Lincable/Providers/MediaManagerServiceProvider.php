<?php

namespace Lincable\Providers;

use Lincable\UrlCompiler;
use Lincable\MediaManager;
use Illuminate\Support\ServiceProvider;

class MediaManagerServiceProvider extends ServiceProvider
{
    /**
     * Indicates if loading of the provider is deferred.
     *
     * @var bool
     */
    protected $defer = false;

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
    }

    /**
     * Register bindings in the container.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(MediaManager::class, function ($app) {
            return new MediaManager($app, new UrlCompiler);
        });

        $this->app['events']->subscribe($this->app['config']['lincable.upload_subscriber']);
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
}
