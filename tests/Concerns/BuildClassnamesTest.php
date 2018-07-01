<?php

namespace Tests\Lincable\Concerns;

use PHPUnit\Framework\TestCase;
use Lincable\Concerns\BuildClassnames;

class BuildClassnamesTest extends TestCase
{
    use BuildClassnames;

    /**
     * Should find the class by name with class namespaces.
     * 
     * @return void
     */
    public function testThatFirstClassNameFindTheClass()
    {
        $expected = FooTester::class;
        
        $classes = [
            FooTester::class,
            BarTester::class
        ];

        $result = $this->firstClassName('foo', $classes, 'Tester');
        $this->assertEquals($expected, $result); 
    }

    /**
     * Shoudld find the class name from class objects.
     * 
     * @return void
     */
    public function testThatFirstClassNameFindTheClassFromObject()
    {
        $expected = new BarTester;

        $classes = [
            new FooTester,
            $expected
        ];

        $result = $this->firstClassName('bar', $classes, 'Tester');
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return the class basename to camel case.
     * 
     * @return void
     */
    public function testThatNameToCamelCaseCamelsTheName()
    {
        $expected = 'fooTester';
        $result = $this->nameToCamelCase(FooTester::class);
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return the object basename to camel case.
     * 
     * @return void
     */
    public function testThatNameToCamelCaseCamelsTheClassBaseName()
    {
        $expected = 'barTester';
        $result = $this->nameToCamelCase(new BarTester);
        $this->assertEquals($expected, $result);
    }
}

class FooTester
{
    //
}

class BarTester
{
    //
}