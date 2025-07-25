<?php

namespace Tests\Feature;

use Bobinrinder\LaravelSuperMigrate\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;
use Symfony\Component\Console\Output\BufferedOutput;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
class MigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_super_migrate_table_exists()
    {
        $this->assertTrue(Schema::hasTable('laravel_super_migrations'));
    }

    public function rollbackTestMigration(string $migrationName, string $filename)
    {
        $source = __DIR__."/../database/migrations/{$migrationName}";
        $target = base_path("database/migrations/{$filename}");


        copy($source, $target);

        // $relativePath = str_replace(base_path() . '/', '', $target);

        try {
            $output = new BufferedOutput();
            Artisan::call('migrate:rollback', ['--step' => 1, '--pretend' => true], $output);
            $migrationName = str_replace('.php', '', $filename);
            if (str_contains($output->fetch(), $migrationName)) {
                Artisan::call('migrate:rollback', ['--step' => 1], $output);
            }
            // dump($output->fetch());
        } catch (\Throwable $e) {
            // dump($e->getMessage());
        }

        unlink($target);
    }

    public function runTestMigration(string $filename)
    {
        $migrationName = str_replace('.php', '', $filename);
        $uniqueName = uniqid($migrationName . "_", true).'.php';

        $source = __DIR__."/../database/migrations/{$filename}";
        $target = base_path("database/migrations/{$uniqueName}");

        copy($source, $target);

        try {
            $this->artisan('migrate')->run();
        } catch (\Throwable $e) {
            // dump($e->getMessage());
        }

        unlink($target);
        return $uniqueName;
    }

    public function test_migration_applies_and_rolls_back_expected_changes()
    {
        expect(Schema::hasTable('success_table'))->toBeFalse();

        expect(DB::table('laravel_super_migrations')->first())->toBeNull();

        $filename = $this->runTestMigration('successful_migration.php');

        expect(Schema::hasTable('success_table'))->toBeTrue();
        expect(Schema::hasColumn('success_table', 'name'))->toBeTrue();

        // name is binary cause of anonymous class
        $name = DB::table('laravel_super_migrations')->orderBy('id', 'desc')->first()->name;
        expect($name)->not->toBeNull();
        $decodedName = hex2bin(unpack('H*', $name)[1]);
        // var_dump($decodedName);

        expect(str_contains($decodedName, 'successful_migration'))->toBeTrue();

        $this->rollbackTestMigration('successful_migration.php', $filename);

        expect(Schema::hasTable('success_table'))->toBeFalse();
    }

    public function test_migration_error_logs_correctly()
    {
        expect(Schema::hasTable('error_table'))->toBeFalse();

        expect(DB::table('laravel_super_migrations')->first())->toBeNull();

        $migrationName = $this->runTestMigration('error_migration.php');

        expect(Schema::hasTable('error_table'))->toBeFalse();

        // name is binary cause of anonymous class
        $lsmEntry = DB::table('laravel_super_migrations')->orderBy('id', 'desc')->first();
        expect($lsmEntry->name)->not->toBeNull();
        $decodedName = hex2bin(unpack('H*', $lsmEntry->name)[1]);

        // var_dump($lsmEntry);

        expect(str_contains($decodedName, $migrationName))->toBeTrue();

        // $this->runTestMigration('successful_migration.php', true);

        // expect(Schema::hasTable('success_table'))->toBeFalse();
    }
}
