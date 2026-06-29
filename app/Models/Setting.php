<?php

declare(strict_types=1);

namespace App\Models;

use Lukman\Database\Connection;
use Lukman\Database\QueryBuilder;

final class Setting
{
    public function __construct(private readonly Connection $db)
    {
    }

    private function query(): QueryBuilder
    {
        return (new QueryBuilder($this->db))->table('settings');
    }

    /**
     * Get a setting value by key.
     */
    public function get(string $key, mixed $default = null): mixed
    {
        $row = $this->query()->where('key', $key)->first();

        return $row !== null ? $row['value'] : $default;
    }

    /**
     * Set (insert or update) a setting value.
     */
    public function set(string $key, mixed $value, string $group = 'general'): void
    {
        $existing = $this->query()->where('key', $key)->first();
        $now = date('Y-m-d H:i:s');

        if ($existing !== null) {
            $this->query()->where('key', $key)->update([
                'value'      => (string) $value,
                'updated_at' => $now,
            ]);
        } else {
            $this->query()->insert([
                'key'        => $key,
                'value'      => (string) $value,
                'group'      => $group,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
    }

    /** @return list<array<string, mixed>> */
    public function all(): array
    {
        return $this->query()->orderBy('group')->orderBy('key')->get();
    }

    /** @return list<array<string, mixed>> */
    public function byGroup(string $group): array
    {
        return $this->query()->where('group', $group)->orderBy('key')->get();
    }
}
