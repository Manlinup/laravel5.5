<?php
namespace Avris\Bag;

class Bag implements \ArrayAccess, \IteratorAggregate, \Countable, \JsonSerializable
{
    /** @var array */
    protected $array = [];

    /**
     * @param array $array
     */
    public function __construct($array = [])
    {
        foreach (BagHelper::toArray($array) as $key => $value) {
            $this->set($key, $value);
        }
    }

    /**
     * @return array
     */
    public function all()
    {
        return $this->array;
    }

    /**
     * @return array
     */
    public function keys()
    {
        return array_keys($this->array);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->array);
    }

    public function isEmpty()
    {
        return empty($this->array);
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function get($key, $default = null)
    {
        return isset($this->array[$key]) ? $this->array[$key] : $default;
    }

    /**
     * @param string $offset
     * @return mixed
     */
    public function offsetGet($offset)
    {
        return $this->get($offset);
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function __invoke($key, $default = null)
    {
        return $this->get($key, $default);
    }

    /**
     * @param string $key
     * @param mixed|null $default
     * @return mixed
     */
    public function getDeep($key, $default = null)
    {
        return Nested::get($this->array, explode('.', $key), $default);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return Bag
     */
    public function set($key, $value)
    {
        $this->array[$key] = $value;

        return $this;
    }

    /**
     * @param string $offset
     * @param mixed $value
     */
    public function offsetSet($offset, $value)
    {
        if (is_null($offset)) {
            $this->array[] = $value;
        } else {
            $this->set($offset, $value);
        }
    }

    /**
     * @param string|string[] $key
     * @return bool true if any $key is found
     */
    public function has($key)
    {
        if (!is_array($key)) {
            return array_key_exists($key, $this->array);
        }

        foreach ($key as $element) {
            if (array_key_exists($element, $this->array)) {
                return true;
            }
        }

        return false;
    }

    /**
     * @param string $offset
     * @return bool
     */
    public function offsetExists($offset)
    {
        return $this->has($offset);
    }

    /**
     * @param string $key
     * @return $this
     */
    public function delete($key)
    {
        unset($this->array[$key]);

        return $this;
    }

    /**
     * @param string $offset
     */
    public function offsetUnset($offset)
    {
        $this->delete($offset);
    }

    /**
     * @return $this
     */
    public function clear()
    {
        $this->array = [];

        return $this;
    }

    public function getIterator()
    {
        return new \ArrayIterator($this->array);
    }

    /**
     * @param array|\Traversable $array
     * @return $this
     */
    public function add($array)
    {
        foreach (BagHelper::toArray($array) as $key => $value) {
            if (!isset($this->array[$key])) {
                $this->set($key, $value);
            }
        }

        return $this;
    }

    /**
     * @param array|\Traversable $array
     * @return $this
     */
    public function replace($array)
    {
        foreach (BagHelper::toArray($array) as $key => $value) {
            $this->set($key, $value);
        }

        return $this;
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function appendToElement($key, $value)
    {
        $array = $this->getArrayFromElement($key);
        $array[] = $value;

        return $this->set($key, $array);
    }

    /**
     * @param string $key
     * @param mixed $value
     * @return $this
     * @throws \InvalidArgumentException
     */
    public function prependToElement($key, $value)
    {
        $array = $this->getArrayFromElement($key);
        array_unshift($array, $value);

        return $this->set($key, $array);
    }

    protected function getArrayFromElement($key)
    {
        $array = $this->get($key);
        if ($array === null) {
            return [];
        }
        if (!is_array($array)) {
            throw new \InvalidArgumentException(sprintf(
                'Cannot append to element "%s", because it\'s not an array',
                $key
            ));
        }

        return $array;
    }

    public function map(callable $callback)
    {
        $result = [];
        foreach ($this->array as $key => $value) {
            $mapped = $callback($key, $value);
            $result[$key] = $mapped;
        }

        return new Bag($result);
    }

    public function filter(callable $callback)
    {
        return new Bag(array_filter($this->array, $callback)); // TODO add in php5.6: ARRAY_FILTER_USE_BOTH
    }

    /**
     * @return array
     */
    public function jsonSerialize()
    {
        return $this->array;
    }

    public function __debugInfo()
    {
        return $this->array;
    }
}
