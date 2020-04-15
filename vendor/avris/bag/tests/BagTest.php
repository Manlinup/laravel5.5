<?php
namespace App\Bag;

use Avris\Bag\Bag;
use Avris\Bag\BagHelper;
use Avris\Bag\Test\TestObject;

class BagTest extends \PHPUnit_Framework_TestCase
{
    /** @var array */
    protected $testArray;

    /** @var TestObject */
    protected $testObject;

    protected function setUp()
    {
        $this->testObject = new TestObject();

        $this->testArray = [
            'foo' => [
                'bar' => 'baz',
            ],
            'abc.def' => 'ghi',
            'obj' => $this->testObject,
        ];
    }

    public function testConstructEmpty()
    {
        $bag = new Bag();
        $this->assertSame([], $bag->all());
    }

    public function testConstructFromArray()
    {
        $bag = new Bag(['foo' => 'bar']);
        $this->assertSame(['foo' => 'bar'], $bag->all());
    }

    public function testConstructFromBag()
    {
        $bag = new Bag(['foo' => 'bar']);
        $newBag = new Bag($bag);

        $this->assertSame(['foo' => 'bar'], $newBag->all());
    }

    public function testConstructFromString()
    {
        $bag = new Bag('test');
        $this->assertSame([0 => 'test'], $bag->all());
    }

    public function testAll()
    {
        $bag = new Bag($this->testArray);

        $this->assertSame($this->testArray, $bag->all());
    }

    public function testKeys()
    {
        $bag = new Bag($this->testArray);

        $this->assertSame(['foo', 'abc.def', 'obj'], $bag->keys());
    }

    public function testCount()
    {
        $bag = new Bag($this->testArray);

        $this->assertSame(3, $bag->count());
        $this->assertSame(3, count($bag));
    }

    public function testIsEmpty()
    {
        $bag = new Bag($this->testArray);
        $this->assertFalse($bag->isEmpty());

        $bag = new Bag();
        $this->assertTrue($bag->isEmpty());
    }

    public function testGet()
    {
        $bag = new Bag($this->testArray);

        $this->assertSame(['bar' => 'baz'], $bag->get('foo'));
        $this->assertSame(null, $bag->get('foo.bar'));
        $this->assertSame(null, $bag->get('abc'));
        $this->assertSame('ghi', $bag->get('abc.def'));
        $this->assertSame($this->testObject, $bag->get('obj'));

        $this->assertSame(null, $bag->get('nonexistent'));
        $this->assertSame('def', $bag->get('nonexistent', 'def'));

        $this->assertSame($bag->get('foo'), $bag['foo']);
        $this->assertSame($bag('foo'), $bag['foo']);
    }

    public function testGetDeep()
    {
        $bag = new Bag($this->testArray);

        $this->assertSame(['bar' => 'baz'], $bag->getDeep('foo'));
        $this->assertSame('baz', $bag->getDeep('foo.bar'));
        $this->assertSame(null, $bag->get('abc'));
        $this->assertSame('ghi', $bag->get('abc.def'));
        $this->assertSame($this->testObject, $bag->get('obj'));

        $this->assertSame(null, $bag->get('nonexistent'));
        $this->assertSame('def', $bag->get('nonexistent', 'def'));
        $this->assertSame(null, $bag->get('non.existent'));
        $this->assertSame('def', $bag->get('non.existent', 'def'));
    }

    public function testSet()
    {
        $bag = new Bag($this->testArray);

        $bag->set('set', 'val');
        $this->assertSame(
            $this->testArray + ['set' => 'val'],
            $bag->all()
        );

        $bag['set'] = 'val2';
        $this->assertSame(
            $this->testArray + ['set' => 'val2'],
            $bag->all()
        );

        $bag[] = 'xxx';
        $this->assertSame(
            $this->testArray + ['set' => 'val2', 0 => 'xxx'],
            $bag->all()
        );

        $this->assertInstanceOf(Bag::class, $bag->set('a', 'b'));
    }

    public function testHas()
    {
        $bag = new Bag($this->testArray);

        $this->assertTrue($bag->has('foo'));
        $this->assertFalse($bag->has('nope'));

        $this->assertTrue(isset($bag['foo']));
        $this->assertFalse(isset($bag['nope']));

        $this->assertTrue($bag->has(['nee', 'foo', 'niet']));
        $this->assertFalse($bag->has(['nee', 'niet']));
    }

