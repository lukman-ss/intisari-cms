<?php

declare(strict_types=1);

namespace App\Themes;

class Theme
{
    public function __construct(
        public readonly string $path,
        public readonly string $name,
        public readonly string $slug,
        public readonly string $version,
        public readonly string $author,
        public readonly string $description,
        public readonly array $supports = []
    ) {}

    public static function fromJson(string $path, array $data): self
    {
        return new self(
            $path,
            $data['name'] ?? 'Unknown Theme',
            $data['slug'] ?? basename($path),
            $data['version'] ?? '0.0.1',
            $data['author'] ?? 'Unknown',
            $data['description'] ?? '',
            $data['supports'] ?? []
        );
    }
    
    public function getViewsPath(): string
    {
        return $this->path . DIRECTORY_SEPARATOR . 'views';
    }
}
