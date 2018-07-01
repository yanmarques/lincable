<?php

namespace Tests\Lincable\Parsers;

use Carbon\Carbon;
use Lincable\Formatters;
use PHPUnit\Framework\TestCase;
use Lincable\Parsers\ColonParser;
use Illuminate\Container\Container;
use Lincable\Exceptions\NotDynamicOptionException;

class ColonParserTest extends TestCase
{
    private $parser;

    public function setUp()
    {
        $this->parser = new ColonParser; 
        $this->parser->setContainer(new Container);
        $this->parser->setFormatters(collect([
            Formatters\YearFormatter::class,
            Formatters\MonthFormatter::class,
            Formatters\DayFormatter::class,
            Formatters\RandomFormatter::class,
            Formatters\TimestampsFormatter::class,
        ]));
    }

    /**
     * Should return the year from dynamic parameter.
     * 
     * @return void
     */
    public function testThatParseReturnsTheCurrentYear()
    {
        $expected = Carbon::now()->year;
        $result = $this->parser->parse('@foo<year>');
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return the month from dynamic parameter.
     * 
     * @return void
     */
    public function testThatParseReturnsTheCurrentMonth()
    {
        $expected = Carbon::now()->month;
        $result = $this->parser->parse('@foo<month>');
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return the year from dynamic parameter.
     * 
     * @return void
     */
    public function testThatParseReturnsTheCurrentDay()
    {
        $expected = Carbon::now()->day;
        $result = $this->parser->parse('@foo<day>');
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return a random string with length of 32. 
     * 
     * @return void
     */
    public function testThatParseReturnsARandomString()
    {
        $result = $this->parser->parse('@foo<random>');
        $this->assertTrue(is_string($result));
        $this->assertTrue(strlen($result) == 32);
    }

    /**
     * Should return a random string with the specified length. 
     * 
     * @return void
     */
    public function testThatParseReturnsARandomStringWithCustomLength()
    {
        $length = 10;
        $result = $this->parser->parse("@foo<random:$length>");
        $this->assertTrue(is_string($result));
        $this->assertTrue(strlen($result) == $length);
    }

    /**
     * Should throw an exception indicating option is not dynamic.
     * 
     * @return void
     */
    public function testThatParseThrowsNotDynamicOptionException()
    {
        $this->expectException(NotDynamicOptionException::class);
        $this->parser->parse('@useless<foo');
    }

    /**
     * Should recognize the parameter is dynamic, but the parse returns
     * false for no formatter option.
     * 
     * @return void
     */
    public function testThatParseRecognizesTheDynamicParameterWithNoFormatter()
    {
        $option = '@useless';
        $this->assertFalse($this->parser->parse($option));
        $this->assertTrue($this->parser->isParameterDynamic($option));
    }

    /**
     * Should return a timestamps. 
     * 
     * @return void
     */
    public function testThatParseReturnsATimestamps()
    {
        $expected = sha1(Carbon::now()->timestamp);
        $result = $this->parser->parse('@foo<timestamps>');
        $this->assertEquals($expected, $result);
    }
}