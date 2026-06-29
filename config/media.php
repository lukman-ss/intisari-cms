<?php

declare(strict_types=1);

$env = function_exists('env') ? 'env' : function ($key, $default = null) {
    $val = getenv($key);
    return $val !== false ? $val : $default;
};

return [
    'upload_path' => $env('CMS_UPLOAD_PATH', 'storage/uploads'),
    'allowed_extensions' => ['jpg', 'jpeg', 'png', 'gif', 'pdf', 'zip'],
    'max_file_size' => 10240, // KB
    'thumbnail_sizes' => [
        'small' => [150, 150],
        'medium' => [300, 300],
        'large' => [1024, 1024],
    ],
];
