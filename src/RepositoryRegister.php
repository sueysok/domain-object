<?php

namespace Sueysok\DomainObject;


/**
 * Date 2017/7/6
 * Time 上午9:58
 * Trait RepositoryRegister
 *
 * @author  sueysok
 * @package Sueysok\DomainObject
 * @property \Illuminate\Contracts\Container\Container app
 * @property array                                     repositories
 */
trait RepositoryRegister
{

    /**
     * Register repositories
     */
    protected function registerRepositories()
    {
        foreach ($this->repositories as $repository => $model) {
            $this->app->singleton($repository, function () use ($repository, $model) {
                return new $repository(new $model);
            });
        }
    }

}