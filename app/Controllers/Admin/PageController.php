<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\AuthManager;
use App\Auth\Capability;
use App\Auth\CapabilityChecker;
use App\Repositories\PostRepository;
use App\Repositories\RevisionRepository;
use App\Support\Flash;
use App\Support\PostStatus;
use App\Support\PostType;
use App\Support\Redirect;
use App\Support\Slug;
use App\Validation\PostValidator;
use Lukman\Http\Request;
use Lukman\Http\Response;

class PageController
{
    private PostRepository $repo;
    private PostValidator $validator;

    public function __construct()
    {
        $this->repo = new PostRepository();
        $this->validator = new PostValidator();
    }

    public function index(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_PAGES)) {
            Flash::set('error', 'You do not have permission to manage pages.');
            return Redirect::to('/admin/dashboard');
        }

        $page = (int)($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';
        $status = $_GET['status'] ?? '';

        $paginator = $this->repo->paginate(PostType::PAGE, $page, 20, (string)$search, (string)$status);

        $content = app()->render('admin/pages/index', [
            'pages' => $paginator['data'],
            'search' => $search,
            'status' => $status,
            'paginator' => $paginator,
        ]);

        return app()->render('layouts/admin', [
            'title' => 'Pages',
            'content' => $content
        ]);
    }

    public function create(): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_PAGES)) {
            Flash::set('error', 'You do not have permission to create pages.');
            return Redirect::to('/admin/dashboard');
        }

        $allPages = $this->repo->paginate(PostType::PAGE, 1, 100, '', PostStatus::PUBLISHED)['data'];

        $content = app()->render('admin/pages/create', ['allPages' => $allPages]);
        return app()->render('layouts/admin', [
            'title' => 'Add New Page',
            'content' => $content
        ]);
    }

    public function store(Request $request): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_PAGES)) {
            return Redirect::to('/admin/dashboard');
        }

        $data = $_POST;
        $data['type'] = PostType::PAGE;
        if (empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = Slug::generate($data['title']);
        }
        
        $data['parent_id'] = (int)($data['parent_id'] ?? 0);
        $data['menu_order'] = (int)($data['menu_order'] ?? 0);

        $user = AuthManager::guard()->user();
        $data['author_id'] = $user['id'] ?? 1;

        if (isset($data['status']) && $data['status'] === PostStatus::PUBLISHED) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        $errors = $this->validator->validate($data);

        if (!empty($errors)) {
            Flash::set('error', implode(' ', $errors));
            return Redirect::back('/admin/pages/create');
        }

        // Process SEO metadata
        $seoData = [
            'seo_title'       => $_POST['seo_title'] ?? '',
            'seo_description' => $_POST['seo_description'] ?? '',
            'seo_keywords'    => $_POST['seo_keywords'] ?? '',
            'seo_noindex'     => isset($_POST['seo_noindex']) ? 1 : 0,
            'seo_nofollow'    => isset($_POST['seo_nofollow']) ? 1 : 0,
            'seo_canonical'   => trim($_POST['seo_canonical'] ?? ''),
            'seo_og_title'    => $_POST['seo_og_title'] ?? '',
            'seo_og_description' => $_POST['seo_og_description'] ?? '',
            'seo_og_image'    => $_POST['seo_og_image'] ?? ''
        ];
        $data['seo_metadata'] = json_encode($seoData);

        // Clean up data for database insertion
        unset($data['csrf_token']);
        foreach (array_keys($seoData) as $k) { unset($data[$k]); }
        
        if (empty($data['featured_image_id'])) {
            $data['featured_image_id'] = null;
        }

        $this->repo->create($data);
        Flash::set('success', 'Page created successfully.');
        return Redirect::to('/admin/pages');
    }

    public function edit(Request $request, string $id): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_PAGES)) {
            return Redirect::to('/admin/dashboard');
        }

        $pageData = $this->repo->find((int)$id);
        if (!$pageData || $pageData->type !== PostType::PAGE) {
            Flash::set('error', 'Page not found.');
            return Redirect::to('/admin/pages');
        }

        $allPages = $this->repo->paginate(PostType::PAGE, 1, 100, '', PostStatus::PUBLISHED)['data'];

        $content = app()->render('admin/pages/edit', [
            'page' => $pageData,
            'allPages' => $allPages,
        ]);
        return app()->render('layouts/admin', [
            'title' => 'Edit Page',
            'content' => $content
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_PAGES)) {
            return Redirect::to('/admin/dashboard');
        }

        $pageData = $this->repo->find((int)$id);
        if (!$pageData || $pageData->type !== PostType::PAGE) {
            return Redirect::to('/admin/pages');
        }

        $data = $_POST;
        $data['type'] = PostType::PAGE;
        if (empty($data['slug']) && !empty($data['title'])) {
            $data['slug'] = Slug::generate($data['title']);
        }

        $data['parent_id'] = (int)($data['parent_id'] ?? 0);
        $data['menu_order'] = (int)($data['menu_order'] ?? 0);

        if (isset($data['status']) && $data['status'] === PostStatus::PUBLISHED && empty($pageData->published_at)) {
            $data['published_at'] = date('Y-m-d H:i:s');
        }

        $errors = $this->validator->validate($data, (int)$id);

        if (!empty($errors)) {
            Flash::set('error', implode(' ', $errors));
            return Redirect::back("/admin/pages/{$id}/edit");
        }

        // Process SEO metadata
        $seoData = [
            'seo_title'       => $_POST['seo_title'] ?? '',
            'seo_description' => $_POST['seo_description'] ?? '',
            'seo_keywords'    => $_POST['seo_keywords'] ?? '',
            'seo_noindex'     => isset($_POST['seo_noindex']) ? 1 : 0,
            'seo_nofollow'    => isset($_POST['seo_nofollow']) ? 1 : 0,
            'seo_canonical'   => trim($_POST['seo_canonical'] ?? ''),
            'seo_og_title'    => $_POST['seo_og_title'] ?? '',
            'seo_og_description' => $_POST['seo_og_description'] ?? '',
            'seo_og_image'    => $_POST['seo_og_image'] ?? ''
        ];
        $data['seo_metadata'] = json_encode($seoData);

        // Clean up data for database update
        unset($data['csrf_token']);
        foreach (array_keys($seoData) as $k) { unset($data[$k]); }
        
        if (empty($data['featured_image_id'])) {
            $data['featured_image_id'] = null;
        }

        $this->repo->update((int)$id, $data);
        Flash::set('success', 'Page updated successfully.');
        return Redirect::to("/admin/pages/{$id}/edit");
    }

    public function trash(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_PAGES)) {
            Flash::set('error', 'Permission denied.');
            return Redirect::to('/admin/pages');
        }

        $this->repo->trash((int)$id);
        Flash::set('success', 'Page moved to trash.');
        return Redirect::to('/admin/pages');
    }

    public function restore(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_PAGES)) {
            return Redirect::to('/admin/pages');
        }

        $this->repo->update((int)$id, ['status' => PostStatus::DRAFT]);
        Flash::set('success', 'Page restored from trash.');
        return Redirect::to('/admin/pages');
    }

    public function destroy(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_PAGES)) {
            return Redirect::to('/admin/pages');
        }

        $this->repo->delete((int)$id);
        Flash::set('success', 'Page deleted permanently.');
        return Redirect::to('/admin/pages?status=trash');
    }

    public function bulk(\Lukman\Http\Request $request): \Lukman\Http\Response
    {
        if (!\App\Auth\CapabilityChecker::checkCurrentUser(\App\Auth\Capability::DELETE_PAGES)) {
            \App\Support\Flash::set('error', 'Permission denied.');
            return \App\Support\Redirect::to('/admin/pages');
        }

        $action = $_POST['action'] ?? '';
        $ids = $_POST['ids'] ?? [];

        if (empty($ids) || !is_array($ids)) {
            \App\Support\Flash::set('error', 'No pages selected.');
            return \App\Support\Redirect::to('/admin/pages');
        }

        foreach ($ids as $id) {
            $post = $this->repo->find((int)$id);
            if ($post && $post->type === \App\Support\PostType::PAGE) {
                if ($action === 'trash') {
                    $this->repo->trash((int)$id);
                } elseif ($action === 'restore') {
                    $this->repo->update((int)$id, ['status' => \App\Support\PostStatus::DRAFT]);
                } elseif ($action === 'delete') {
                    $this->repo->delete((int)$id);
                }
            }
        }
        \App\Support\Flash::set('success', 'Bulk action completed.');
        return \App\Support\Redirect::back('/admin/pages');
    }

    public function autosave(Request $request, string $id): Response
    {
        header('Content-Type: application/json');

        if (!CapabilityChecker::checkCurrentUser(Capability::EDIT_PAGES)) {
            return new Response(json_encode(['success' => false, 'message' => 'Permission denied.']), 403);
        }

        $pageData = $this->repo->find((int)$id);
        if (!$pageData || $pageData->type !== PostType::PAGE) {
            return new Response(json_encode(['success' => false, 'message' => 'Page not found.']), 404);
        }

        $data = [
            'title'   => $_POST['title'] ?? '',
            'content' => $_POST['content'] ?? '',
            'excerpt' => $_POST['excerpt'] ?? '',
        ];

        $revRepo = new RevisionRepository();
        $autosaveId = $revRepo->createAutosave((int)$id, $data);

        return new Response(json_encode([
            'success'     => true,
            'autosave_id' => $autosaveId,
            'message'     => 'Draft saved.',
        ]));
    }
}