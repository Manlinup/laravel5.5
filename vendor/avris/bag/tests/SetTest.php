<?php
namespace Avris\Bag;

use Avris\Bag\Bag;
use Avris\Bag\BagHelper;
use Avris\Bag\Test\TestObject;

class SetTest extends \PHPUnit_Framework_TestCase
{
    public function testConstructEmpty()
    {
        $set = new Set();
        $this->assertSame([], $set->all());
    }

    public function testConstructArray()
    {
        $set = new Set(['foo', 'bar', 'foo']);
        $this->assertSame(['foo', 'bar'], $set->all());
    }

    public function testConstructSet()
    {
        $set = new Set(['foo', 'bar', 'foo']);
        $newSet = new Set($set);

        $this->assertSame(['foo', 'bar'], $newSet->all());
    }

    public function testConstructBag()
    {
        $bag = new Bag(['x' => 'foo', 'y' => 'bar', 'z' => 'foo']);
        $set = new Set($bag);

        $this->assertSame(['foo', 'bar'], $set->all());
    }

    public function testConstructCallback()
    {
        $set = new Set(['Post', 'get', 'DELETE', 'GET'], 'strtoupper');

        $this->assertSame(['POST', 'GET', 'DELETE'], $set->all());
    }

    public function testCount()
    {
        $set = new Set(['Post', 'get', 'DELETE', 'GET'], 'strtoupper');

        $this->assertSame(3, $set->count());
        $this->assertSame(3, count($set));
    }

    public function testIsEmpty()
    {
        $this->assertTrue((new Set())->isEmpty());
        $this->assertFalse((new Set(['a']))->isEmpty());
    }

    public function testAdd()
    {
        $set = new Set(['raz', 'dwa', 'trzy']);
        $this->assertSame(3, $set->count());

        $set->add('cztery');
        $this->assertSame(4, $set->count());

        $set->add('raz');
        $this->assertSame(4, $set->count());

        $set->add(0);
        $this->assertSame(5, $set->count());

        $set->add('0');
        $this->assertSame(6, $set->count());
    }

    public function testAddMultiple()
    {
        $set = new Set(['raz', 'dwa', 'trzy']);
        $this->assertSame(3, $set->count());

        $set->addMultiple(['cztery', 'pięć', 'raz', 'raz']);
        $this->assertSame(5, $set->count());
    }

    public function testHas()
    {
        $set = new Set(['raz', '2']);

        $this->assertFalse($set->has(1));
        $this->assertFalse($set->has('1'));
        $this->assertTrue($set->has('raz'));
        $this->assertTrue($set->has('2'));
        $this->assertFalse($set->has(2));
        $this->assertFalse($set->has(3));
    }

    public function testDelete()
    {
        $set = new Set(['raz', '2']);

        $set->delete('raz');
        $this->assertSame(['2'], $set->all());

        $set->delete(2);
        $this->assertSame(['2'], $set->all());

        $set->delete('2');
        $this->assertSame([], $set->all());
    }

    public function testFirstLast()
    {
        $set = new Set();
        $this->assertFalse($set->first());
        $this->assertFalse($set->last());

        $set->add('raz');
        $this->assertSame('raz', $set->first());
        $this->assertSame('raz', $set->last());

        $set->add('dwa');
        $this->assertSame('raz', $set->first());
        $this->assertSame('dwa', $set->last());
    }

    public function testClear()
    {
        $set = new Set(['a', 'b', 'c']);

        $set->clear();
        $this->assertSame([], $set->all());
        $this->assertSame(0, $set->count());
    }

    public function testIterate()
    {
        $set = new Set(['a', 'b', 'a', 'c']);

        $output = '';
        foreach ($set as $value) {
            $output .= $value . '|';
        }

        $this->assertSame('a|b|c|', $output);
    }

    public function testJsonSerialize()
    {
        $set = new Set(['a', 'b', 'a', 'c']);
        $this->assertSame(['a', 'b', 'c'], $set->jsonSerialize());
        $this->assertSame('["a","b","c"]', json_encode($set));
    }

    public function testDebugInfo()
    {
        $set = new Set(['a', 'b', 'a', 'c']);
        $this->assertSame(['a', 'b', 'c'], $set->__debugInfo());
    }
}