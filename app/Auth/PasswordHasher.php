<?php

declare(strict_types=1);

namespace App\Auth;

class PasswordHasher
{
    public function make(string $password): string
    {
        return password_hash($password, PASSWORD_DEFAULT);
    }

    public function check(string $password, string $hash): bool
    {
        return password_verify($password, $hash);
    }

    public function needsRehash(string $hash): bool
    {
        return password_needs_rehash($hash, PASSWORD_DEFAULT);
    }
}
