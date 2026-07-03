<?php

declare(strict_types=1);

namespace App\Security;

class LoginRateLimiter
{
    private string $sessionKey = 'login_attempts';
    private int $maxAttempts = 5;
    private int $lockoutTime = 300; // 5 minutes

    public function __construct()
    {
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    public function check(): bool
    {
        $attempts = $_SESSION[$this->sessionKey] ?? [];
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        
        if (!isset($attempts[$ip])) {
            return true;
        }

        if ($attempts[$ip]['count'] >= $this->maxAttempts) {
            if (time() - $attempts[$ip]['last_time'] < $this->lockoutTime) {
                return false;
            } else {
                $attempts[$ip] = ['count' => 0, 'last_time' => time()];
                $_SESSION[$this->sessionKey] = $attempts;
                return true;
            }
        }

        return true;
    }

    public function hit(): void
    {
        $attempts = $_SESSION[$this->sessionKey] ?? [];
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        
        if (!isset($attempts[$ip])) {
            $attempts[$ip] = ['count' => 0, 'last_time' => time()];
        }
        
        if (time() - $attempts[$ip]['last_time'] >= $this->lockoutTime) {
            $attempts[$ip]['count'] = 1;
        } else {
            $attempts[$ip]['count']++;
        }
        
        $attempts[$ip]['last_time'] = time();
        $_SESSION[$this->sessionKey] = $attempts;
    }

    public function clear(): void
    {
        $attempts = $_SESSION[$this->sessionKey] ?? [];
        $ip = $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0';
        unset($attempts[$ip]);
        $_SESSION[$this->sessionKey] = $attempts;
    }
}
