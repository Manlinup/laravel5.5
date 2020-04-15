<?php
namespace App\Bag;

use Avris\Bag\Bag;
use Avris\Bag\BagHelper;
use Avris\Bag\Test\TestObject;

class BagHelperTest extends \PHPUnit_Framework_TestCase
{
    public function testIsArray()
    {
        $this->assertTrue(BagHelper::isArray([]));
        $this->assertTrue(BagHelper::isArray(new Bag([])));

        $this->assertFalse(BagHelper::isArray('test'));
        $this->assertFalse(BagHelper::isArray(new TestObject()));
    }

    public function testToArray()
    {
        $array = [
            'foo' => 'bar',
            'lorem' => [
                'ipsum' => ['dolor', 'sit', 'amet'],
            ]
        ];

        $this->assertSame(
            $array,
            BagHelper::toArray($array)
        );

        $this->assertSame(
            $array,
            BagHelper::toArray(new Bag($array))
        );

        $this->assertSame(
            $array,
            BagHelper::toArray(new \ArrayIterator($array))
        );

        $this->assertSame(
            [null],
            BagHelper::toArray(null)
        );

        $this->assertSame(
            ['foo'],
            BagHelper::toArray('foo')
        );
    }

    public function testMagicGetter()
    {
        $array = [
            'test' => 'ok',
        ];

        $this->assertSame('ok', BagHelper::magicGetter($array, 'test'));
        $this->assertSame(null, BagHelper::magicGetter($array, 'nope'));
        $this->assertSame('def', BagHelper::magicGetter($array, 'nope', 'def'));

        $object = new TestObject();
        $object->setFoo('FOO');
        $object->set('bar', 'BAR');

        $this->assertSame('FUN', BagHelper::magicGetter($object, 'fun'));
        $this->assertSame('FOO', BagHelper::magicGetter($object, 'foo'));
        $this->assertSame('BAR', BagHelper::magicGetter($object, 'bar'));
        $this->assertSame('XXX', BagHelper::magicGetter($object, 'xxx'));
        $this->assertSame('EMPTY', BagHelper::magicGetter($object, 'nope'));

        $otherObject = new \stdClass();
        $this->assertSame(null, BagHelper::magicGetter($otherObject, 'nope'));
        $this->assertSame('def', BagHelper::magicGetter($otherObject, 'nope', 'def'));
    }

    /**
     * @expectedException \Avris\Bag\NotFoundException
     */
    public function testMagicGetterNotFound()
    {
        BagHelper::magicGetter([], 'nope', BagHelper::THROW_EXCEPTION);
    }

    public function testMagicSetter()
    {
        $array = [];

        $this->assertEquals(['foo' => 'bar'], BagHelper::magicSetter($array, 'foo', 'bar'));
        $this->assertEquals(['foo' => 'bar'], $array);

        $object = new TestObject();

        $this->assertSame($object, BagHelper::magicSetter($object, 'foo', 'FOO'));
        $this->assertSame('FOO', $object->getFoo());
        $this->assertSame($object, BagHelper::magicSetter($object, 'bar', 'BAR'));
        $this->assertSame('BAR', $object->get('bar'));
        $this->assertSame($object, BagHelper::magicSetter($object, 'xxx', 'osiem'));
        $this->assertSame('osiem', $object->xxx);

        $otherObject = new \stdClass();
        $this->assertSame($otherObject, BagHelper::magicSetter($otherObject, 'test', 'ok'));
        $this->assertSame('ok', $otherObject->test);
    }

    /**
     * @expectedException \InvalidArgumentException
     * @expectedExceptionMessage Array or object expected, string given
     */
    public function testMagicSetterInvalidArgument()
    {
        $string = 'string';
        BagHelper::magicSetter($string, 'whatever', 'value');
    }
}