<?php

declare(strict_types=1);

namespace App\Models;

use Lukman\Database\Connection;
use Lukman\Database\QueryBuilder;

final class Media
{
    public function __construct(private readonly Connection $db)
    {
    }

    private function query(): QueryBuilder
    {
        return (new QueryBuilder($this->db))->table('media');
    }

    /** @return array<string, mixed>|null */
    public function find(int $id): ?array
    {
        return $this->query()->where('id', $id)->first();
    }

    /** @return list<array<string, mixed>> */
    public function all(): array
    {
        return $this->query()->orderBy('created_at', 'desc')->get();
    }

    /**
     * @param array<string, mixed> $data
     * @return array<string, mixed>
     */
    public function create(array $data): array
    {
        $data['created_at'] = $data['created_at'] ?? date('Y-m-d H:i:s');
        $id = (int) $this->query()->insertGetId($data);

        return $this->find($id) ?? [];
    }

    public function delete(int $id): int
    {
        return $this->query()->where('id', $id)->delete();
    }
}
