<?php
namespace App\Bag;

use Avris\Bag\Bag;
use Avris\Bag\BagHelper;
use Avris\Bag\Nested;
use Avris\Bag\Test\TestObject;

class NestedTest extends \PHPUnit_Framework_TestCase
{
    public function testGet()
    {
        $array = [
            'foo' => [
                'bar' => new TestObject(),
            ]
        ];

        $this->assertEquals($array, Nested::get($array, []));
        $this->assertEquals($array['foo'], Nested::get($array, ['foo']));
        $this->assertEquals($array['foo']['bar'], Nested::get($array, ['foo', 'bar']));
        $this->assertEquals('FUN', Nested::get($array, ['foo', 'bar', 'fun']));

        $this->assertSame(null, Nested::get($array, ['nope']));
        $this->assertSame('def', Nested::get($array, ['nope'], 'def'));
        $this->assertSame(null, Nested::get($array, ['foo', 'nope']));
    }

    public function testSet()
    {
        $array = [];

        Nested::set($array, ['foo', 'bar', 'baz'], 'ok');

        $this->assertEquals([
            'foo' => [
                'bar' => [
                    'baz' => 'ok',
                ],
            ],
        ], $array);
    }

    public function testSetEmpty()
    {
        $array = [];

        Nested::set($array, [], 'ok');

        $this->assertSame('ok', $array);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Array expected, string given
     */
    public function testSetNotArrayException()
    {
        $array = [
            'foo' => 'bar',
        ];

        Nested::set($array, ['foo', 'bar', 'baz'], 'ok');
    }
}
