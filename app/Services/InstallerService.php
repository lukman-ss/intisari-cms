<?php

declare(strict_types=1);

namespace App\Services;

use App\Database\ConnectionFactory;
use App\Database\MigrationRunner;
use App\Auth\PasswordHasher;
use Intisari\Application;

class InstallerService
{
    private string $lockFile;

    public function __construct()
    {
        $app = Application::getGlobal();
        $this->lockFile = $app ? $app->storagePath('installed.lock') : 'storage/installed.lock';
    }

    public function isInstalled(): bool
    {
        return file_exists($this->lockFile);
    }

    public function checkRequirements(): array
    {
        $app = Application::getGlobal();
        $storagePath = $app ? $app->storagePath() : 'storage';
        $dbPath = $app ? dirname($app->config()->get('database.sqlite.database', 'database/cms.sqlite')) : 'database';
        if ($app && !str_starts_with($dbPath, '/') && !str_starts_with($dbPath, '\\')) {
            $dbPath = $app->basePath($dbPath);
        }

        return [
            'php_version' => PHP_VERSION_ID >= 80200,
            'storage_writable' => is_writable($storagePath) || (@mkdir($storagePath, 0755, true) && is_writable($storagePath)),
            'database_dir_writable' => is_writable($dbPath) || (@mkdir($dbPath, 0755, true) && is_writable($dbPath)),
        ];
    }

    public function install(array $data): void
    {
        if ($this->isInstalled()) {
            throw new \RuntimeException('Already installed.');
        }

        $app = Application::getGlobal();
        if ($app === null) {
            throw new \RuntimeException('Application is not bootstrapped.');
        }

        $siteTitle = trim((string)($data['site_title'] ?? ''));
        $adminUsername = trim((string)($data['admin_username'] ?? ''));
        $adminEmail = trim((string)($data['admin_email'] ?? ''));
        $adminPassword = (string)($data['admin_password'] ?? '');

        if ($siteTitle === '' || $adminUsername === '' || $adminEmail === '' || $adminPassword === '') {
            throw new \InvalidArgumentException('All installation fields are required.');
        }

        if (!filter_var($adminEmail, FILTER_VALIDATE_EMAIL)) {
            throw new \InvalidArgumentException('A valid admin email is required.');
        }

        if (strlen($adminPassword) < 8) {
            throw new \InvalidArgumentException('Admin password must be at least 8 characters.');
        }

        $pdo = ConnectionFactory::make();
        
        $runner = new MigrationRunner($pdo);
        $runner->run($app->basePath('database/migrations'));

        $stmt = $pdo->prepare("INSERT INTO users (username, email, password, created_at, updated_at) VALUES (?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)");
        $password = (new PasswordHasher())->make($adminPassword);
        $stmt->execute([
            $adminUsername,
            $adminEmail,
            $password,
        ]);

        $stmt = $pdo->prepare("INSERT INTO options (option_name, option_value) VALUES (?, ?)");
        $stmt->execute(['site_name', $siteTitle]);

        file_put_contents($this->lockFile, date('Y-m-d H:i:s'));
    }
}
