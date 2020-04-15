<?php
namespace Avris\Bag\Test;

class TestObject implements \JsonSerializable
{
    private $values = [];

    private $foo;

    public $xxx = 'XXX';

    public function get($key)
    {
        return isset($this->values[$key]) ? $this->values[$key] : 'EMPTY';
    }

    public function set($key, $value)
    {
        $this->values[$key] = $value;
    }

    public function setFoo($foo)
    {
        $this->foo = $foo;
    }

    public function getFoo()
    {
        return $this->foo;
    }

    public function fun()
    {
        return 'FUN';
    }

    public function jsonSerialize()
    {
        return '[OBJ]';
    }
}
