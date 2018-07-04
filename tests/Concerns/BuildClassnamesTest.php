<?php

namespace Tests\Lincable\Concerns;

use PHPUnit\Framework\TestCase;
use Lincable\Concerns\BuildClassnames;

class BuildClassnamesTest extends TestCase
{
    use BuildClassnames;

    /**
     * Should return the class basename to camel case.
     *
     * @return void
     */
    public function testThatGetNameReturnClassToCamelCase()
    {
        $expected = 'fooClass';
        $result = $this->nameFromClass('Bar\Baz\FooClass');
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return the class basename to camel case without
     * the suffix passed.
     *
     * @return void
     */
    public function testThatGetNameReturnClassToCamelCaseWithoutSuffix()
    {
        $suffix = 'AnyUselessSuffix';
        $expected = 'fooClass';
        $result = $this->nameFromClass('Bar\Baz\FooClass'.$suffix, $suffix);
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return the correct namespace.
     *
     * @return void
     */
    public function testThatBuildNamespaceReturnsTheNamespaceSeparatedByDoubleBackslash()
    {
        $expected = '\\The\\Foo\\BarNamespace';
        $classes = ['the', 'foo', 'barNamespace'];
        $result = $this->buildNamespace($classes);
        $this->assertEquals($expected, $result);
    }

    /**
     * Should remove backslash from start.
     *
     * @return void
     */
    public function testThatRemoveBackslashRemoveBackslashFromStart()
    {
        $expected = 'foo';
        $result = $this->removeBackslash('\\'.$expected);
        $this->assertEquals($expected, $result);
    }
}
