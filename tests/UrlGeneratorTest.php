<?php

namespace Tests\Lincable;

use Lincable\UrlConf;
use Lincable\UrlCompiler;
use Lincable\UrlGenerator;
use Tests\Lincable\Models\Foo;
use Tests\Lincable\Models\Bar;
use Lincable\Parsers\ColonParser;
use Tests\Lincable\Parsers\FooParser;
use Lincable\Exceptions\NoModelConfException;

class UrlGeneratorTest extends TestCase
{
    private $generator;

    public function setUp()
    {
        parent::setUp();

        // Create the UrlConf for classes.
        $urlConf = new UrlConf('Tests\Lincable');

        // Push the foo class with the url configuration.
        $urlConf->push(Foo::class, 'foo/:id/bar/:foo_id/baz/:bar_id');

        // Add the colon parser to collection.
        $parsers = collect();
        $parsers->push(app(ColonParser::class));
        
        // Create the UrlCompiler.
        $compiler = new UrlCompiler($parsers->first());
        
        // Create the UrlGenerator.
        $this->generator = new UrlGenerator($compiler, $parsers, $urlConf);
    }

    /**
     * Should set the model for the generator.
     *
     * @return void
     */
    public function testThatForModelSetsTheModelToGenerator()
    {
        $expected = new Foo;
        $this->generator->forModel($expected);
        $this->assertEquals($expected, $this->generator->getModel());
    }

    /**
     * Should generate the url applying the model attributes parameters.
     *
     * @return void
     */
    public function testThatGenerateReturnTheUrlWithParamtersChanged()
    {
        $model = new Foo([
            'id' => 1,
            'foo_id' => 2,
            'bar_id' => 3
        ]);
        $expected = 'foo/1/bar/2/baz/3';
        $this->generator->forModel($model);
        $result = $this->generator->generate();
        $this->assertEquals($expected, $result);
    }

    /**
     * Should override the model attributes.
     *
     * @return void
     */
    public function testPassingCustomParametersToForModel()
    {
        $model = new Foo([
            'id' => 1,
            'foo_id' => 2,
            'bar_id' => 3
        ]);
        $expected = 'foo/5/bar/2/baz/3';
        $this->generator->forModel($model, ['id' => 5]);
        $result = $this->generator->generate();
        $this->assertEquals($expected, $result);
    }

    /**
     * Should throws NoModelConfException because model is not configured on UrlConf.
     *
     * @return void
     */
    public function testThatForModelThrowsNoModelConfException()
    {
        $this->expectException(NoModelConfException::class);
        $this->generator->forModel(new Bar);
    }

    /**
     * Should generate url for multiple parsers with multiple dynamic parameters.
     *
     * @return void
     */
    public function testGeneratorWithMultipleParsers()
    {
        $fooParser = app(FooParser::class);

        // Add a formatter bar that returns bar.
        $fooParser->addFormatter(function () {
            return 'bar';
        }, 'bar');

        $model = new Foo([
            'id' => 1,
            'foo_id' => 2,
            'bar_id' => 3
        ]);
        $expected = 'foo/1/bar/2/baz/bar';

        // Add parser to generator.
        $this->generator->getParsers()->push($fooParser);

        // Set a new url configuration to FooParser.
        $this->generator->getUrlConf()->set(Foo::class, 'foo/:id/bar/:foo_id/baz/foo@bar');
        
        $result = $this->generator->forModel($model)->generate();
        $this->assertEquals($expected, $result);
    }

    /**
     * Should use the parameter resolver
     *
     * @return void
     */
    public function testSetParameterResolverThatSum1ToIntegerParameters()
    {
        $resolver = function ($value) {
            return is_int($value) ? $value + 1 : $value;
        };

        $this->generator->setParameterResolver($resolver);

        $model = new Foo([
            'id' => 1,
            'foo_id' => 2,
            'bar_id' => 3
        ]);
        
        $expected = 'foo/2/bar/3/baz/4';
        $result = $this->generator->forModel($model)->generate();
        $this->assertEquals($expected, $result);
    }

    /**
     * Should set a globally parameter resolver for url generator that sums 1
     * values.
     *
     * @return void
     */
    public function testWithParameterResolverThatSubs1ToParameters()
    {
        $resolver = function ($value) {
            return  $value + 1;
        };

        UrlGenerator::withParameterResolver($resolver);

        $model = new Foo([
            'id' => 1,
            'foo_id' => 2,
            'bar_id' => 3
        ]);
        
        $expected = 'foo/2/bar/3/baz/4';
        $result = $this->generator->forModel($model)->generate();
        $this->assertEquals($expected, $result);
    }

    /**
     * Should set a globally parameter resolver for url generator that sums 1
     * values.
     *
     * @return void
     */
    public function testWithParameterResolverWithKeyArgument()
    {
        $resolver = function ($value, $key) {
            if ($key == 'id') {
                return  'changed';
            }

            return $value;
        };

        UrlGenerator::withParameterResolver($resolver);

        $model = new Foo([
            'id' => 1,
            'foo_id' => 2,
            'bar_id' => 3
        ]);
        
        $expected = 'foo/changed/bar/2/baz/3';
        $result = $this->generator->forModel($model)->generate();
        $this->assertEquals($expected, $result);
    }
}
