<?php

declare(strict_types=1);

namespace App\Support;

class PostStatus
{
    public const DRAFT = 'draft';
    public const PENDING = 'pending';
    public const PUBLISHED = 'published';
    public const PRIVATE = 'private';
    public const TRASH = 'trash';

    public static function all(): array
    {
        return [
            self::DRAFT,
            self::PENDING,
            self::PUBLISHED,
            self::PRIVATE,
            self::TRASH,
        ];
    }
}
