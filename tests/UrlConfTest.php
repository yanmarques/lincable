<?php

namespace Tests\Lincable;

use Lincable\UrlConf;
use Tests\Lincable\Models\Foo;
use Lincable\Exceptions\ConfModelNotFoundException;

class UrlConfTest extends TestCase
{
    private $urlConf;

    public function setUp()
    {
        parent::setUp();

        $this->urlConf = new UrlConf('Tests\Lincable\Models');
    }

    /**
     * Should has the key on conf.
     *
     * @return void
     */
    public function testThatHasReturnsTrueForValidConf()
    {
        $key = 'foo';
        $this->urlConf->push($key, 'bar');
        $this->assertTrue($this->urlConf->has(Foo::class));
    }

    /**
     * Should has the key on conf.
     *
     * @return void
     */
    public function testThatHasReturnsTrueForClassName()
    {
        $key = Foo::class;
        $this->urlConf->push($key, 'bar');
        $this->assertTrue($this->urlConf->has($key));
    }

    /**
     * Should NOT has the key on conf.
     *
     * @return void
     */
    public function testThatHasReturnsFalseForInvalidConf()
    {
        $this->urlConf->push('foo', 'bar');
        $this->assertFalse($this->urlConf->has('baz'));
    }

    /**
     * Should return the value associated to model.
     *
     * @return void
     */
    public function testThatGetReturnValueFromClass()
    {
        $key = 'foo';
        $expected = 'bar';
        $this->urlConf->push($key, $expected);
        $result = $this->urlConf->get(Foo::class);
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return null for not found class.
     *
     * @return void
     */
    public function testThatGetReturnNull()
    {
        $result = $this->urlConf->get(Foo::class);
        $this->assertNull($result);
    }

    /**
     * Should return bar as defualt value.
     *
     * @return void
     */
    public function testThatGetReturnBarAsDefaultValue()
    {
        $expected = 'bar';
        $result = $this->urlConf->get(Foo::class, $expected);
        $this->assertEquals($expected, $result);
    }

    /**
     * Should return an array with all configuration.
     *
     * @return void
     */
    public function testThatAllReturnAnArrayWithClassConf()
    {
        $expected = [Foo::class => 'bar'];
        $this->urlConf->push(Foo::class, 'bar');
        $result = $this->urlConf->all();
        $this->assertEquals($expected, $result);
    }

    /**
     * Should throws a ConfModelNotFoundException.
     *
     * @return void
     */
    public function testThatPushAddsNewConfiguration()
    {
        $this->expectException(ConfModelNotFoundException::class);
        $this->urlConf->push('baz', 'baz');
    }

    /**
     * Should change the already configured model class.
     *
     * @return void
     */
    public function testThatSetChangeTheConfiguration()
    {
        $expected = 'foo';
        $this->urlConf->push(Foo::class, $expected);
        $result = $this->urlConf->get(Foo::class);
        $this->assertEquals($expected, $result);

        $expected = 'test';
        $this->urlConf->set(Foo::class, 'test');
        $result = $this->urlConf->get(Foo::class);
        $this->assertEquals($expected, $result);
    }

    /**
     * Should set an array of configuration.
     *
     * @return void
     */
    public function testSetWithArrayOfParameters()
    {
        $this->urlConf->push(Foo::class, 'foo');
        $expected = [Foo::class => 'test'];
        $this->urlConf->set($expected);
        $result = $this->urlConf->all();
        $this->assertEquals($expected, $result);
    }
}