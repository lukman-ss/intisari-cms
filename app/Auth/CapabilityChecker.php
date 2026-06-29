<?php

declare(strict_types=1);

namespace App\Auth;

use App\Database\ConnectionFactory;
use PDO;

class CapabilityChecker
{
    public static function has(int $userId, string $capability): bool
    {
        if ($userId === 1) {
            return true;
        }

        $pdo = ConnectionFactory::make();
        
        $stmt = $pdo->prepare("
            SELECT r.name 
            FROM roles r
            JOIN role_user ru ON ru.role_id = r.id
            WHERE ru.user_id = ?
        ");
        $stmt->execute([$userId]);
        $roles = $stmt->fetchAll(PDO::FETCH_COLUMN);
        
        if (empty($roles)) {
            return false;
        }

        if (in_array('administrator', $roles, true)) {
            return true;
        }

        $placeholders = implode(',', array_fill(0, count($roles), '?'));
        $stmt = $pdo->prepare("
            SELECT COUNT(*) 
            FROM permissions p
            JOIN permission_role pr ON pr.permission_id = p.id
            JOIN roles r ON r.id = pr.role_id
            WHERE r.name IN ($placeholders) AND p.name = ?
        ");
        
        $params = [...$roles, $capability];
        $stmt->execute($params);
        $count = (int)$stmt->fetchColumn();

        return $count > 0;
    }

    public static function checkCurrentUser(string $capability): bool
    {
        $guard = AuthManager::guard();
        if (!$guard->check()) {
            return false;
        }

        $userId = $guard->id();
        return self::has($userId, $capability);
    }
}
