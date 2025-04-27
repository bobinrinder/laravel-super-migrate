<?php

namespace Bobinrinder\LaravelSuperMigrate;

use Bobinrinder\LaravelSuperMigrate\Commands\LaravelSuperMigrateCommand;
use Bobinrinder\LaravelSuperMigrate\Exceptions\MigrationErrorHandler;
use Bobinrinder\LaravelSuperMigrate\Models\LaravelSuperMigration;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Illuminate\Database\Events\MigrationEnded;
use Illuminate\Database\Events\MigrationsStarted;
use Illuminate\Database\Events\MigrationStarted;
use Illuminate\Support\Facades\Event;
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
            ->name('super-migrate')
            ->hasConfigFile()
            ->hasViews()
            ->hasMigration('create_super_migrate_table')
            ->hasCommand(LaravelSuperMigrateCommand::class);
    }

    /**
     * Bootstrap any application services.
     */
    public function packageBooted(): void
    {
        Event::listen(function (MigrationsStarted $event) {
            $runId = LaravelSuperMigration::initRunId();
        });

        Event::listen(function (MigrationStarted $event) {
            LaravelSuperMigration::start($event);
        });

        Event::listen(function (MigrationEnded $event) {
            LaravelSuperMigration::finish($event);
        });
    }

    public function packageRegistered()
    {
        // Extend Laravel's exception handler
        $this->app->extend(ExceptionHandler::class, function ($originalHandler, $app) {
            return new MigrationErrorHandler($originalHandler);
        });
    }
}
