<?php

namespace Tests\Lincable\Parsers;

use Lincable\Parsers\Parser;
use Lincable\Parsers\Options;
use PHPUnit\Framework\TestCase;
use Illuminate\Container\Container;
use Lincable\Contracts\Formatters\Formatter;
use Lincable\Exceptions\NotDynamicOptionException;
use Lincable\Contracts\Parsers\ParameterInterface;

class ParserTest extends TestCase
{
    private $parser;

    public function setUp()
    {
        $container = new Container;
        $this->parser = new DotParser($container);
    }

    /**
     * Should add a list of formatters.
     * 
     * @return void
     */
    public function testThatAddFormattersAddAnArray()
    {
        $expected = [
            'foo' => new FooFormatter,
            'bar' => BarFormatter::class
        ];
    
        $this->parser->addFormatters(array_values($expected));
        $this->assertEquals(
            $expected,
            $this->parser->getFormatters()->toArray()
        );
    }

    /**
     * Should find the formatter by its formatted name.
     * 
     * @return void
     */
    public function testThatFindFormatterReturnsTheClassName()
    {
        $expected = ALongNameFormatter::class;
        $this->parser->addFormatter($expected);
        $result = $this->parser->findFormatter('aLongName');
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return the year value.
     * 
     * @return void
     */
    public function testThatParseReturnsFoo()
    {
        $expected = 'foo';
        $this->parser->addFormatter(new FooFormatter);
        $result = $this->parser->parse('id.foo');
        $this->assertEquals(
            $expected, $result
        );
    }

    /**
     * Should return an array with the expected matches.
     * 
     * @return void
     */
    public function testThatgetMatchesReturnsArrayWithMatches()
    {
        $expected = ['id', 'bar'];
        $result = $this->parser->getMatches('id.bar');
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return false for parse because option is not dynamic.
     * 
     * @return void
     */
    public function testThatParseThrowsNotDynamicOptionException()
    {
        $this->expectException(NotDynamicOptionException::class);
        $this->parser->parse('useless');
    }

    /**
     * Should resolves the ArgumentFormatter instance with container.
     * 
     * @return void
     */
    public function testThatFindFormatterResolvesTheArgumentFormatter()
    {
        $expected = 'foo';
        $container = new Container;

        // Bind the Param class instance to container for further 
        // ArgumentFormatter class resolution.
        $container->instance(Param::class, new Param($expected));
        
        // Change the parser container class.
        $this->parser->setContainer($container);

        // Add the formatter class name. The class constructor
        // expects and Param class as argument, once we have bound
        // a Param instance on container, the dependency injection should
        // be performed.
        $this->parser->addFormatter(ArgumentFormatter::class);

        $result = $this->parser->parse('id.argument');

        $this->assertEquals($expected, $result);
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

class BarFormatter
{
    public function format()
    {
        return 'bar';
    }
}

class ALongNameFormatter implements Formatter
{
    public function format($value = null)
    {
        return 'A long text returned here';
    }
}

class ArgumentFormatter implements Formatter
{
    private $param;

    /**
     * Create a new class instance.
     * 
     * @param  \Tests\Param $param
     * @return void
     */
    public function __construct(Param $param)
    {
        $this->param = $param;
    }

    public function format($value = null)
    {
        return $this->param->getValue();
    }
}

class Param
{
    private $value;

    /**
     * Create a new class instance. 
     *
     * @param  mixed $value
     * @return void
     */
    public function __construct($value)
    {
        $this->value = $value;
    }

    /**
     * Return the value parameter.
     * 
     * @return mixed
     */
    public function getValue()
    {
        return $this->value;        
    }
}