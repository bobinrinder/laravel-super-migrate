<?php

namespace Bobinrinder\LaravelSuperMigrate;

use Bobinrinder\LaravelSuperMigrate\Commands\LaravelSuperMigrateCommand;
use Spatie\LaravelPackageTools\Package;
use Spatie\LaravelPackageTools\PackageServiceProvider;

class LaravelSuperMigrateServiceProvider extends PackageServiceProvider
{
    public function configurePackage(Package $package): void
    {
        /*
         * This class is a Package Service Provider
         *
         * More info: https://github.com/spatie/laravel-package-tools
         */
        $package
            ->name('laravel-super-migrate')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_laravel_super_migrate_table')
            ->hasCommand(LaravelSuperMigrateCommand::class);
    }
}
