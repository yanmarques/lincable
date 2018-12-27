<?php

namespace Tests\Lincable;

use Exception;
use Lincable\MediaManager;
use Lincable\UrlGenerator;
use Lincable\Parsers\Parser;
use Lincable\Parsers\Options;
use Tests\Lincable\Parsers\DotParser;
use Tests\Lincable\Models\Foo as FooModel;
use Tests\Lincable\Formatters\FooFormatter;
use Illuminate\Filesystem\FilesystemAdapter;
use Lincable\Exceptions\ConfModelNotFoundException;

class MediaManagerTest extends TestCase
{
    public function setUp()
    {
        parent::setUp();

        app('config')->set('lincable.models.namespace', 'Tests\Lincable');
    }

    /**
     * Should return the url generator with the configuration loaded.
     *
     * @return void
     */
    public function testBuildUrlGeneratorWithCompiler()
    {
        $urlGenerator = app(MediaManager::class)->buildUrlGenerator();
        $this->assertInstanceOf(UrlGenerator::class, $urlGenerator);
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

        $urlParsers = app(MediaManager::class)->buildUrlGenerator()->getParsers();
        $urlParsers = $urlParsers->map(function ($parser) {
            return [
                get_class($parser) => $parser->getFormatters()->values()->toArray()
            ];
        })->collapse();
        
        $this->assertEquals(array_merge($defaultParsers, $parsers), $urlParsers->toArray());
    }

    /**
     * Should create the url conf setting the model with dot notation.
     *
     * @return void
     */
    public function testModelsConfigurationWithDotNotation()
    {
        $expected = [
            'models.foo' => '/bar/baz'
        ];
        
        $this->app['config']->set('lincable.urls', $expected);

        $conf = app(MediaManager::class)->getUrlConf();
        
        $this->assertEquals($conf->get(FooModel::class), head($expected));
    }

    /**
     * Should create the url conf with the root configuration.
     *
     * @return void
     */
    public function testModelsConfigurationWithRoot()
    {
        $expected = [
            'models.foo' => 'bar/baz'
        ];
        
        $root = 'foo';

        $this->app['config']->set('lincable.root', $root);
        $this->app['config']->set('lincable.urls', $expected);

        $conf = app(MediaManager::class)->getUrlConf();
        
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
        $this->app['config']->set('lincable.parsers', [FooFormatter::class]);

        $this->expectException(Exception::class);
        
        app(MediaManager::class);
    }

    /**
     * Should generate the url for the model.
     *
     * @return void
     */
    public function testGenerateUrlForConfiguredModel()
    {
        $root = 'root';

        $this->app['config']->set('lincable.root', $root);
        $this->app['config']->set('lincable.urls', [
            FooModel::class => 'foo/:year/:id'
        ]);

        $model = new FooModel(['id' => 123]);

        $urlGenerator = app(MediaManager::class)->buildUrlGenerator();
        $url = $urlGenerator->forModel($model)->generate();
        
        $this->assertEquals(
            sprintf('%s/foo/%s/%s', $root, now()->year, $model->id),
            $url
        );
    }
}
