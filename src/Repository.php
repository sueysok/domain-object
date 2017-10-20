<?php

namespace Sueysok\DomainObject;

use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Support\Collection;


/**
 * Date 2017/6/6
 * Time 下午3:19
 * Class Repository
 *
 * @author  sueysok
 * @package Sueysok\DomainObject
 */
abstract class Repository implements RepositoryContract
{

    /**
     * @var Eloquent|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    protected $model;

    /**
     * Repository constructor.
     *
     * @param Eloquent $model
     */
    public function __construct(Eloquent $model)
    {
        $this->model = $model;
    }

    /**
     * @return Eloquent|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder
     */
    public function getModel()
    {
        return $this->model;
    }

    /**
     * @param Eloquent|\Illuminate\Database\Eloquent\Builder|\Illuminate\Database\Query\Builder $model
     */
    public function setModel($model)
    {
        $this->model = $model;
    }

    /**
     * @param Collection $modelCollection
     *
     * @return Collection
     */
    protected static function entityCollection($modelCollection)
    {
        $Collection = new Collection;

        if ($modelCollection instanceof Collection && !$modelCollection->isEmpty()) {
            foreach ($modelCollection->all() as $model) {
                $Collection->push((static::entity($model)));
            }
        }

        return $Collection;
    }

    /**
     *
     */
    protected function modelNotFound()
    {
        $exception = new ModelNotFoundException;
        $exception->setModel(get_class($this->model));
        throw $exception;
    }

}