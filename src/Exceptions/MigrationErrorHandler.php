<?php

namespace Bobinrinder\LaravelSuperMigrate\Exceptions;

use Bobinrinder\LaravelSuperMigrate\Models\LaravelSuperMigration;
use Illuminate\Contracts\Debug\ExceptionHandler;
use Throwable;

class MigrationErrorHandler implements ExceptionHandler
{
    protected $originalHandler;

    public function __construct(ExceptionHandler $originalHandler)
    {
        $this->originalHandler = $originalHandler;
    }

    public function report(Throwable $exception)
    {
        // Check if the exception is related to migrations
        if ($this->isMigrationError($exception)) {
            // Log or handle the migration error as necessary
            LaravelSuperMigration::fail($exception->getMessage());
        }

        // Call the original exception handler to handle other exceptions
        $this->originalHandler->report($exception);
    }

    public function render($run, Throwable $exception)
    {
        return $this->originalHandler->render($run, $exception);
    }

    public function renderForConsole($output, Throwable $exception)
    {
        $this->originalHandler->renderForConsole($output, $exception);
    }

    public function shouldReport(Throwable $exception)
    {
        return $this->originalHandler->shouldReport($exception);
    }

    /**
     * Check if the error is related to a migration.
     *
     * @return bool
     */
    protected function isMigrationError(Throwable $exception)
    {
        // Check if the error occurred in the migrations directory
        if (app()->runningInConsole() && str_contains($exception->getFile(), '/database/migrations/')) {
            return true;
        }

        // Check the stack trace for references to the migrations directory
        foreach ($exception->getTrace() as $trace) {
            if (isset($trace['file']) && str_contains($trace['file'], '/database/migrations/')) {
                return true;
            }
        }

        return false;
    }
}
