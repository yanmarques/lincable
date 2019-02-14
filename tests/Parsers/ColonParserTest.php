<?php

namespace Tests\Lincable\Parsers;

use LogicException;
use Tests\Lincable\TestCase;
use Lincable\Parsers\ColonParser;
use Tests\Lincable\Formatters\FooFormatter;
use Lincable\Exceptions\NotDynamicOptionException;

class ColonParserTest extends TestCase
{
    private $parser;

    public function setUp()
    {
        parent::setUp();

        $this->parser = app(ColonParser::class);
    }

    /**
     * Should return the expected string executed on callable.
     * 
     * @return void
     */
    public function testThatParseReturnTheStringWithCallableFormatter()
    {
        $expected = 'foo';
        $this->parser->addFormatter(function () {
            return 'foo';
        }, 'bar');
        $result = $this->parser->parse(':bar');
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return the expected string on format method.
     * 
     * @return void
     */
    public function testThatParseReturnFooWithFooFormatter()
    {
        $expected = 'foo';
        $this->parser->addFormatter(FooFormatter::class);
        $result = $this->parser->parse(':foo');
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return the expected string calling formatter with a custom name.
     * 
     * @return void
     */
    public function testThatAddProviderWithCustomFormatterName()
    {
        $expected = 'foo';
        $customName = 'customName';
        $this->parser->addFormatter(FooFormatter::class, $customName);
        $result = $this->parser->parse(":{$customName}");
        $this->assertEquals($expected, $result);
    }

    /**
     * Should throw an \LogicException because no formatter was found.
     * 
     * @return void
     */
    public function testThatParseThrowsAnLogicException()
    {
        $option = ':useless';
    
        $this->expectException(LogicException::class);
        $this->parser->parse($option);
    }
}