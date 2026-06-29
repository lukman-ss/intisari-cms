<?php

declare(strict_types=1);

return [
    'user_table' => 'users',
    'session_key' => 'auth_user_id',
    'password_column' => 'password',
    'remember_token_column' => 'remember_token',
    'login_path' => '/login',
    'logout_path' => '/logout',
    'after_login_path' => '/admin',
];
