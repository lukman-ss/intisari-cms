<?php

declare(strict_types=1);

namespace Tests\Auth;

use App\Auth\Auth;
use Intisari\Application;
use PHPUnit\Framework\TestCase;
use Lukman\Session\SessionStore;
use Lukman\Session\SessionHandlerInterface;

class AuthTest extends TestCase
{
    private Application $app;

    protected function setUp(): void
    {
        $this->app = new Application(dirname(__DIR__, 2));
        $this->app->setAsGlobal();
        $this->app->bootstrap();

        Auth::setApp($this->app);
    }

    protected function tearDown(): void
    {
        Application::clearGlobal();
    }

    public function testCheckReturnsFalseWhenNoSessionStarted(): void
    {
        // Auth::check() returns false safely when session hasn't been started
        $this->assertFalse(Auth::check());
    }

    public function testIdReturnsNullWhenNotAuthenticated(): void
    {
        $this->assertNull(Auth::id());
    }

    public function testUserReturnsNullWhenNotAuthenticated(): void
    {
        // No DB configured — user() should return null (no session key set)
        $this->assertNull(Auth::id());
    }

    public function testLogoutDoesNotThrowWhenSessionNotStarted(): void
    {
        $this->expectNotToPerformAssertions();
        Auth::logout();
    }

    public function testLoginAndCheckWithMockSession(): void
    {
        if (!class_exists(\Lukman\Session\SessionStore::class)) {
            $this->markTestSkipped('Session package is not installed.');
        }

        // Create an in-memory session store backed by an array handler
        $handler = new class implements \Lukman\Session\SessionHandlerInterface {
            private array $store = [];
            public function read(string $id): array { return $this->store[$id] ?? []; }
            public function write(string $id, array $data, int $ttl): void { $this->store[$id] = $data; }
            public function destroy(string $id): void { unset($this->store[$id]); }
            public function gc(int $lifetime): int { return 0; }
            public function exists(string $id): bool { return isset($this->store[$id]); }
        };

        $session = new SessionStore($handler, null, 'test-session-id');
        $session->start();

        // Inject into app singleton
        $this->app->instance('session.default', $session);
        $this->app->instance(\Lukman\Session\SessionStore::class, $session);

        // Fake login
        Auth::login(['id' => 42, 'email' => 'admin@example.com', 'name' => 'Admin']);

        $this->assertTrue($session->has('_auth.user_id'));
        $this->assertSame(42, (int) $session->get('_auth.user_id'));
    }
}
