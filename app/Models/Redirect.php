<?php
declare(strict_types=1);

namespace App\Models;

class Redirect
{
    public int $id;
    public string $source_url;
    public string $target_url;
    public int $type;
    public int $hits;
    public string $created_at;

    public function __construct(array $data)
    {
        $this->id = (int)($data['id'] ?? 0);
        $this->source_url = $data['source_url'] ?? '';
        $this->target_url = $data['target_url'] ?? '';
        $this->type = (int)($data['type'] ?? 301);
        $this->hits = (int)($data['hits'] ?? 0);
        $this->created_at = $data['created_at'] ?? '';
    }
}
