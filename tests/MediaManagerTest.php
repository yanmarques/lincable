<?php

namespace Tests\Lincable;

use Exception;
use Carbon\Carbon;
use Lincable\UrlCompiler;
use Lincable\MediaManager;
use Lincable\UrlGenerator;
use Lincable\Parsers\Parser;
use Lincable\Parsers\Options;
use Illuminate\Config\Repository;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;
use Illuminate\Filesystem\FilesystemAdapter;
use Lincable\Contracts\Parsers\ParameterInterface;
use Lincable\Exceptions\ConfModelNotFoundException;

class MediaManagerTest extends TestCase
{
    /**
     * Should return the url generator with the configuration loaded.
     *
     * @return void
     */
    public function testBuildUrlGeneratorWithCompiler()
    {
        $this->setDisk('s3');
        $urlGenerator = $this->createMediaManager()->buildUrlGenerator();
        $this->assertInstanceOf(UrlGenerator::class, $urlGenerator);
    }

    /**
     * Should return a local adapter.
     *
     * @return void
     */
    public function testGetDiskWithNewConfiguration()
    {
        $this->setDisk('foo');
        $disk = $this->createMediaManager()->getDisk();
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
        
        $this->setConfig('lincable.parsers', $parsers);
        $defaultParsers = config('lincable.default_parsers');
        $this->setDisk('s3');

        $urlParsers = $this->createMediaManager()->buildUrlGenerator()->getParsers();
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
            'lincable.fooModel' => '/bar/baz'
        ];
        
        $this->setConfig('lincable.models.namespace', 'tests');
        $this->setConfig('lincable.urls', $expected);
        $this->setDisk('s3');

        $conf = $this->createMediaManager()->getUrlConf();
        
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
            'lincable.fooModel' => 'bar/baz'
        ];
        $root = 'foo';
        $this->setConfig('lincable.models.namespace', 'tests');
        $this->setConfig('lincable.root', $root);
        $this->setConfig('lincable.urls', $expected);
        $this->setDisk('s3');

        $conf = $this->createMediaManager()->getUrlConf();
        
        $this->assertEquals($conf->get(FooModel::class), $root.'/'.head($expected));
    }
    
    /**
     * Should throw an exception when has invalid model on configuration.
     *
     * @return void
     */
    public function testConfiguringInvalidModelThrowException()
    {
        $this->setConfig('lincable.urls', ['Example' => 'baz']);
        $this->setDisk('s3');
        
        $this->expectException(ConfModelNotFoundException::class);
        $this->createMediaManager();
    }

    /**
     * Should throw an exception when has invalid model on configuration.
     *
     * @return void
     */
    public function testConfiguringNonParserOnParsersThrowException()
    {
        $this->setConfig('lincable.parsers', [FooFormatter::class]);
        $this->setDisk('s3');

        $this->expectException(Exception::class);
        $this->createMediaManager();
    }

    /**
     * Should generate the url for the model.
     *
     * @return void
     */
    public function testGenerateUrlForConfiguredModel()
    {
        $root = 'root';
        $this->setConfig('lincable.root', $root);
        $this->setConfig('lincable.urls', [
            FooModel::class => 'foo/:year/:id'
        ]);
        $this->setDisk('s3');

        $model = new FooModel([
            'id' => 123
        ]);

        $urlGenerator = $this->createMediaManager()->buildUrlGenerator();
        $url = $urlGenerator->forModel($model)->generate();
        
        $this->assertEquals(
            sprintf('%s/foo/%s/%s', $root, Carbon::now()->year, $model->id),
            $url
        );
    }

    /**
     * Return a media manager instance.
     *
     * @return \Lincable\MediaManager
     */
    protected function createMediaManager()
    {
        return new MediaManager(Container::getInstance(), new UrlCompiler);
    }
}

class DotParser extends Parser
{
    /**
     * Create a new class instance.
     *
     * @param  Illuminate\Contracts\Container\Container|null $app
     * @return void
     */
    public function __construct(Container $app = null)
    {
        $this->boot($app);
    }

    /**
     * @inheritdoc
     */
    protected function parseMatches(array $matches): ParameterInterface
    {
        return new Options(last($matches));
    }

    /**
     * @inheritdoc
     */
    protected function getDynamicPattern(): string
    {
        return '/^([a-zA-Z_]+)\.([a-zA-Z_]+)$/';
    }
}

class FooFormatter
{
    public function format()
    {
        return 'foo';
    }
}
