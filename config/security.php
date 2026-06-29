<?php

declare(strict_types=1);

return [
    'csrf_enabled' => true,
    'csrf_token_name' => '_token',
    'csrf_session_key' => 'csrf_token',
    'password_min_length' => 8,
    'login_rate_limit' => 5, // max attempts
];
