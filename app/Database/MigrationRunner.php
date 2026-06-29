<?php
declare(strict_types=1);

namespace App\Database;

use PDO;

class MigrationRunner
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->createMigrationsTable();
    }

    private function createMigrationsTable(): void
    {
        $this->pdo->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                migration VARCHAR(255) NOT NULL,
                batch INTEGER NOT NULL
            );
        ");
    }

    public function run(string $path): array
    {
        $files = glob(rtrim($path, '/\\') . '/*.php');
        if (!$files) {
            return [];
        }
        sort($files);

        $stmt = $this->pdo->query("SELECT migration FROM migrations");
        $ran = $stmt->fetchAll(PDO::FETCH_COLUMN) ?: [];

        $batch = $this->getNextBatch();
        $executed = [];

        foreach ($files as $file) {
            $name = basename($file, '.php');
            if (!in_array($name, $ran, true)) {
                $migration = require $file;
                $sql = $migration->up();
                
                $this->pdo->exec($sql);
                
                $stmt = $this->pdo->prepare("INSERT INTO migrations (migration, batch) VALUES (?, ?)");
                $stmt->execute([$name, $batch]);
                
                $executed[] = $name;
            }
        }

        return $executed;
    }

    public function fresh(string $path): void
    {
        $stmt = $this->pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%'");
        $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        $this->pdo->exec('PRAGMA foreign_keys = OFF;');
        foreach ($tables as $table) {
            $this->pdo->exec("DROP TABLE IF EXISTS " . $table);
        }
        $this->pdo->exec('PRAGMA foreign_keys = ON;');
        
        $this->createMigrationsTable();
        $this->run($path);
    }

    private function getNextBatch(): int
    {
        $stmt = $this->pdo->query("SELECT MAX(batch) FROM migrations");
        $batch = (int)$stmt->fetchColumn();
        return $batch + 1;
    }
}