    public function testDelete()
    {
        $bag = new Bag($this->testArray);

        $bag->delete('nope');
        $this->assertSame($this->testArray, $bag->all());

        $bag->delete('obj');
        $this->assertSame(
            [
                'foo' => [
                    'bar' => 'baz',
                ],
                'abc.def' => 'ghi',
            ],
            $bag->all()
        );

        unset($bag['foo']);
        $this->assertSame(
            ['abc.def' => 'ghi'],
            $bag->all()
        );

        $this->assertInstanceOf(Bag::class, $bag->delete('a'));
    }

    public function testClear()
    {
        $bag = new Bag($this->testArray);

        $this->assertInstanceOf(Bag::class, $bag->clear());
        $this->assertSame([], $bag->all());
    }

    public function testIterate()
    {
        $bag = new Bag($this->testArray);

        $output = '';
        foreach ($bag as $key => $value) {
            $output .= $key . ' => ' . json_encode($value) . PHP_EOL;
        }

        $this->assertSame(
            'foo => {"bar":"baz"}' . PHP_EOL .
            'abc.def => "ghi"' . PHP_EOL .
            'obj => "[OBJ]"' . PHP_EOL,
            $output
        );
    }

    public function testAdd()
    {
        $bag = new Bag($this->testArray);

        $bag->add([
            'foo' => 'xxx',
            'test' => 'ok',
        ]);

        $this->assertSame([
            'foo' => [
                'bar' => 'baz',
            ],
            'abc.def' => 'ghi',
            'obj' => $this->testObject,
            'test' => 'ok',
        ], $bag->all());
    }

    public function testReplace()
    {
        $bag = new Bag($this->testArray);

        $bag->replace([
            'foo' => 'xxx',
            'test' => 'ok',
        ]);

        $this->assertSame([
            'foo' => 'xxx',
            'abc.def' => 'ghi',
            'obj' => $this->testObject,
            'test' => 'ok',
        ], $bag->all());
    }

    public function testAppendAndPrependToElement()
    {
        $bag = new Bag();
        $bag->appendToElement('test', 'x');
        $bag->appendToElement('test', 'y');
        $bag->appendToElement('test', 'z');
        $bag->prependToElement('test', 'a');

        $this->assertSame(['a', 'x', 'y', 'z'], $bag->get('test'));
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Cannot append to element "foo", because it's not an array
     */
    public function testAppendToElementException()
    {
        $bag = new Bag(['foo' => 'bar']);
        $bag->appendToElement('foo', 'baz');
    }

    public function testMap()
    {
        $bag = new Bag([
            'foo' => 'bar',
            'lorem' => 'ipsum',
            'test' => 'foo',
        ]);

        $callback = function ($key, $value) {
            return $key . '|' . strtoupper($value);
        };

        $mappedBag = $bag->map($callback);
        $this->assertInstanceOf(Bag::class, $mappedBag);
        $this->assertSame([
            'foo' => 'foo|BAR',
            'lorem' => 'lorem|IPSUM',
            'test' => 'test|FOO',
        ], $mappedBag->all());
    }

    public function testFilter()
    {
        $bag = new Bag([
            'foo' => 'bar',
            'lorem' => 'ipsum',
            'test' => 'foo',
        ]);

        // TODO add in php5.6
        // $callback = function ($key, $value) {
        //    return $key === 'foo' || $value === 'foo';
        //};

        $callback = function ($value) {
            return $value === 'foo';
        };

        $filteredBag = $bag->filter($callback);
        $this->assertInstanceOf(Bag::class, $filteredBag);
        $this->assertSame([
            //'foo' => 'bar',
            'test' => 'foo',
        ], $filteredBag->all());
    }

    public function testJsonSerialize()
    {
        $bag = new Bag($this->testArray);
        $this->assertSame($this->testArray, $bag->jsonSerialize());
        $this->assertSame('{"foo":{"bar":"baz"},"abc.def":"ghi","obj":"[OBJ]"}', json_encode($this->testArray));
    }

    public function testDebugInfo()
    {
        $bag = new Bag($this->testArray);
        $this->assertSame($this->testArray, $bag->__debugInfo());
    }
}