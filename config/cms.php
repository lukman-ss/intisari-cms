<?php

declare(strict_types=1);

$env = function_exists('env') ? 'env' : function ($key, $default = null) {
    $val = getenv($key);
    return $val !== false ? $val : $default;
};

return [
    'admin_path' => $env('CMS_ADMIN_PATH', 'admin'),
    'site_name' => $env('CMS_SITE_NAME', 'Intisari CMS'),
    'pagination_limit' => (int)$env('CMS_ITEMS_PER_PAGE', 20),
    'homepage_mode' => 'posts', // 'posts' or 'page'
    'posts_per_page' => 10,
    'default_post_status' => 'draft',
];
