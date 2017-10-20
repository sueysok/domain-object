<?php

namespace Sueysok\DomainObject;


/**
 * Date 2017/6/6
 * Time 下午3:23
 * Class ValueRepository
 *
 * @author  sueysok
 * @package Sueysok\DomainObject
 */
abstract class ValueRepository extends Repository
{

    /**
     * 值对象持久化
     *
     * @param Value $value
     */
    public function save(Value $value)
    {
        $this->persistent($value);
    }

    /**
     * @param Value|null $value
     *
     * @return int|null
     */
    public function getId(Value $value = null)
    {
        if (is_null($value)) {
            return $this->model->exists ? $this->model->id : null;
        } else {
            if ($this->model->exists && $this->model->hash == $this->hash($value)) {
                return $this->model->id;
            }

            $this->persistent($value);

            return $this->model->id;
        }
    }

    /**
     * @param Value $value
     */
    protected function persistent(Value $value)
    {
        $hash = $this->hash($value);

        $model = $this->model->where('hash', $hash)->first();

        $this->model = $model ?: $this->model->create(array_merge($value->toArray(), ['hash' => $hash]));
    }

    /**
     * @param Value $value
     *
     * @return string
     */
    protected function hash(Value $value)
    {
        return md5($value->toJson());
    }

}
