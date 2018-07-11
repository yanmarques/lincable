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
<<<<<<< HEAD

=======
        
>>>>>>> 4c8a26f5a973bebf5b69c343119e2161a489f17b
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
<<<<<<< HEAD

=======
        
>>>>>>> 4c8a26f5a973bebf5b69c343119e2161a489f17b
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
