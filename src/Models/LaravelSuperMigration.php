<?php

namespace Bobinrinder\LaravelSuperMigrate\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Events\MigrationEvent;
use Illuminate\Support\Str;

class LaravelSuperMigration extends Model
{
    protected $guarded = [];

    public $timestamps = false;

    // this uuid identifies a single migration run
    protected static $runId;

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
        $pattern = '/\d{4}_\d{2}_\d{2}_\d{6}_[a-z0-9_]+/';
        preg_match($pattern, $string, $matches);

        if (! empty($matches)) {
            return $matches[0];
        }

        return $string;
    }

    public static function getMigrationNameFromEvent(MigrationEvent $event): string
    {
        // Get the full class name, which includes the file path for anonymous migrations
        $className = get_class($event->migration);

        return self::getMigrationNameFromString($className);
    }

    public static function start(MigrationEvent $event)
    {
        // Check if there is any active migration running already
        $existingMigration = self::orderBy('id', 'DESC')->firstWhere([
            'finished_at' => null,
            'failed_at' => null,
        ]);

        // If there is currently a migration runnning, do not start a new one
        if ($existingMigration) {
            throw new \Exception('Migration already started: '.$existingMigration->name);
        }

        return self::create([
            'name' => self::getMigrationNameFromEvent($event),
            'method' => $event->method,
            'run_id' => self::$runId,
            'started_at' => now(),
        ]);
    }

    public static function finish(MigrationEvent $event)
    {
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

    public static function fail(string $error = '')
    {
        $lsm = self::orderBy('id', 'DESC')->firstWhere([
            'finished_at' => null,
            'run_id' => self::$runId,
        ]);

        if ($lsm) {
            $lsm->failed_at = now();
            if ($error) {
                $lsm->error = $error;
            }
            $lsm->save();

            return;
        }

        throw new \Exception('Super migrate failed logging!');
    }
}
