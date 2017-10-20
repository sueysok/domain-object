<?php

namespace Sueysok\DomainObject;

use Illuminate\Support\Str;


/**
 * Date 2017/6/6
 * Time 下午3:12
 * Class Value
 * @author sueysok
 * @package Sueysok\DomainObject
 */
abstract class Value extends DomainObject
{

    /**
     * @param array $attributes
     *
     * @return static
     */
    public function extract(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->{Str::camel($key)} = $value;
        }

        return $this;
    }

    /**
     * 比较两个领域对象
     *
     * @param DomainObject|Value $Value
     *
     * @return bool
     */
    public function equal(DomainObject $Value)
    {
        if (get_called_class() !== get_class($Value)) {
            return false;
        }

        if ($this->toJson() !== $Value->toJson()) {
            return false;
        }

        return true;
    }

}