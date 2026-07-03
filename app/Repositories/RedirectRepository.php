<?php
declare(strict_types=1);

namespace App\Repositories;

use App\Database\ConnectionFactory;
use App\Models\Redirect;
use PDO;

class RedirectRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = ConnectionFactory::make();
    }

    public function paginate(int $page = 1, int $perPage = 20, string $search = ''): array
    {
        $offset = ($page - 1) * $perPage;
        
        $where = "";
        $params = [];
        
        if ($search !== '') {
            $where = "WHERE source_url LIKE ? OR target_url LIKE ?";
            $searchParam = '%' . $search . '%';
            $params = [$searchParam, $searchParam];
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM redirects $where");
        $stmt->execute($params);
        $total = (int)$stmt->fetchColumn();

        $stmt = $this->db->prepare("SELECT * FROM redirects $where ORDER BY id DESC LIMIT ? OFFSET ?");
        $stmt->execute([...$params, $perPage, $offset]);
        
        $items = array_map(fn($row) => new Redirect($row), $stmt->fetchAll(PDO::FETCH_ASSOC));

        return [
            'data' => $items,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'last_page' => (int)ceil($total / $perPage),
        ];
    }

    public function find(int $id): ?Redirect
    {
        $stmt = $this->db->prepare("SELECT * FROM redirects WHERE id = ?");
        $stmt->execute([$id]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? new Redirect($data) : null;
    }

    public function findBySource(string $source): ?Redirect
    {
        $stmt = $this->db->prepare("SELECT * FROM redirects WHERE source_url = ?");
        $stmt->execute([$source]);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        
        return $data ? new Redirect($data) : null;
    }

    public function incrementHits(int $id): void
    {
        $stmt = $this->db->prepare("UPDATE redirects SET hits = hits + 1 WHERE id = ?");
        $stmt->execute([$id]);
    }

    public function create(array $data): int
    {
        $stmt = $this->db->prepare("INSERT INTO redirects (source_url, target_url, type) VALUES (?, ?, ?)");
        $stmt->execute([
            $data['source_url'],
            $data['target_url'],
            $data['type'] ?? 301
        ]);
        return (int)$this->db->lastInsertId();
    }

    public function update(int $id, array $data): bool
    {
        $stmt = $this->db->prepare("UPDATE redirects SET source_url = ?, target_url = ?, type = ? WHERE id = ?");
        return $stmt->execute([
            $data['source_url'],
            $data['target_url'],
            $data['type'] ?? 301,
            $id
        ]);
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM redirects WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
