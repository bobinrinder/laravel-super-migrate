<?php

namespace Bobinrinder\LaravelSuperMigrate\Commands;

use Illuminate\Console\Command;
use Bobinrinder\LaravelSuperMigrate\Models\LaravelSuperMigration;
class LaravelSuperMigrateCommand extends Command
{
    public $signature = 'super-migrate';

    public $description = 'List all migrations run via Laravel Super Migrate';

    public function handle(): int
    {
        $lsm = LaravelSuperMigration::select('id', 'method', 'name', 'started_at', 'finished_at', 'failed_at')->get()->toArray();

        $headers = ['ID', 'Method', 'Name', 'Started At', 'Finished At', 'Failed At'];

        $this->table($headers, $lsm);

        $this->comment('All done');

        return self::SUCCESS;
    }
}
