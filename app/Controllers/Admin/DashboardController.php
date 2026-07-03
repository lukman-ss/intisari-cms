<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\Auth;
use App\Auth\AuthManager;
use App\Database\ConnectionFactory;
use App\Controllers\BaseController;
use Lukman\Http\Request;
use Lukman\Http\Response;

final class DashboardController extends BaseController
{
    public function index(Request $request): Response
    {
        $db = ConnectionFactory::make();

        $postCount    = (int)$db->query("SELECT COUNT(*) FROM posts WHERE type='post' AND status != 'trash'")->fetchColumn();
        $pageCount    = (int)$db->query("SELECT COUNT(*) FROM posts WHERE type='page' AND status != 'trash'")->fetchColumn();
        $userCount    = (int)$db->query("SELECT COUNT(*) FROM users")->fetchColumn();
        $commentCount = (int)$db->query("SELECT COUNT(*) FROM comments WHERE status='pending'")->fetchColumn();
        $mediaCount   = (int)$db->query("SELECT COUNT(*) FROM media")->fetchColumn();

        $recentPosts = $db->query(
            "SELECT p.id, p.title, p.status, p.created_at, u.username as author
             FROM posts p
             LEFT JOIN users u ON u.id = p.author_id
             WHERE p.type='post' AND p.status != 'trash'
             ORDER BY p.id DESC LIMIT 5"
        )->fetchAll(\PDO::FETCH_ASSOC);

        $recentComments = $db->query(
            "SELECT c.id, c.author_name, c.content, c.status, c.created_at, p.title as post_title
             FROM comments c
             LEFT JOIN posts p ON p.id = c.post_id
             ORDER BY c.created_at DESC LIMIT 5"
        )->fetchAll(\PDO::FETCH_ASSOC);

        $authUser = AuthManager::guard()->user();

        $html = app()->render('admin/dashboard', [
            'authUser'       => $authUser,
            'postCount'      => $postCount,
            'pageCount'      => $pageCount,
            'userCount'      => $userCount,
            'commentCount'   => $commentCount,
            'mediaCount'     => $mediaCount,
            'recentPosts'    => $recentPosts,
            'recentComments' => $recentComments,
        ]);

        return app()->render('layouts/admin', [
            'title'   => 'Dashboard',
            'content' => $html,
        ]);
    }
}
