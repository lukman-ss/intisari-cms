<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\ConnectionFactory;
use PDO;

class MenuRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = ConnectionFactory::make();
    }

    public function all(): array
    {
        $stmt = $this->db->query("SELECT * FROM menus ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM menus WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO menus (name, location) VALUES (?, ?)");
        $stmt->execute([$data['name'], $data['location'] ?? null]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE menus SET name = ?, location = ? WHERE id = ?");
        return $stmt->execute([$data['name'], $data['location'] ?? null, $id]);
    }

    public function getItems(int $menuId): array
    {
        $stmt = $this->db->prepare("SELECT * FROM menu_items WHERE menu_id = ? ORDER BY order_index ASC");
        $stmt->execute([$menuId]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function addItem(int $menuId, array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO menu_items (menu_id, parent_id, title, url, order_index) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $menuId,
            $data['parent_id'] ?? 0,
            $data['title'],
            $data['url'],
            $data['order_index'] ?? 0
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function updateItem(int $itemId, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE menu_items 
            SET parent_id = ?, title = ?, url = ?, order_index = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['parent_id'] ?? 0,
            $data['title'],
            $data['url'],
            $data['order_index'] ?? 0,
            $itemId
        ]);
    }

    public function deleteItem(int $itemId): bool
    {
        $stmt = $this->db->prepare("DELETE FROM menu_items WHERE id = ?");
        return $stmt->execute([$itemId]);
    }
}
