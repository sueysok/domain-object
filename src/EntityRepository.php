<?php

namespace Sueysok\DomainObject;


/**
 * Date 2017/6/6
 * Time 下午3:21
 * Class EntityRepository
 *
 * @author  sueysok
 * @package Sueysok\DomainObject
 */
abstract class EntityRepository extends Repository
{

    /**
     * @param Entity $entity
     */
    protected function checkout(Entity $entity)
    {
        if ($entity->isPersistent()) {
            if (!$entity->getEloquent()) {
                $entity->setEloquent($this->model->find($entity->getId()));
            }

            $this->setModel($entity->getEloquent());
        } else {
            $this->setModel($this->model->newInstance());

            $entity->setEloquent($this->model);
        }
    }

}