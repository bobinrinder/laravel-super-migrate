<?php

namespace Bobinrinder\LaravelSuperMigrate\Facades;

use Illuminate\Support\Facades\Facade;

/**
 * @see \Bobinrinder\LaravelSuperMigrate\LaravelSuperMigrate
 */
class LaravelSuperMigrate extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return \Bobinrinder\LaravelSuperMigrate\LaravelSuperMigrate::class;
    }
}
