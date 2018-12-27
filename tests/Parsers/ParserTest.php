<?php

namespace Tests\Lincable\Parsers;

use Tests\Lincable\TestCase;
use Tests\Lincable\Formatters\Param;
use Tests\Lincable\Formatters\BarFormatter;
use Tests\Lincable\Formatters\FooFormatter;
use Tests\Lincable\Formatters\ArgumentFormatter;
use Tests\Lincable\Formatters\ALongNameFormatter;
use Lincable\Exceptions\NotDynamicOptionException;

class ParserTest extends TestCase
{
    private $parser;

    public function setUp()
    {
        parent::setUp();

        $this->parser = app(DotParser::class);
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
        $container = app();

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