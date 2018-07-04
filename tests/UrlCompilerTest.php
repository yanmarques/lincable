<?php

namespace Tests\Lincable;

use Lincable\UrlCompiler;
use PHPUnit\Framework\TestCase;
use Lincable\Parsers\ColonParser;
use Illuminate\Container\Container;

class UrlCompilerTest extends TestCase
{
    private $compiler;

    public function setUp()
    {
        $parser = new ColonParser(new Container);
        $this->compiler = new UrlCompiler($parser);
    }

    /**
     * Should return the array with dynamic paramters of url.
     *
     * @return void
     */
    public function testThatCompileReturnTheArrayWithDynamicParameters()
    {
        $expected = ['foo', 'bar'];
        $result = $this->compiler->compile('example/:foo/test/:bar');
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return an empty array.
     *
     * @return void
     */
    public function testThatCompileReturnAnEmptyArray()
    {
        $expected = [];
        $result = $this->compiler->compile('foo/bar/baz');
        $this->assertEquals($expected, $result);
    }
}
