<?php

namespace Sueysok\DomainObject;

use ArrayAccess;
use Carbon\Carbon;
use Illuminate\Contracts\Support\Arrayable;
use Illuminate\Contracts\Support\Jsonable;
use Illuminate\Support\Collection;


/**
 * Date 2017/6/6
 * Time 下午3:10
 * Class DomainObject
 *
 * @author  sueysok
 * @package Sueysok\DomainObject
 */
abstract class DomainObject implements Arrayable, Jsonable, ArrayAccess
{

    /**
     * @param array $attributes
     *
     * @return static
     */
    abstract public function extract(array $attributes);

    /**
     * 比较两个领域对象
     *
     * @param DomainObject $Object
     *
     * @return bool
     */
    abstract public function equal(DomainObject $Object);

    /**
     * Get the instance as an array.
     *
     * @return array
     */
    public function toArray()
    {
        $attributes = (new \ReflectionClass($this))->getDefaultProperties();

        if (key_exists('persistence', $attributes)) {
            unset($attributes['persistence']);
        }

        if (key_exists('eloquent', $attributes)) {
            unset($attributes['eloquent']);
        }

        $array = [];
        foreach ($attributes as $key => $value) {
            $getFunction = 'get' . ucfirst($key);
            $value = $this->{$getFunction}();
            if ($value instanceof self || $value instanceof Collection) {
                $array[snake_case($key)] = $value->toArray();
            } else {
                if ($value instanceof Carbon) {
                    $array[snake_case($key)] = $value->toDateTimeString();
                } else {
                    $array[snake_case($key)] = $value;
                }
            }
        }

        return $array;
    }

    /**
     * Convert the object to its JSON representation.
     *
     * @param  int $options
     *
     * @return string
     */
    public function toJson($options = 0)
    {
        return json_encode($this->toArray());
    }

    /**
     * Whether a offset exists
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetexists.php
     *
     * @param mixed $offset <p>
     *                      An offset to check for.
     *                      </p>
     *
     * @return boolean true on success or false on failure.
     * </p>
     * <p>
     * The return value will be casted to boolean if non-boolean was returned.
     * @since 5.0.0
     */
    public function offsetExists($offset)
    {
        return !is_null($this->__get($offset));
    }

    /**
     * Offset to retrieve
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetget.php
     *
     * @param mixed $offset <p>
     *                      The offset to retrieve.
     *                      </p>
     *
     * @return mixed Can return all value types.
     * @since 5.0.0
     */
    public function offsetGet($offset)
    {
        return $this->__get($offset);
    }

    /**
     * Offset to set
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetset.php
     *
     * @param mixed $offset <p>
     *                      The offset to assign the value to.
     *                      </p>
     * @param mixed $value  <p>
     *                      The value to set.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetSet($offset, $value)
    {
        $this->__set($offset, $value);

    }

    /**
     * Offset to unset
     *
     * @link  http://php.net/manual/en/arrayaccess.offsetunset.php
     *
     * @param mixed $offset <p>
     *                      The offset to unset.
     *                      </p>
     *
     * @return void
     * @since 5.0.0
     */
    public function offsetUnset($offset)
    {
        $this->__set($offset, null);
    }

    /**
     * @param string $name
     *
     * @return mixed
     */
    public function __get($name)
    {
        $function = 'get' . ucfirst(camel_case($name));

        return $this->{$function}();
    }

    /**
     * @param string $name
     * @param mixed  $value
     */
    public function __set($name, $value)
    {
        $function = 'set' . ucfirst(camel_case($name));

        $this->{$function}($value);
    }

}