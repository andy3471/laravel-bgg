<?php

namespace AndyH\LaravelBgg;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Andy3471\LaravelBgg\Skeleton\SkeletonClass
 */
class LaravelBggFacade extends Facade
{
    /**
     * Get the registered name of the component.
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return 'laravel-bgg';
    }
}
