<?php
declare(strict_types=1);

namespace App\Database;

use PDO;

class SeederRunner
{
    private PDO $pdo;

    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }

    public function run(string $path): array
    {
        $files = glob(rtrim($path, '/\\') . '/*.php');
        if (!$files) {
            return [];
        }
        
        $executed = [];
        foreach ($files as $file) {
            ob_start();
            $seeder = require_once $file;
            ob_end_clean();
            
            if (is_object($seeder) && method_exists($seeder, 'run')) {
                $seeder->run($this->pdo);
            }
            $executed[] = basename($file);
        }
        
        return $executed;
    }
}
