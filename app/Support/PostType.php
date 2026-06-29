<?php

declare(strict_types=1);

namespace App\Support;

class PostType
{
    public const POST = 'post';
    public const PAGE = 'page';
    public const ATTACHMENT = 'attachment';
    public const REVISION = 'revision';

    public static function all(): array
    {
        return [
            self::POST,
            self::PAGE,
            self::ATTACHMENT,
            self::REVISION,
        ];
    }
}
