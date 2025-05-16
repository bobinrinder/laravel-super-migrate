<?php

namespace Tests\Feature;

use Bobinrinder\LaravelSuperMigrate\Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Schema;

class MigrationTest extends TestCase
{
    use RefreshDatabase;

    public function test_if_super_migrate_table_exists()
    {
        $this->assertTrue(Schema::hasTable('laravel_super_migrations'));
    }

    public function runTestMigration(string $filename, bool $rollback = false)
    {
        $target = base_path("database/migrations/{$filename}");

        if (file_exists($target)) {
            unlink($target); // Clean up to avoid redeclaration
        }

        $source = __DIR__."/../database/migrations/{$filename}";
        copy($source, $target);

        if ($rollback) {
            $this->artisan('migrate:rollback --step=1')->run();
        } else {
            $this->artisan('migrate')->run();
        }

        unlink($target);
    }

    public function test_migration_applies_and_rolls_back_expected_changes()
    {
        expect(Schema::hasTable('success_table'))->toBeFalse();

        expect(\DB::table('laravel_super_migrations')->first())->toBeNull();

        $this->runTestMigration('successful_migration.php');

        expect(Schema::hasTable('success_table'))->toBeTrue();
        expect(Schema::hasColumn('success_table', 'name'))->toBeTrue();

        // name is binary cause of anonymous class
        $name = \DB::table('laravel_super_migrations')->orderBy('id', 'desc')->first()->name;
        expect($name)->not->toBeNull();
        $decodedName = hex2bin(unpack('H*', $name)[1]);

        expect(str_contains($decodedName, 'successful_migration'))->toBeTrue();

        $this->runTestMigration('successful_migration.php', true);

        expect(Schema::hasTable('success_table'))->toBeFalse();
    }
}
