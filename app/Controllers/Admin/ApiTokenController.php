<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\ApiTokenManager;
use App\Auth\AuthManager;
use App\Auth\Capability;
use App\Auth\CapabilityChecker;
use App\Support\Flash;
use App\Support\Redirect;
use Lukman\Http\Request;
use Lukman\Http\Response;

class ApiTokenController
{
    private ApiTokenManager $manager;

    public function __construct()
    {
        $this->manager = new ApiTokenManager();
    }

    public function index(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            Flash::set('error', 'You do not have permission.');
            return Redirect::to('/admin/dashboard');
        }

        $userId = AuthManager::guard()->id();
        $tokens = $this->manager->getTokensForUser($userId);
        
        $newPlainTextToken = Flash::get('new_token');

        $content = app()->render('admin/tools/api-tokens', [
            'tokens' => $tokens,
            'newPlainTextToken' => $newPlainTextToken
        ]);

        return app()->render('layouts/admin', [
            'title' => 'API Tokens',
            'content' => $content
        ]);
    }

    public function store(Request $request): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $name = trim($_POST['name'] ?? '');
        if ($name === '') {
            Flash::set('error', 'Token name is required.');
            return Redirect::back('/admin/tools/api-tokens');
        }

        $userId = AuthManager::guard()->id();
        $token = $this->manager->createToken($userId, $name);
        
        Flash::set('success', 'Token created successfully.');
        Flash::set('new_token', $token['plainTextToken']);
        
        return Redirect::back('/admin/tools/api-tokens');
    }

    public function revoke(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $userId = AuthManager::guard()->id();
        
        if ($this->manager->revokeToken((int)$id, $userId)) {
            Flash::set('success', 'Token revoked.');
        } else {
            Flash::set('error', 'Could not revoke token.');
        }
        
        return Redirect::back('/admin/tools/api-tokens');
    }
}
