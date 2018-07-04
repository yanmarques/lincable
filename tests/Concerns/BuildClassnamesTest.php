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
}