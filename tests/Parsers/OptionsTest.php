<?php

namespace Tests\Lincable\Parsers;

use Lincable\Parsers\Options;
use PHPUnit\Framework\TestCase;
use Lincable\Contracts\Parsers\ParameterInterface;

class OptionsTest extends TestCase
{
    /**
     * Should return an instance of ParameterInterface.
     * 
     * @return void
     */
    public function testThatInstanceImplementsParameterInterface()
    {
        $options = new Options('foo');
        $this->assertInstanceOf(
            ParameterInterface::class,
            $options 
        );
    }

    /**
     * Should return the name passed to constructor.
     * 
     * @return void
     */
    public function testThatGetValueReturnTheValueProvidedOnConstructor()
    {
        $expected = 'foo';
        $options = new Options($expected);
        $result = $options->getValue();
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return an empty array when no params passed on constructor.
     * 
     * @return void
     */
    public function testThatGetParamsReturnAnEmptyArrayWithNoParamsOnConstructor()
    {
        $expected = [];
        $options = new Options('foo');
        $result = $options->getParams();
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return the string in array.
     * 
     * @return void
     */
    public function testThatGetParamsReturnTheArrayVersionOfStringPassedOnConstructor()
    {
        $expected = ['bar'];
        $options = new Options('foo', 'bar');
        $result = $options->getParams();
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return the exact same expected array. 
     * 
     * @return void
     */
    public function testThatGetParamsReturnExactTheSameArray()
    {
        $expected = ['bar', 'baz'];
        $options = new Options('foo', $expected);
        $result = $options->getParams();
        $this->assertEquals($expected, $result);
    }
}