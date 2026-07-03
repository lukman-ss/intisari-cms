<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\ConnectionFactory;
use PDO;

class TermRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = ConnectionFactory::make();
    }

    public function paginate(string $taxonomy, int $page = 1, int $perPage = 20, string $search = ''): array
    {
        $offset = ($page - 1) * $perPage;
        
        $where = "WHERE taxonomy = ?";
        $params = [$taxonomy];
        
        if ($search !== '') {
            $where .= " AND (name LIKE ? OR description LIKE ?)";
            $searchParam = '%' . $search . '%';
            $params[] = $searchParam;
            $params[] = $searchParam;
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM terms $where");
        $stmt->execute($params);
        $total = (int)$stmt->fetchColumn();

        $stmt = $this->db->prepare("
            SELECT t.*, (
                SELECT COUNT(*) FROM term_relationships tr 
                JOIN posts p ON p.id = tr.object_id
                WHERE tr.term_id = t.id AND p.type = 'post'
            ) as count 
            FROM terms t $where 
            ORDER BY t.name ASC 
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
        $stmt = $this->db->prepare("SELECT * FROM terms WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("
            INSERT INTO terms (name, slug, taxonomy, description, seo_metadata) 
            VALUES (?, ?, ?, ?, ?)
        ");
        $stmt->execute([
            $data['name'],
            $data['slug'],
            $data['taxonomy'],
            $data['description'] ?? '',
            $data['seo_metadata'] ?? null
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("
            UPDATE terms 
            SET name = ?, slug = ?, description = ?, seo_metadata = ?
            WHERE id = ?
        ");
        return $stmt->execute([
            $data['name'],
            $data['slug'],
            $data['description'] ?? '',
            $data['seo_metadata'] ?? null,
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM term_relationships WHERE term_id = ?");
        $stmt->execute([$id]);

        $stmt = $this->db->prepare("DELETE FROM terms WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
     * Get all terms for a given post.
     */
    public function getTermsForPost(int $postId, string $taxonomy = ''): array
    {
        $sql = "SELECT t.* FROM terms t 
                JOIN term_relationships tr ON tr.term_id = t.id 
                WHERE tr.object_id = ?";
        $params = [$postId];

        if ($taxonomy !== '') {
            $sql .= " AND t.taxonomy = ?";
            $params[] = $taxonomy;
        }

        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Sync terms for a post (replace existing relationships).
     */
    public function syncTerms(int $postId, array $termIds): void
    {
        $stmt = $this->db->prepare("DELETE FROM term_relationships WHERE object_id = ?");
        $stmt->execute([$postId]);

        foreach ($termIds as $termId) {
            $termId = (int)$termId;
            if ($termId <= 0) continue;
            $stmt = $this->db->prepare("INSERT OR IGNORE INTO term_relationships (object_id, term_id) VALUES (?, ?)");
            $stmt->execute([$postId, $termId]);
        }
    }

    /**
     * Get all terms by taxonomy (no pagination).
     */
    public function allByTaxonomy(string $taxonomy): array
    {
        $stmt = $this->db->prepare("SELECT * FROM terms WHERE taxonomy = ? ORDER BY name ASC");
        $stmt->execute([$taxonomy]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
