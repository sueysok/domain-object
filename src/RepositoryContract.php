<?php

namespace Sueysok\DomainObject;

use Illuminate\Support\Collection;


/**
 * Date 2017/9/11
 * Time 上午10:55
 * Interface RepositoryContract
 *
 * @author  sueysok
 * @package Sueysok\DomainObject
 */
interface RepositoryContract
{

    /**
     * @param Eloquent|Collection $item
     *
     * @return Entity|Value|Collection
     */
    public static function entity($item);

}