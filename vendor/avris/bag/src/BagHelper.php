<?php
namespace Avris\Bag;

class BagHelper
{
    const THROW_EXCEPTION = '___throw_exception___';

    /**
     * @param mixed $object
     * @return bool
     */
    public static function isArray($object)
    {
        return is_array($object) || $object instanceof \Traversable;
    }

    /**
     * @param mixed $object
     * @return array
     */
    public static function toArray($object)
    {
        if ($object instanceof Bag) {
            return $object->all();
        }

        if ($object instanceof \Traversable) {
            return iterator_to_array($object);
        }

        return is_array($object) ? $object : [$object];
    }

    /**
     * @param array|object $object
     * @param string $attr
     * @param mixed|null $default
     * @return mixed|null
     * @throws NotFoundException
     */
    public static function magicGetter($object, $attr, $default = null)
    {
        if ((is_array($object) || $object instanceof Bag) && isset($object[$attr])) {
            return $object[$attr];
        } elseif (is_object($object)) {
            if (method_exists($object, $attr)) {
                return call_user_func([$object, $attr]);
            } elseif (method_exists($object, 'get' . ucfirst($attr))) {
                return call_user_func([$object, 'get' . ucfirst($attr)]);
            } elseif (property_exists($object, $attr)) {
                return $object->{$attr};
            } elseif (method_exists($object, 'get')) {
                return $object->get($attr);
            }
        }

        if ($default === self::THROW_EXCEPTION) {
            throw new NotFoundException;
        }

        return $default;
    }

    /**
     * @param array|object $object
     * @param string $attr
     * @param mixed $value
     * @return array|object
     * @throws \InvalidArgumentException
     */
    public static function magicSetter(&$object, $attr, $value)
    {
        if (!is_array($object) && !is_object($object)) {
            throw new \InvalidArgumentException(sprintf('Array or object expected, %s given', gettype($object)));
        }

        if (is_array($object)) {
            $object[$attr] = $value;
        } elseif (method_exists($object, 'set' . ucfirst($attr))) {
            call_user_func([$object, 'set' . ucfirst($attr)], $value);
        } elseif (property_exists($object, $attr)) {
            $object->{$attr} = $value;
        } elseif (method_exists($object, 'set')) {
            $object->set($attr, $value);
        } else {
            $object->{$attr} = $value;
        }

        return $object;
    }
}
