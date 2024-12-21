<?php

namespace Bobinrinder\LaravelSuperMigrate\Commands;

use Illuminate\Console\Command;

class LaravelSuperMigrateCommand extends Command
{
    public $signature = 'laravel-super-migrate';

    public $description = 'My command';

    public function handle(): int
    {
        $this->comment('All done');

        return self::SUCCESS;
    }
}
