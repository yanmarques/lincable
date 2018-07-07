<?php

namespace Tests\Lincable;

use Lincable\UrlConf;
use Lincable\UrlCompiler;
use Lincable\UrlGenerator;
use Lincable\Parsers\Parser;
use Lincable\Parsers\Options;
use PHPUnit\Framework\TestCase;
use Lincable\Parsers\ColonParser;
use Illuminate\Support\Collection;
use Illuminate\Container\Container;
use Illuminate\Database\Eloquent\Model;
use Lincable\Exceptions\NoModelConfException;
use Lincable\Contracts\Parsers\ParameterInterface;

class UrlGeneratorTest extends TestCase
{
    private $generator;

    public function setUp()
    {
        // Create the UrlConf for classes.
        $urlConf = new UrlConf('Tests\Lincable');

        // Push the foo class with the url configuration.
        $urlConf->push(Foo::class, 'foo/:id/bar/:foo_id/baz/:bar_id');

        // Add the colon parser to collection.
        $parsers = collect();
        $parsers->push(new ColonParser(new Container));
        
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
        $fooParser = new FooParser(new Container);

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
}

class FooParser extends Parser
{
    public function __construct()
    {
        $this->boot(new Container);
    }

    /**
     * @inheritdoc
     */
    protected function parseMatches(array $matches): ParameterInterface
    {
        return new Options(head($matches));
    }

    /**
     * @inheritdoc
     */
    protected function getDynamicPattern(): string
    {
        return '/^foo@([a-zA-Z]+)$/';
    }
}

class Foo extends Model
{
    protected $fillable = [
        'id',
        'foo_id',
        'bar_id'
    ];
}
