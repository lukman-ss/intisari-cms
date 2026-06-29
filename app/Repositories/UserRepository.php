<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\ConnectionFactory;
use PDO;

class UserRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = ConnectionFactory::make();
    }

    public function paginate(int $page = 1, int $perPage = 20, string $search = ''): array
    {
        $offset = ($page - 1) * $perPage;
        
        $where = '';
        $params = [];
        if ($search !== '') {
            $where = "WHERE username LIKE ? OR email LIKE ?";
            $searchParam = '%' . $search . '%';
            $params = [$searchParam, $searchParam];
        }

        $stmt = $this->db->prepare("SELECT COUNT(*) FROM users $where");
        $stmt->execute($params);
        $total = (int)$stmt->fetchColumn();

        $stmt = $this->db->prepare("SELECT * FROM users $where ORDER BY id DESC LIMIT ? OFFSET ?");
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
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByUsername(string $username, ?int $exceptId = null): ?array
    {
        $sql = "SELECT * FROM users WHERE username = ?";
        $params = [$username];
        if ($exceptId !== null) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function findByEmail(string $email, ?int $exceptId = null): ?array
    {
        $sql = "SELECT * FROM users WHERE email = ?";
        $params = [$email];
        if ($exceptId !== null) {
            $sql .= " AND id != ?";
            $params[] = $exceptId;
        }
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    public function create(array $data): bool
    {
        $stmt = $this->db->prepare("
            INSERT INTO users (username, email, password, created_at, updated_at) 
            VALUES (?, ?, ?, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
        ");
        return $stmt->execute([
            $data['username'],
            $data['email'],
            $data['password']
        ]);
    }

    public function update(int $id, array $data): bool
    {
        if (isset($data['password'])) {
            $stmt = $this->db->prepare("
                UPDATE users SET username = ?, email = ?, password = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?
            ");
            return $stmt->execute([
                $data['username'],
                $data['email'],
                $data['password'],
                $id
            ]);
        } else {
            $stmt = $this->db->prepare("
                UPDATE users SET username = ?, email = ?, updated_at = CURRENT_TIMESTAMP WHERE id = ?
            ");
            return $stmt->execute([
                $data['username'],
                $data['email'],
                $id
            ]);
        }
    }

    public function delete(int $id): bool
    {
        $stmt = $this->db->prepare("DELETE FROM users WHERE id = ?");
        return $stmt->execute([$id]);
    }
    
    public function countAdmins(): int
    {
        $stmt = $this->db->query("SELECT COUNT(*) FROM users");
        return (int)$stmt->fetchColumn();
    }
}
