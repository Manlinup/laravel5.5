<?php
namespace Avris\Bag;

class Nested
{
    /**
     * @param array|object $object
     * @param string[] $keys
     * @param mixed|null $default
     * @return mixed|null
     */
    public static function get($object, array $keys, $default = null)
    {
        $current = $object;
        foreach ($keys as $key) {
            try {
                $current = BagHelper::magicGetter($current, $key, BagHelper::THROW_EXCEPTION);
            } catch (NotFoundException $e) {
                return $default;
            }
        }

        return $current;
    }

    /**
     * @param array $array
     * @param string[] $keys
     * @param mixed $value
     * @return mixed|null
     */
    public static function set(array &$array, array $keys, $value)
    {
        if (!count($keys)) {
            $array = $value;

            return $array;
        }

        $current = &$array;
        $setKey = array_pop($keys);

        foreach ($keys as $i => $key) {
            if (!is_array($current)) {
                throw new \InvalidArgumentException(sprintf('Array expected, %s given', gettype($current)));
            }
            if (!isset($current[$key])) {
                $current[$key] = [];
            }
            $current = &$current[$key];
        }

        $current[$setKey] = $value;

        return $array;
    }


}
