<?php

declare(strict_types=1);

namespace App\Plugins;

class Plugin
{
    public function __construct(
        public readonly string $path,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $version,
        public readonly string $author,
        public readonly string $description,
        public readonly string $entry
    ) {}

    public static function fromJson(string $path, array $data): self
    {
        return new self(
            $path,
            $data['name'] ?? 'Unknown Plugin',
            $data['slug'] ?? basename($path),
            $data['version'] ?? '0.0.1',
            $data['author'] ?? 'Unknown',
            $data['description'] ?? '',
            $data['entry'] ?? 'plugin.php'
        );
    }
    
    public function getEntryFile(): string
    {
        return $this->path . DIRECTORY_SEPARATOR . ltrim($this->entry, '/\\');
    }
}
