<?php

namespace Tests\Lincable;

use LogicException;
use Lincable\UrlCompiler;
use Lincable\Parsers\ColonParser;
use Tests\Lincable\Parsers\FooParser;
use Tests\Lincable\Formatters\BarFormatter;

class UrlCompilerTest extends TestCase
{
    private $compiler;

    public function setUp()
    {
        parent::setUp();

        $parser = app(ColonParser::class);

        $this->compiler = new UrlCompiler($parser);
    }

    /**
     * Should return the array with dynamic paramters of url.
     *
     * @return void
     */
    public function testThatCompileReturnTheArrayWithDynamicParameters()
    {
        $expected = [['foo'], ['bar']];
        $result = $this->compiler->parseDynamics('example/:foo/test/:bar');
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return the url compiled with the dynamic parametters formatted.
     *
     * @return void
     */
    public function testThatCompileReturnTheUrlCompiled()
    {
        $expected = 'foo/bar';
        $this->compiler->getParser()->addFormatter(BarFormatter::class);
        $result = $this->compiler->compile('foo/:bar');
        $this->assertEquals($expected, $result);
    }

    /**
     * Should expect and LogicException because no formatter was found.
     *
     * @return void
     */
    public function testThatCompileReturn()
    {
        $this->expectException(LogicException::class);
        $this->compiler->compile('foo/:bar');
    }

    /**
     * Should return the same url passed due to any formatter.
     *
     * @return void
     */
    public function testThatCompileReturnTheExactSameUrlPassed()
    {
        $expected = 'foo/bar/@baz';
        $result = $this->compiler->compile($expected);
        $this->assertEquals($expected, $result);
    }

    /**
     * Should change the parser and compile the url for new parser.
     *
     * @return void
     */
    public function testThatSetParserChangesTheParserUsedOnCompiler()
    {
        $parser = app(FooParser::class);
        
        $parser->addFormatter(function () {
            return 'baz';
        }, 'bar');

        $this->compiler->setParser($parser);
        $expected = 'test/baz';
        $result = $this->compiler->compile('test/foo@bar');
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return true for dynamic url.
     *
     * @return void
     */
    public function testThatHasDynamicsReturnTrueForDynamicUrl()
    {
        $result = $this->compiler->hasDynamics(':foo');
        $this->assertTrue($result);
    }

    /**
     * Should return false for not dynamic curl.
     *
     * @return void
     */
    public function testThatHasDynamicsReturnFalseForNotDynamicUrl()
    {
        $result = $this->compiler->hasDynamics('foo/baz');
        $this->assertFalse($result);
    }

    /**
     * Should instantiate the compiler without arguments.
     *
     * @return void
     */
    public function testNewInstanceWithNullArgumentOnConstructor()
    {
        $compiler = new UrlCompiler;
        $this->assertInstanceof(\Lincable\Contracts\Compilers\Compiler::class, $compiler);
    }

    /**
     * Should throw an exception because parser is null.
     *
     * @return  void
     */
    public function testGetParserWithNullParserThrowException()
    {
        $compiler = new UrlCompiler;
        $this->expectException(\Exception::class);
        $compiler->getParser();
    }
}
