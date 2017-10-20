<?php

namespace Sueysok\DomainObject;

use Carbon\Carbon;


/**
 * Date 2017/6/6
 * Time 下午3:11
 * Class Entity
 *
 * @author  sueysok
 * @package Sueysok\DomainObject
 */
abstract class Entity extends DomainObject
{

    /**
     * @var int
     */
    protected $id = 0;

    /**
     * @var Carbon
     */
    protected $createdAt;

    /**
     * @var Carbon
     */
    protected $updatedAt;

    /**
     * @var bool
     */
    protected $persistence = false;

    /**
     * @var \Sueysok\DomainObject\Eloquent
     */
    protected $eloquent;

    /**
     * Entity constructor.
     *
     * @param int|null $id
     */
    public function __construct($id = 0)
    {
        $this->id = $id;
    }

    /**
     * @return int|null
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return Carbon|null
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @return Carbon|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return Eloquent
     */
    public function getEloquent()
    {
        return $this->eloquent;
    }

    /**
     * @param Eloquent $eloquent
     */
    public function setEloquent($eloquent)
    {
        $this->eloquent = $eloquent;
    }

    /**
     * 是否已经持久化
     *
     * @return bool
     */
    public function isPersistent()
    {
        return $this->id ? true : false;
    }

    /**
     * 是否变更
     *
     * @return bool
     */
    public function isChange()
    {
        return !$this->persistence;
    }

    /**
     * @param array $attributes
     *
     * @return static
     */
    public function extract(array $attributes)
    {
        foreach ($attributes as $key => $value) {
            $this->{camel_case($key)} = $value;
        }

        if (key_exists('id', $attributes)) {
            $this->persistent();
        }

        return $this;
    }

    /**
     * @param Eloquent $model
     *
     * @param array    $payload
     *
     * @return static
     */
    public function updateExtract(Eloquent $model, array $payload = [])
    {
        $this->eloquent = $model;

        return $this->extract(array_merge([
            'id'         => $model->id,
            'created_at' => $model->created_at,
            'updated_at' => $model->updated_at,
        ], $payload));
    }

    /**
     * 比较两个领域对象
     *
     * @param DomainObject|Entity $Entity
     *
     * @return bool
     */
    public function equal(DomainObject $Entity)
    {
        if (get_called_class() !== get_class($Entity)) {
            return false;
        }

        if ($this->getId() !== $Entity->getId()) {
            return false;
        }

        return true;
    }

    /**
     * 持久状态已变
     */
    protected function change()
    {
        $this->persistence = false;
    }

    /**
     * 数据持久化
     */
    protected function persistent()
    {
        $this->persistence = true;
    }

    /**
     *
     */
    public function __clone()
    {
        $this->id = 0;
        $this->persistence = false;

        $attributes = (new \ReflectionClass($this))->getDefaultProperties();

        foreach ($attributes as $key => $value) {
            if ($value instanceof Value) {
                $this->{$key} = clone $this->{$key};
            }
        }
    }

}