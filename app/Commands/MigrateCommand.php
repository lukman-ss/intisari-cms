<?php

declare(strict_types=1);

namespace App\Commands;

use Intisari\Application;
use Lukman\Database\Schema\SchemaBuilder;

/**
 * Idempotent migration command for Intisari CMS.
 *
 * Runs all migrations in database/migrations and the AdminSeeder.
 * Run via: php intisari migrate
 */
final class MigrateCommand
{
    public function __construct(private readonly Application $app)
    {
    }

    public function handle(): void
    {
        $connection = $this->app->db();
        $schema = new SchemaBuilder($connection);

        $migrationsDir = $this->app->basePath('database/migrations');
        if (is_dir($migrationsDir)) {
            $files = scandir($migrationsDir);
            if ($files !== false) {
                sort($files);
                foreach ($files as $file) {
                    if (str_ends_with($file, '.php')) {
                        $migration = require $migrationsDir . '/' . $file;
                        if (is_object($migration) && method_exists($migration, 'up')) {
                            $migration->up($schema);
                            echo "Ran migration: {$file}\n";
                        }
                    }
                }
            }
        }

        $seederClass = \Database\Seeders\AdminSeeder::class;
        if (class_exists($seederClass)) {
            $seeder = new $seederClass();
            $seeder->run($connection);
        }

        echo "Migration complete.\n";
    }
}
