<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\ConnectionFactory;
use PDO;

class CommentRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = ConnectionFactory::make();
    }

    public function paginateAdmin(int $page = 1, int $perPage = 20, string $status = '', string $search = ''): array
    {
        $offset = ($page - 1) * $perPage;
        
        $where = "WHERE 1=1";
        $params = [];
        
        if ($status !== '') {
            $where .= " AND c.status = ?";
            $params[] = $status;
        }

        if ($search !== '') {
            $where .= " AND (c.author_name LIKE ? OR c.author_email LIKE ? OR c.content LIKE ?)";
            $searchParam = '%' . $search . '%';
            $params[] = $searchParam;
            $params[] = $searchParam;
            $params[] = $searchParam;
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM comments c $where");
        $stmt->execute($params);
        $total = (int)$stmt->fetchColumn();

        $stmt = $this->db->prepare("
            SELECT c.*, p.title as post_title 
            FROM comments c 
            LEFT JOIN posts p ON c.post_id = p.id 
            $where 
            ORDER BY c.created_at DESC 
            LIMIT ? OFFSET ?
        ");
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
        $stmt = $this->db->prepare("SELECT * FROM comments WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function getForPost(int $postId, string $status = 'approved'): array
    {
        $stmt = $this->db->prepare("SELECT * FROM comments WHERE post_id = ? AND status = ? ORDER BY created_at ASC");
        $stmt->execute([$postId, $status]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO comments (post_id, user_id, author_name, author_email, content, status) 
            VALUES (?, ?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['post_id'],
            $data['user_id'] ?? 0,
            $data['author_name'] ?? null,
            $data['author_email'] ?? null,
            $data['content'],
            $data['status'] ?? 'pending'
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function updateStatus(int $id, string $status): bool
    {
        $stmt = $this->db->prepare("UPDATE comments SET status = ? WHERE id = ?");
        return $stmt->execute([$status, $id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM comments WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
