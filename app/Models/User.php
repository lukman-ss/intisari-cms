<?php

declare(strict_types=1);

namespace App\Models;

use Lukman\Database\Connection;
use Lukman\Database\QueryBuilder;

/**
 * User model — thin repository over the users table.
 */
final class User
{
    public function __construct(private readonly Connection $db)
    {
    }

    private function query(): QueryBuilder
    {
        return (new QueryBuilder($this->db))->table('users');
    }

    /** @return array<string, mixed>|null */
    public function find(int $id): ?array
    {
        return $this->query()->where('id', $id)->first();
    }

    /** @return array<string, mixed>|null */
    public function findByEmail(string $email): ?array
    {
        return $this->query()->where('email', $email)->first();
    }

    /** @return list<array<string, mixed>> */
    public function all(): array
    {
        return $this->query()->orderBy('id')->get();
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        $data['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
        $data['updated_at'] = $data['updated_at'] ?? date('Y-m-d H:i:s');

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        $id = (int) $this->query()->insertGetId($data);

        return $this->find($id) ?? [];
    }

    /**
     * @param array<string, mixed> $data
     */
    public function update(int $id, array $data): int
    {
        $data['updated_at'] = date('Y-m-d H:i:s');

        if (isset($data['password'])) {
            $data['password'] = password_hash($data['password'], PASSWORD_BCRYPT);
        }

        return $this->query()->where('id', $id)->update($data);
    }

    public function delete(int $id): int
    {
        return $this->query()->where('id', $id)->delete();
    }

    /**
     * Verify a plain-text password against the stored hash.
     *
     * @param array<string, mixed> $user
     */
    public function verifyPassword(array $user, string $plainPassword): bool
    {
        return password_verify($plainPassword, (string) $user['password']);
    }
}
