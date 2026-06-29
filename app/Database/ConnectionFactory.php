<?php
declare(strict_types=1);

namespace App\Database;

use PDO;
use Intisari\Application;

class ConnectionFactory
{
    public static function make(): PDO
    {
        $app = Application::getGlobal();
        $dbPath = $app ? $app->config()->get('database.sqlite.database', 'database/cms.sqlite') : 'database/cms.sqlite';
        
        if (!str_starts_with($dbPath, '/') && !str_starts_with($dbPath, '\\') && $app) {
            $dbPath = $app->basePath($dbPath);
        }

        $dir = dirname($dbPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
        }

        $pdo = new PDO('sqlite:' . $dbPath);
        $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        
        return $pdo;
    }
}
