<?php
namespace Avris\Bag;

class Set implements \IteratorAggregate, \JsonSerializable, \Countable
{
    /** @var array */
    protected $values = [];

    /** @var callback|null */
    protected $callback;

    /**
     * @param array $values
     * @param callback|null $callback
     */
    public function __construct($values = [], $callback = null)
    {
        $values = $values instanceof self ? $values->all() : BagHelper::toArray($values);

        $this->callback = $callback;
        if ($callback) {
            $values = array_map($callback, $values);
        }

        $this->values = array_unique($values);
    }

    /**
     * @return string[]
     */
    public function all()
    {
        return array_values($this->values);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->values);
    }

    /**
     * @return bool
     */
    public function isEmpty()
    {
        return count($this->values) === 0;
    }

    /**
     * @param mixed $value
     * @return mixed
     */
    protected function normalizeValue($value)
    {
        $callback = $this->callback;

        return $callback ? $callback($value) : $value;
    }

    /**
     * @param mixed $value
     * @return $this
     */
    public function add($value)
    {
        $value = $this->normalizeValue($value);

        if (!in_array($value, $this->values, true)) {
            $this->values[] = $value;
        }

        return $this;
    }

    /**
     * @param array $values
     * @return $this
     */
    public function addMultiple(array $values)
    {
        foreach ($values as $value) {
            $this->add($value);
        }

        return $this;
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function has($value)
    {
        return in_array($this->normalizeValue($value), $this->values, true);
    }

    /**
     * @param mixed $value
     * @return bool
     */
    public function delete($value)
    {
        $key = array_search($this->normalizeValue($value), $this->values, true);

        if ($key !== false) {
            unset($this->values[$key]);
        }

        return $this;
    }

    /**
     * @return mixed
     */
    public function first()
    {
        return reset($this->values);
    }

    /**
     * @return mixed
     */
    public function last()
    {
        return end($this->values);
    }

    public function clear()
    {
        $this->values = [];

        return $this;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->values);
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return array_values($this->values);
    }

    /**
     * @return array
     */
    public function __debugInfo()
    {
        return array_values($this->values);
    }
}
