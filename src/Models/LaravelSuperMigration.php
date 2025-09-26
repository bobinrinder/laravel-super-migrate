<?php

namespace Bobinrinder\LaravelSuperMigrate\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\MigrationEvent;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Symfony\Component\Console\Output\ConsoleOutput;

/**
 * @property string|null $name
 * @property Carbon|null $finished_at
 * @property Carbon|null $failed_at
 * @property string|null $error
 * @property string|null $stack_trace
 */
class LaravelSuperMigration extends Model
{
    protected $table;

    protected $guarded = [];

    public $timestamps = false;

    // this uuid identifies a single migration run
    protected static $runId;

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('super-migrate.table_name', 'super_migrations');
    }

    public static function initRunId(): string
    {
        self::$runId = (string) Str::uuid();

        return self::$runId;
    }

    public static function getRunId(): string
    {
        return self::$runId;
    }

    public static function clearRunId(): void
    {
        self::$runId = null;
    }

    public static function getMigrationNameFromString(string $string): string
    {
        // Extract the file name from the class path
        $pattern = '#/migrations/([^/]+)\.php#i';
        preg_match($pattern, $string, $matches);

        if (! empty($matches)) {
            return $matches[1];
        }

        return $string;
    }

    public static function getMigrationNameFromEvent(MigrationEvent $event): string
    {
        // Get the full class name, which includes the file path for anonymous migrations
        $className = get_class($event->migration);

        return self::getMigrationNameFromString($className);
    }

    public static function isInitialMigration(MigrationEvent $event): bool
    {
        $migrationName = self::getMigrationNameFromEvent($event);
        if (str_contains($migrationName, 'create_super_migrate_table')) {
            return true;
        }

        return false;
    }

    public static function start(MigrationEvent $event)
    {
        // Check if this is the initial migration
        if (self::isInitialMigration($event)) {
            return;
        }

        // Check if config allows parallel migrations
        if (config('super-migrate.allow_parallel_migrations') === false) {

            // Check if there is any active migration running already
            $existingMigration = self::orderBy('id', 'DESC')->firstWhere([
                'finished_at' => null,
                'failed_at' => null,
            ]);

            if ($existingMigration) {

                $output = new ConsoleOutput;
                $output->writeln('');
                $output->writeln('<info>You have parallel migration prevention activated.</info>');
                $output->writeln('<comment>Active migration running: '.$existingMigration->name.'</comment>');

                // If there is currently a migration runnning, do not start a new one
                if (config('super-migrate.fail_gracefully_on_parallel_migrations') === false) {
                    $output->writeln('<error>Aborting migration...</error>');
                    throw new \Exception('Migration already started: '.$existingMigration->name);
                } else {
                    // stop the migration and exit the process gracefully
                    $output->writeln('<comment>Aborting migration gracefully...</comment>');
                    exit(0);
                }
            }
        }

        return self::create([
            'name' => self::getMigrationNameFromEvent($event),
            'method' => $event->method,
            'run_id' => self::$runId,
            'started_at' => now(),
        ]);
    }

    public static function finish(MigrationEvent $event): void
    {
        // Check if this is the initial migration
        if (self::isInitialMigration($event)) {
            return;
        }

        $lsm = self::orderBy('id', 'DESC')->firstWhere([
            'name' => self::getMigrationNameFromEvent($event),
            'method' => $event->method,
            'finished_at' => null,
            'run_id' => self::$runId,
        ]);

        if ($lsm) {
            $lsm->finished_at = now();
            $lsm->save();
        }
    }

    public static function fail(\Throwable $exception): void
    {
        $lsm = self::orderBy('id', 'DESC')->firstWhere([
            'finished_at' => null,
            'run_id' => self::$runId,
        ]);

        if ($lsm) {
            $lsm->failed_at = now();
            if ($exception->getMessage()) {
                $lsm->error = $exception->getMessage();
            }
            if ($exception->getTraceAsString()) {
                $lsm->stack_trace = $exception->getTraceAsString();
            }
            $lsm->save();

            return;
        }

        throw new \Exception('Super migrate failed logging!');
    }
}
