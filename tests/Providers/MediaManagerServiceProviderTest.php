<?php

namespace Tests\Lincable;

use Closure;
use Lincable\MediaManager;
use Lincable\UrlGenerator;
use Tests\Lincable\TestCase;
use Tests\Lincable\Models\Media;
use Tests\Lincable\Parsers\DotParser;
use Tests\Lincable\Models\Foo as FooModel;
use Tests\Lincable\Formatters\FooFormatter;
use Lincable\Eloquent\Events\UploadSuccess;
use Illuminate\Filesystem\FilesystemAdapter;
use Lincable\Providers\MediaManagerServiceProvider;
use Lincable\Exceptions\ConfModelNotFoundException;

class MediaManagerServiceProviderTest extends TestCase
{
    private $provider;

    public function setUp()
    {
        parent::setUp();

        $this->provider = new MediaManagerServiceProvider($this->app);
    }

    /**
     * Should register the service provider and make the media manager available
     * on container.
     *
     * @return void
     */
    public function testRegisterWillResolveMediaManagerSingleton()
    {
        $this->provider->register();
        
        $this->assertInstanceOf(MediaManager::class, app(MediaManager::class));
    }

    /**
     * Should register the upload subscriber to events.
     *
     * @return void
     */
    public function testBootSubscriberUploadSubscriberFromConfiguration()
    {
        $this->provider->boot();

        $subscribers = $this->app['events']->getListeners(UploadSuccess::class);
        
        $this->assertInstanceOf(
            Closure::class,
            head($subscribers)
        );
    }

    /**
     * Should boot the lincable configuration.
     *
     * @return void
     */
    public function testBootWillRegisterTheConfigurationFile()
    {
        $this->setDisk('s3');
        $this->provider->boot();
        $config = __DIR__.'/../../config/lincable.php';
        
        $this->assertEquals(require $config, $this->app['config']['lincable']);
    }

    /**
     * Should change the parsers on url generator from configuration.
     *
     * @return void
     */
    public function testParsersWithCustomParsersConfiguration()
    {
        $parsers = [
            DotParser::class => [
                FooFormatter::class
            ]
        ];
        
        $this->app['config']->set('lincable.parsers', $parsers);
        $defaultParsers = config('lincable.default_parsers');

        $urlParsers = app(UrlGenerator::class)->getParsers();
        $urlParsers = $urlParsers->map(function ($parser) {
            return [
                get_class($parser) => $parser->getFormatters()->values()->toArray()
            ];
        })->collapse();
        
        $this->assertEquals(array_merge($defaultParsers, $parsers), $urlParsers->toArray());
    }

    /**
     * Should return a local adapter.
     *
     * @return void
     */
    public function testGetDiskWithNewConfiguration()
    {
        $this->setDisk('local');
        $disk = app(MediaManager::class)->getDisk();
        $this->assertInstanceOf(FilesystemAdapter::class, $disk);
    }

    /**
     * Should create the url conf setting the model with dot notation.
     *
     * @return void
     */
    public function testModelsConfigurationWithDotNotation()
    {
        $this->registerModels();

        $expected = [
            'models.foo' => '/bar/baz'
        ];
        
        $this->app['config']->set('lincable.urls', $expected);

        $conf = app(UrlGenerator::class)->getUrlConf();
        
        $this->assertEquals($conf->get(FooModel::class), head($expected));
    }

    /**
     * Should create the url conf with the root configuration.
     *
     * @return void
     */
    public function testModelsConfigurationWithRoot()
    {
        $this->registerModels();

        $expected = [
            'models.foo' => 'bar/baz'
        ];
        
        $root = 'foo';

        $this->app['config']->set('lincable.root', $root);
        $this->app['config']->set('lincable.urls', $expected);

        $conf = app(UrlGenerator::class)->getUrlConf();
        
        $this->assertEquals($conf->get(FooModel::class), $root.'/'.head($expected));
    }
    
    /**
     * Should throw an exception when has invalid model on configuration.
     *
     * @return void
     */
    public function testConfiguringInvalidModelThrowException()
    {
        $this->app['config']->set('lincable.urls', ['Example' => 'baz']);
        
        $this->expectException(ConfModelNotFoundException::class);
        
        app(MediaManager::class);
    }

    /**
     * Should throw an exception when has invalid model on configuration.
     *
     * @return void
     */
    public function testConfiguringNonParserOnParsersThrowException()
    {
        $this->app['config']->set('lincable.default_parsers', []);
        $this->app['config']->set('lincable.parsers', []);

        $this->expectException(\RuntimeException::class);
        
        app(MediaManager::class);
    }

    /**
     * Should generate the url for the model.
     *
     * @return void
     */
    public function testGenerateUrlForConfiguredModel()
    {
        $this->registerModels();

        $root = 'root';

        $this->app['config']->set('lincable.root', $root);
        $this->app['config']->set('lincable.urls', [
            FooModel::class => 'foo/:year/:id'
        ]);

        $model = new FooModel(['id' => 123]);

        $urlGenerator = app(UrlGenerator::class);
        $url = $urlGenerator->forModel($model)->generate();
        
        $this->assertEquals(
            sprintf('%s/foo/%s/%s', $root, now()->year, $model->id),
            $url
        );
    }

    /**
     * Should override formatter name by class aliasing 
     * a name as the key on array.
     * 
     * @return void
     */
    public function testWillRegisterKeyedFormatters()
    {
        $this->registerModels();

        $this->app['config']->set('lincable.default_parsers', [
            DotParser::class => [
                'bar' => FooFormatter::class
            ]
        ]);

        $this->app['config']->set('lincable.urls', [
            'models.media' => 'baz.bar'
        ]);

        $url = $this->app->make(MediaManager::class)->newLink(new Media);

        $this->assertEquals('/foo', $url);
    }

    /**
     * Register the testing model's namespace.
     * 
     * @return void
     */
    protected function registerModels() 
    {
        $this->app['config']->set('lincable.models.namespace', 'Tests\Lincable');
    }
}
