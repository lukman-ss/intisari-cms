<?php

declare(strict_types=1);

namespace App\Repositories;

use App\Database\ConnectionFactory;
use PDO;

class OptionRepository
{
    private PDO $db;

    public function __construct()
    {
        $this->db = ConnectionFactory::make();
    }

    public function get(string $name, mixed $default = null): mixed
    {
        $stmt = $this->db->prepare("SELECT option_value FROM options WHERE option_name = ?");
        $stmt->execute([$name]);
        $value = $stmt->fetchColumn();

        if ($value === false) {
            return $default;
        }

        return $value;
    }

    public function set(string $name, string $value): bool
    {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM options WHERE option_name = ?");
        $stmt->execute([$name]);
        $exists = (bool)$stmt->fetchColumn();

        if ($exists) {
            $stmt = $this->db->prepare("UPDATE options SET option_value = ? WHERE option_name = ?");
            return $stmt->execute([$value, $name]);
        }

        $stmt = $this->db->prepare("INSERT INTO options (option_name, option_value) VALUES (?, ?)");
        return $stmt->execute([$name, $value]);
    }
    
    public function getMany(array $names): array
    {
        if (empty($names)) {
            return [];
        }
        $placeholders = implode(',', array_fill(0, count($names), '?'));
        $stmt = $this->db->prepare("SELECT option_name, option_value FROM options WHERE option_name IN ($placeholders)");
        $stmt->execute($names);
        
        $results = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $results[$row['option_name']] = $row['option_value'];
        }
        
        return $results;
    }
}
