<?php

declare(strict_types=1);

namespace Tests\Models;

use App\Models\Setting;
use Intisari\Application;
use PHPUnit\Framework\TestCase;
use Lukman\Database\Connection;

class SettingModelTest extends TestCase
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
        $schema = new \Lukman\Database\Schema\SchemaBuilder($this->db);
        if (!$schema->hasTable('settings')) {
            $schema->create('settings', function ($table) {
                $table->id();
                $table->string('key');
                $table->text('value');
                $table->string('group_name');
            });
        }
    }

    protected function tearDown(): void
    {
        // Nothing to drop in memory DB
    }

    public function testCanInstantiateSettingModel(): void
    {
        $setting = new Setting($this->db);
        $this->assertInstanceOf(Setting::class, $setting);
    }
}
