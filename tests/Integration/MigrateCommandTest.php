<?php

declare(strict_types=1);

namespace Tests\Integration;

use App\Commands\MigrateCommand;
use Intisari\Application;
use PHPUnit\Framework\TestCase;
use Lukman\Database\Connection;
use Lukman\Database\Schema\SchemaBuilder;

class MigrateCommandTest extends TestCase
{
    private Application $app;
    private Connection $db;

    protected function setUp(): void
    {
        $this->app = require dirname(__DIR__, 2) . '/bootstrap/app.php';
        $this->app->loadEnvironment('.env.testing', true);
        $this->app->loadConfiguration();
        $this->app->bootstrap();

        $this->db = $this->app->db();
    }

    public function testMigrateCommandExecutesSuccessfully(): void
    {
        // Mock the console output
        ob_start();

        $command = new MigrateCommand($this->app);
        $command->handle();

        $output = ob_get_clean();

        $this->assertStringContainsString('Migration complete', (string) $output);
        
        $schema = new SchemaBuilder($this->db);
        $this->assertTrue($schema->hasTable('users'));
        $this->assertTrue($schema->hasTable('roles'));
        $this->assertTrue($schema->hasTable('permissions'));
    }
}
