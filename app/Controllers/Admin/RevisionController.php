<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\Capability;
use App\Auth\CapabilityChecker;
use App\Repositories\PostRepository;
use App\Repositories\RevisionRepository;
use App\Support\Flash;
use App\Support\Redirect;
use Lukman\Http\Request;
use Lukman\Http\Response;

class RevisionController
{
    private RevisionRepository $repo;
    private PostRepository $postRepo;

    public function __construct()
    {
        $this->repo = new RevisionRepository();
        $this->postRepo = new PostRepository();
    }

    public function index(Request $request, string $id): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_POSTS)) {
            Flash::set('error', 'Permission denied.');
            return Redirect::to('/admin/dashboard');
        }

        $post = $this->postRepo->find((int)$id);
        if (!$post) {
            Flash::set('error', 'Content not found.');
            return Redirect::to('/admin/dashboard');
        }

        $revisions = $this->repo->getRevisions((int)$id);

        $content = app()->render('admin/revisions/index', [
            'post' => $post,
            'revisions' => $revisions
        ]);

        return app()->render('layouts/admin', [
            'title' => 'Revisions',
            'content' => $content
        ]);
    }

    public function show(Request $request, string $id): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_POSTS)) {
            Flash::set('error', 'Permission denied.');
            return Redirect::to('/admin/dashboard');
        }

        $revision = $this->repo->findRevision((int)$id);
        if (!$revision) {
            Flash::set('error', 'Revision not found.');
            return Redirect::to('/admin/dashboard');
        }
        
        $post = $this->postRepo->find((int)$revision->parent_id);

        $content = app()->render('admin/revisions/show', [
            'post' => $post,
            'revision' => $revision
        ]);

        return app()->render('layouts/admin', [
            'title' => 'View Revision',
            'content' => $content
        ]);
    }

    public function restore(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_POSTS)) {
            Flash::set('error', 'Permission denied.');
            return Redirect::to('/admin/dashboard');
        }

        $parentId = $this->repo->restoreRevision((int)$id);
        if ($parentId) {
            Flash::set('success', 'Revision restored successfully.');
            $post = $this->postRepo->find($parentId);
            $type = $post && $post->type === 'page' ? 'pages' : 'posts';
            return Redirect::to("/admin/{$type}/{$parentId}/edit");
        }

        Flash::set('error', 'Failed to restore revision.');
        return Redirect::back('/admin/dashboard');
    }
}
