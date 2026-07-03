<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\Capability;
use App\Auth\CapabilityChecker;
use App\Repositories\CommentRepository;
use App\Support\Flash;
use App\Support\Redirect;
use Lukman\Http\Request;
use Lukman\Http\Response;

class CommentController
{
    private CommentRepository $repo;

    public function __construct()
    {
        $this->repo = new CommentRepository();
    }

    public function index(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MODERATE_COMMENTS)) {
            Flash::set('error', 'You do not have permission.');
            return Redirect::to('/admin/dashboard');
        }

        $page = (int)($_GET['page'] ?? 1);
        $status = $_GET['status'] ?? '';
        $search = $_GET['search'] ?? '';

        $paginator = $this->repo->paginateAdmin($page, 20, (string)$status, (string)$search);

        $content = app()->render('admin/comments/index', [
            'comments' => $paginator['data'],
            'status' => $status,
            'search' => $search,
            'paginator' => $paginator
        ]);

        return app()->render('layouts/admin', [
            'title' => 'Comments',
            'content' => $content
        ]);
    }

    private function updateStatus(Request $request, string $id, string $status): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MODERATE_COMMENTS)) {
            return Redirect::to('/admin/dashboard');
        }

        if (in_array($status, ['approved', 'pending', 'spam', 'trash'], true)) {
            $this->repo->updateStatus((int)$id, $status);
            Flash::set('success', 'Comment status updated to ' . $status . '.');
        }
        
        return Redirect::back('/admin/comments');
    }

    public function approve(Request $request, string $id): Response
    {
        return $this->updateStatus($request, $id, 'approved');
    }

    public function spam(Request $request, string $id): Response
    {
        return $this->updateStatus($request, $id, 'spam');
    }

    public function trash(Request $request, string $id): Response
    {
        return $this->updateStatus($request, $id, 'trash');
    }

    public function destroy(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MODERATE_COMMENTS)) {
            return Redirect::to('/admin/dashboard');
        }

        $this->repo->delete((int)$id);
        Flash::set('success', 'Comment permanently deleted.');
        return Redirect::back('/admin/comments');
    }

    public function edit(Request $request, string $id): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MODERATE_COMMENTS)) {
            Flash::set('error', 'Permission denied.');
            return Redirect::to('/admin/dashboard');
        }

        $comment = $this->repo->find((int)$id);
        if (!$comment) {
            Flash::set('error', 'Comment not found.');
            return Redirect::to('/admin/comments');
        }

        $content = app()->render('admin/comments/edit', ['comment' => $comment]);
        return app()->render('layouts/admin', [
            'title'   => 'Edit Comment',
            'content' => $content,
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MODERATE_COMMENTS)) {
            return Redirect::to('/admin/dashboard');
        }

        $comment = $this->repo->find((int)$id);
        if (!$comment) {
            Flash::set('error', 'Comment not found.');
            return Redirect::to('/admin/comments');
        }

        $content = trim($_POST['content'] ?? '');
        if (empty($content)) {
            Flash::set('error', 'Comment content cannot be empty.');
            return Redirect::back("/admin/comments/{$id}/edit");
        }

        $db = \App\Database\ConnectionFactory::make();
        $stmt = $db->prepare("UPDATE comments SET content = ?, author_name = ?, author_email = ? WHERE id = ?");
        $stmt->execute([
            $content,
            $_POST['author_name'] ?? $comment['author_name'],
            $_POST['author_email'] ?? $comment['author_email'],
            (int)$id,
        ]);

        Flash::set('success', 'Comment updated.');
        return Redirect::to('/admin/comments');
    }

    public function bulk(\Lukman\Http\Request $request): \Lukman\Http\Response
    {
        if (!\App\Auth\CapabilityChecker::checkCurrentUser(\App\Auth\Capability::MODERATE_COMMENTS)) {
            \App\Support\Flash::set('error', 'Permission denied.');
            return \App\Support\Redirect::to('/admin/comments');
        }

        $action = $_POST['action'] ?? '';
        $ids = $_POST['ids'] ?? [];

        if (empty($ids) || !is_array($ids)) {
            \App\Support\Flash::set('error', 'No comments selected.');
            return \App\Support\Redirect::to('/admin/comments');
        }

        foreach ($ids as $id) {
            if ($action === 'approve') {
                $this->repo->updateStatus((int)$id, 'approved');
            } elseif ($action === 'spam') {
                $this->repo->updateStatus((int)$id, 'spam');
            } elseif ($action === 'trash') {
                $this->repo->updateStatus((int)$id, 'trash');
            } elseif ($action === 'delete') {
                $this->repo->delete((int)$id);
            }
        }
        \App\Support\Flash::set('success', 'Bulk action completed.');
        return \App\Support\Redirect::back('/admin/comments');
    }
}