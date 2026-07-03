<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\AuthManager;
use App\Auth\Capability;
use App\Auth\CapabilityChecker;
use App\Repositories\MediaRepository;
use App\Services\MediaUploadService;
use App\Support\Flash;
use App\Support\Redirect;
use App\Validation\MediaValidator;
use Lukman\Http\Request;
use Lukman\Http\Response;

class MediaController
{
    private MediaRepository $repo;
    private MediaUploadService $uploadService;
    private MediaValidator $validator;

    public function __construct()
    {
        $this->repo = new MediaRepository();
        $this->uploadService = new MediaUploadService();
        $this->validator = new MediaValidator();
    }

    public function index(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::UPLOAD_FILES)) {
            Flash::set('error', 'You do not have permission to manage media.');
            return Redirect::to('/admin/dashboard');
        }

        $page = (int)($_GET['page'] ?? 1);
        $search = $_GET['search'] ?? '';
        $mime = $_GET['mime'] ?? '';

        $paginator = $this->repo->paginate($page, 20, (string)$search, (string)$mime);

        $content = app()->render('admin/media/index', [
            'media' => $paginator['data'],
            'search' => $search,
            'mime' => $mime,
            'paginator' => $paginator,
        ]);

        return app()->render('layouts/admin', [
            'title' => 'Media Library',
            'content' => $content
        ]);
    }

    public function upload(): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::UPLOAD_FILES)) {
            return Redirect::to('/admin/dashboard');
        }

        $content = app()->render('admin/media/upload');
        return app()->render('layouts/admin', [
            'title' => 'Upload Media',
            'content' => $content
        ]);
    }

    public function store(Request $request): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::UPLOAD_FILES)) {
            return Redirect::to('/admin/dashboard');
        }

        if (empty($_FILES['file'])) {
            Flash::set('error', 'No file uploaded.');
            return Redirect::back('/admin/media/upload');
        }

        $file = $_FILES['file'];
        $errors = $this->validator->validateUpload($file);

        if (!empty($errors)) {
            Flash::set('error', implode(' ', $errors));
            return Redirect::back('/admin/media/upload');
        }

        try {
            $uploadData = $this->uploadService->upload($file);
            $user = AuthManager::guard()->user();
            
            $metadata = [
                'title' => pathinfo($file['name'], PATHINFO_FILENAME),
                'alt' => '',
                'caption' => '',
                'description' => ''
            ];

            $this->repo->create([
                'user_id' => $user['id'] ?? 1,
                'filename' => $uploadData['filename'],
                'mime_type' => $uploadData['mime_type'],
                'size' => $uploadData['size'],
                'metadata' => json_encode($metadata)
            ]);

            Flash::set('success', 'File uploaded successfully.');
        } catch (\Exception $e) {
            Flash::set('error', $e->getMessage());
        }

        return Redirect::to('/admin/media');
    }

    public function edit(Request $request, string $id): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::UPLOAD_FILES)) {
            return Redirect::to('/admin/dashboard');
        }

        $media = $this->repo->find((int)$id);
        if (!$media) {
            Flash::set('error', 'Media not found.');
            return Redirect::to('/admin/media');
        }

        $media['metadata_decoded'] = json_decode($media['metadata'] ?? '{}', true) ?: [];

        $content = app()->render('admin/media/edit', ['media' => $media]);
        return app()->render('layouts/admin', [
            'title' => 'Edit Media',
            'content' => $content
        ]);
    }

    public function update(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::UPLOAD_FILES)) {
            return Redirect::to('/admin/dashboard');
        }

        $media = $this->repo->find((int)$id);
        if (!$media) {
            return Redirect::to('/admin/media');
        }

        $metadata = json_decode($media['metadata'] ?? '{}', true) ?: [];
        $metadata['title'] = $_POST['title'] ?? $metadata['title'] ?? '';
        $metadata['alt'] = $_POST['alt'] ?? $metadata['alt'] ?? '';
        $metadata['caption'] = $_POST['caption'] ?? $metadata['caption'] ?? '';
        $metadata['description'] = $_POST['description'] ?? $metadata['description'] ?? '';

        $this->repo->update((int)$id, ['metadata' => json_encode($metadata)]);
        Flash::set('success', 'Media updated successfully.');
        return Redirect::to("/admin/media/{$id}/edit");
    }

    public function destroy(Request $request, string $id): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::UPLOAD_FILES)) {
            return Redirect::to('/admin/dashboard');
        }

        $media = $this->repo->find((int)$id);
        if ($media) {
            $this->uploadService->deleteFile($media['filename']);
            $this->repo->delete((int)$id);
            Flash::set('success', 'Media deleted.');
        }
        
        return Redirect::to('/admin/media');
    }

    public function bulk(\Lukman\Http\Request $request): \Lukman\Http\Response
    {
        if (!\App\Auth\CapabilityChecker::checkCurrentUser(\App\Auth\Capability::UPLOAD_FILES)) {
            \App\Support\Flash::set('error', 'Permission denied.');
            return \App\Support\Redirect::to('/admin/media');
        }

        $action = $_POST['action'] ?? '';
        $ids = $_POST['ids'] ?? [];

        if (empty($ids) || !is_array($ids)) {
            \App\Support\Flash::set('error', 'No media selected.');
            return \App\Support\Redirect::to('/admin/media');
        }

        if ($action === 'delete') {
            foreach ($ids as $id) {
                $this->repo->delete((int)$id);
            }
            \App\Support\Flash::set('success', 'Bulk action completed.');
        }
        return \App\Support\Redirect::back('/admin/media');
    }
}