<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\ConnectionFactory;
use PDO;

class RoleRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = ConnectionFactory::make();
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM roles ORDER BY id ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM roles WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getCapabilities(int $roleId): array
    {
        $stmt = $this->db->prepare("
            SELECT p.name 
            FROM permissions p
            JOIN permission_role pr ON pr.permission_id = p.id
            WHERE pr.role_id = ?
        ");
        $stmt->execute([$roleId]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function syncCapabilities(int $roleId, array $capabilities): void
    {
        $stmt = $this->db->prepare("DELETE FROM permission_role WHERE role_id = ?");
        $stmt->execute([$roleId]);

        foreach ($capabilities as $cap) {
            $stmt = $this->db->prepare("SELECT id FROM permissions WHERE name = ?");
            $stmt->execute([$cap]);
            $permId = $stmt->fetchColumn();
            
            if (!$permId) {
                $stmt = $this->db->prepare("INSERT INTO permissions (name, created_at) VALUES (?, CURRENT_TIMESTAMP)");
                $stmt->execute([$cap]);
                $permId = (int)$this->db->lastInsertId();
            }

            $stmt = $this->db->prepare("INSERT INTO permission_role (permission_id, role_id) VALUES (?, ?)");
            $stmt->execute([$permId, $roleId]);
        }
    }
}
