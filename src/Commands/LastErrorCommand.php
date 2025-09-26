<?php

namespace Bobinrinder\LaravelSuperMigrate\Commands;

use Bobinrinder\LaravelSuperMigrate\Models\LaravelSuperMigration;
use Illuminate\Console\Command;

class LastErrorCommand extends Command
{
    public $signature = 'super-migrate:failure {id? : The ID of the failed migration to show details for}';

    public $description = 'Show last error captured by Super Migrate';

    public function handle(): int
    {
        $lsmFailureId = $this->argument('id');

        $lsm = null;

        // get by id if provided
        if ($lsmFailureId) {
            $lsm = LaravelSuperMigration::select('id', 'method', 'name', 'started_at', 'finished_at', 'failed_at', 'error', 'stack_trace')
                ->where('id', $lsmFailureId)
                ->whereNotNull('failed_at')
                ->first();

            if (! $lsm) {
                $this->error("No migration failure found with ID: $lsmFailureId");

                return self::FAILURE;
            }
        } else {
            // get last failed migration
            $lsm = LaravelSuperMigration::select('id', 'method', 'name', 'started_at', 'finished_at', 'failed_at', 'error', 'stack_trace')
                ->whereNotNull('failed_at')
                ->orderBy('failed_at', 'desc')
                ->first();
        }

        if (! $lsm) {
            $this->info('No failed migrations found');

            return self::SUCCESS;
        }

        $this->info(print_r($lsm->toArray(), true));

        return self::SUCCESS;
    }
}
