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
            Flash::set('error', 'You do not have permission to moderate comments.');
            return Redirect::to('/admin/dashboard');
        }

        $page = (int)($_GET['page'] ?? 1);
        $status = $_GET['status'] ?? '';

        $paginator = $this->repo->paginateAdmin($page, 20, $status);

        $content = app()->render('admin/comments/index', [
            'comments' => $paginator['data'],
            'status' => $status,
            'paginator' => $paginator,
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
}
