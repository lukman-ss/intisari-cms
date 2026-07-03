<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\ConnectionFactory;
use App\Models\Post;
use PDO;

class PostRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = ConnectionFactory::make();
    }

    public function find(int $id): ?Post
    {
        $stmt = $this->db->prepare("SELECT posts.*, media.filename as featured_image_url 
                                    FROM posts 
                                    LEFT JOIN media ON posts.featured_image_id = media.id 
                                    WHERE posts.id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? new Post($data) : null;
    }

    public function findBySlug(string $slug, string $type = 'post'): ?Post
    {
        $stmt = $this->db->prepare("SELECT posts.*, media.filename as featured_image_url 
                                    FROM posts 
                                    LEFT JOIN media ON posts.featured_image_id = media.id 
                                    WHERE posts.slug = ? AND posts.type = ?");
        $stmt->execute([$slug, $type]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? new Post($data) : null;
    }

    public function paginate(string $type = 'post', int $page = 1, int $perPage = 20, string $search = '', string $status = ''): array
    {
        $offset = ($page - 1) * $perPage;
        
        $params = [$type];
        if ($status !== '') {
            $where = "WHERE posts.type = ? AND posts.status = ?";
            $params[] = $status;
        } else {
            $where = "WHERE posts.type = ? AND posts.status != 'trash'";
        }
        
        if ($search !== '') {
            $where .= " AND (posts.title LIKE ? OR posts.content LIKE ?)";
            $searchParam = '%' . $search . '%';
            $params[] = $searchParam;
            $params[] = $searchParam;
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM posts $where");
        $stmt->execute($params);
        $total = (int)$stmt->fetchColumn();

        $stmt = $this->db->prepare("SELECT posts.*, media.filename as featured_image_url 
                                    FROM posts 
                                    LEFT JOIN media ON posts.featured_image_id = media.id 
                                    $where ORDER BY posts.id DESC LIMIT ? OFFSET ?");
        $stmt->execute([...$params, $perPage, $offset]);
        
        $items = array_map(fn($row) => new Post($row), $stmt->fetchAll(PDO::FETCH_ASSOC));

        return [
            'data' => $items,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => (int)ceil($total / $perPage),
        ];
    }

    public function create(array $data): int
    {
        $fields = array_keys($data);
        $placeholders = implode(', ', array_fill(0, count($fields), '?'));
        $columns = implode(', ', $fields);
        
        $sql = "INSERT INTO posts ($columns, created_at, updated_at) VALUES ($placeholders, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute(array_values($data));
        
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        (new \App\Repositories\RevisionRepository())->createRevision($id);

        $fields = [];
        $values = [];
        foreach ($data as $key => $val) {
            $fields[] = "$key = ?";
            $values[] = $val;
        }
        
        $values[] = $id;
        $set = implode(', ', $fields);
        
        $stmt = $this->db->prepare("UPDATE posts SET $set, updated_at = CURRENT_TIMESTAMP WHERE id = ?");
        return $stmt->execute($values);
    }

    public function trash(int $id): bool
    {
        $stmt = $this->db->prepare("UPDATE posts SET status = 'trash' WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM posts WHERE id = ?");
        return $stmt->execute([$id]);
    }

    public function getPublishedPostsByType(string $type): array
    {
        $stmt = $this->db->prepare("SELECT slug, updated_at, created_at, seo_metadata 
                                    FROM posts 
                                    WHERE type = ? AND status = 'published' 
                                    ORDER BY updated_at DESC");
        $stmt->execute([$type]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
