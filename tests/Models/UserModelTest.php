<?php

declare(strict_types=1);

namespace Tests\Models;

use App\Models\User;
use Intisari\Application;
use PHPUnit\Framework\TestCase;
use Lukman\Database\Connection;

class UserModelTest extends TestCase
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
        
        // We assume an in-memory sqlite database for testing
        $schema = new \Lukman\Database\Schema\SchemaBuilder($this->db);
        if (!$schema->hasTable('users')) {
            $schema->create('users', function ($table) {
                $table->id();
                $table->string('name');
                $table->string('email');
                $table->string('password');
            });
        }
    }

    protected function tearDown(): void
    {
        $schema = new \Lukman\Database\Schema\SchemaBuilder($this->db);
        if ($schema->hasTable('users')) {
            // Drop table not strictly supported in current basic builder, but for testing sqlite memory is fine
        }
    }

    public function testCanInstantiateUserModel(): void
    {
        $user = new User($this->db);
        $this->assertInstanceOf(User::class, $user);
    }
}
