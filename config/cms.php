<?php

declare(strict_types=1);

$env = static fn (string $key, mixed $default = null): mixed => $_ENV[$key] ?? $_SERVER[$key] ?? getenv($key) ?: $default;

return [
    'title'       => $env('CMS_TITLE', 'Intisari CMS'),
    'description' => $env('CMS_DESCRIPTION', 'A minimal CMS powered by IntisariPHP.'),
    'per_page'    => (int) $env('CMS_PER_PAGE', 15),
    'upload_path' => $env('CMS_UPLOAD_PATH', 'storage/uploads'),
];
