<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\ConnectionFactory;
use PDO;

class MediaRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = ConnectionFactory::make();
    }

    public function paginate(int $page = 1, int $perPage = 20, string $search = '', string $mimeType = ''): array
    {
        $offset = ($page - 1) * $perPage;
        
        $where = "WHERE 1=1";
        $params = [];
        
        if ($mimeType !== '') {
            $where .= " AND mime_type LIKE ?";
            $params[] = $mimeType . '%';
        }
        
        if ($search !== '') {
            $where .= " AND (filename LIKE ? OR metadata LIKE ?)";
            $searchParam = '%' . $search . '%';
            $params[] = $searchParam;
            $params[] = $searchParam;
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM media $where");
        $stmt->execute($params);
        $total = (int)$stmt->fetchColumn();

        $stmt = $this->db->prepare("SELECT * FROM media $where ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->execute([...$params, $perPage, $offset]);
        
        $items = $stmt->fetchAll(PDO::FETCH_ASSOC);

        return [
            'data' => $items,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => (int)ceil($total / $perPage),
        ];
    }

    public function find(int $id): ?array
    {
        $stmt = $this->db->prepare("SELECT * FROM media WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO media (user_id, filename, mime_type, size, metadata) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['user_id'],
            $data['filename'],
            $data['mime_type'],
            $data['size'],
            $data['metadata'] ?? '{}'
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE media SET metadata = ? WHERE id = ?");
        return $stmt->execute([$data['metadata'] ?? '{}', $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM media WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
