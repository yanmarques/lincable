<?php

namespace Tests\Lincable;

use LogicException;
use Lincable\UrlCompiler;
use Lincable\Parsers\Parser;
use Lincable\Parsers\Options;
use PHPUnit\Framework\TestCase;
use Lincable\Parsers\ColonParser;
use Illuminate\Container\Container;
use Lincable\Contracts\Parsers\ParameterInterface;

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
        $this->compiler->getParser()->addFormatter(Bar::class);
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
        $parser = new FooParser;
        
        $parser->addFormatter(function () {
            return 'baz';
        }, 'bar');

        $this->compiler->setParser($parser);
        $expected = 'test/baz';
        $result = $this->compiler->compile('test/foo@bar');
        $this->assertEquals($expected, $result);
    }
}

class Bar
{
    public function format()
    {
        return 'bar';
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
