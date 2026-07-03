<?php

declare(strict_types=1);

namespace App\Auth;

use App\Database\ConnectionFactory;

class ApiTokenManager
{
    public function createToken(int $userId, string $name): array
    {
        $plainTextToken = bin2hex(random_bytes(32));
        $tokenHash = hash('sha256', $plainTextToken);
        
        $db = ConnectionFactory::make();
        $stmt = $db->prepare("INSERT INTO api_tokens (user_id, name, token_hash) VALUES (?, ?, ?)");
        $stmt->execute([$userId, $name, $tokenHash]);
        
        return [
            'id' => (int)$db->lastInsertId(),
            'plainTextToken' => $plainTextToken
        ];
    }

    public function revokeToken(int $tokenId, int $userId): bool
    {
        $db = ConnectionFactory::make();
        $stmt = $db->prepare("DELETE FROM api_tokens WHERE id = ? AND user_id = ?");
        $stmt->execute([$tokenId, $userId]);
        return $stmt->rowCount() > 0;
    }

    public function getTokensForUser(int $userId): array
    {
        $db = ConnectionFactory::make();
        $stmt = $db->prepare("SELECT * FROM api_tokens WHERE user_id = ? ORDER BY created_at DESC");
        $stmt->execute([$userId]);
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public function authenticate(string $plainTextToken): ?int
    {
        $tokenHash = hash('sha256', $plainTextToken);
        $db = ConnectionFactory::make();
        $stmt = $db->prepare("SELECT id, user_id FROM api_tokens WHERE token_hash = ?");
        $stmt->execute([$tokenHash]);
        $row = $stmt->fetch(\PDO::FETCH_ASSOC);
        
        if ($row) {
            $update = $db->prepare("UPDATE api_tokens SET last_used_at = CURRENT_TIMESTAMP WHERE id = ?");
            $update->execute([$row['id']]);
            return (int)$row['user_id'];
        }
        
        return null;
    }
}
