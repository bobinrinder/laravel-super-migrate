<?php

namespace Bobinrinder\LaravelSuperMigrate\Commands;

use Bobinrinder\LaravelSuperMigrate\Models\LaravelSuperMigration;
use Illuminate\Console\Command;

class ListCommand extends Command
{
    public $signature = 'super-migrate:list';

    public $description = 'List all migrations run via Laravel Super Migrate';

    public function handle(): int
    {
        $lsm = LaravelSuperMigration::select('id', 'method', 'name', 'started_at', 'finished_at', 'failed_at')
            ->orderByDesc('started_at')->limit(20)->get()->toArray();

        // add a status column
        foreach ($lsm as &$migration) {
            if ($migration['finished_at'] !== null && $migration['failed_at'] === null) {
                $migration['status'] = '✅';
            } elseif ($migration['failed_at'] !== null) {
                $migration['status'] = '❌';
            } else {
                $migration['status'] = '⏳';
            }
        }

        // reorder the columns
        foreach ($lsm as &$migration) {
            $migration = [
                'status' => $migration['status'],
                'id' => $migration['id'],
                'method' => $migration['method'],
                'name' => $migration['name'],
                'started_at' => $migration['started_at'],
                'finished_at' => $migration['finished_at'],
                'failed_at' => $migration['failed_at'],
            ];
        }

        $headers = ['', 'ID', 'Method', 'Name', 'Started At', 'Finished At', 'Failed At'];

        $this->table($headers, $lsm);

        return self::SUCCESS;
    }
}
