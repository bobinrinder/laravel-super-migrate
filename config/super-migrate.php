<?php

// config for Bobinrinder/LaravelSuperMigrate
return [

    /*
     * Easily enable or disable the package.
     * This is useful for testing or if you want to disable the package temporarily.
     * The default value is true.
     */
    'enabled' => env('SUPER_MIGRATE_ENABLED', true),

    /*
     * The name of the migration table. This is the table that will be used to store the migration history.
     * The default value is 'super_migrations'.
     */
    'table_name' => env('SUPER_MIGRATE_TABLE_NAME', 'super_migrations'),

    /*
     * Allows you to decide whether you want to allow parallel migrations.
     * If set to true, the package will allow parallel migrations.
     * If set to false, the package will prevent parallel migrations.
     * The default value is false.
     */
    'allow_parallel_migrations' => env('SUPER_MIGRATE_ALLOW_PARALLEL_MIGRATIONS', false),

    /*
     * Allows you to decide whether you want to fail gracefully on parallel migrations.
     * If set to true, the package will fail gracefully on parallel migrations.
     * If set to false, the package will throw an exception on parallel migrations.
     * The default value is true.
     */
    'fail_gracefully_on_parallel_migrations' => env('SUPER_MIGRATE_FAIL_GRACEFULLY_ON_PARALLEL_MIGRATIONS', true),

];
