<?php

declare(strict_types=1);

namespace App\Validation;

use App\Repositories\UserRepository;

class UserValidator
{
    private UserRepository $repo;

    public function __construct()
    {
        $this->repo = new UserRepository();
    }

    public function validate(array $data, ?int $exceptId = null): array
    {
        $errors = [];

        if (empty($data['username'])) {
            $errors[] = 'Username is required.';
        } elseif ($this->repo->findByUsername($data['username'], $exceptId)) {
            $errors[] = 'Username is already taken.';
        }

        if (empty($data['email'])) {
            $errors[] = 'Email is required.';
        } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            $errors[] = 'Email is invalid.';
        } elseif ($this->repo->findByEmail($data['email'], $exceptId)) {
            $errors[] = 'Email is already taken.';
        }

        if ($exceptId === null && empty($data['password'])) {
            $errors[] = 'Password is required for new users.';
        }

        return $errors;
    }
}
