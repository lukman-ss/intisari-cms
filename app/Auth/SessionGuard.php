<?php

declare(strict_types=1);

namespace App\Auth;

use App\Database\ConnectionFactory;
use PDO;

class SessionGuard
{
    private PasswordHasher $hasher;
    private string $sessionKey = 'auth_user_id';
    private ?array $user = null;

    public function __construct()
    {
        $this->hasher = new PasswordHasher();
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function attempt(string $username, string $password): bool
    {
        $pdo = ConnectionFactory::make();
        $stmt = $pdo->prepare("SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1");
        $stmt->execute([$username, $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$user) {
            return false;
        }

        if ($this->hasher->check($password, $user['password'])) {
            $this->login($user);
            return true;
        }

        return false;
    }

    public function login(array $user): void
    {
        session_regenerate_id(true);
        $_SESSION[$this->sessionKey] = $user['id'];
        $this->user = $user;
    }

    public function loginUsingId(int $id): void
    {
        $_SESSION[$this->sessionKey] = $id;
        $this->user = null;
    }

    public function logout(): void
    {
        unset($_SESSION[$this->sessionKey]);
        $this->user = null;
        session_regenerate_id(true);
    }

    public function check(): bool
    {
        return isset($_SESSION[$this->sessionKey]);
    }

    public function id(): ?int
    {
        return $_SESSION[$this->sessionKey] ?? null;
    }

    public function user(): ?array
    {
        if ($this->user !== null) {
            return $this->user;
        }

        $id = $this->id();
        if ($id) {
            $pdo = ConnectionFactory::make();
            $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ? LIMIT 1");
            $stmt->execute([$id]);
            $this->user = $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        }

        return $this->user;
    }
}
