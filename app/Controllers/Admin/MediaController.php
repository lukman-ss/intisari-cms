<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\Auth;
use App\Controllers\BaseController;
use App\Models\Media;
use Lukman\Http\RedirectResponse;
use Lukman\Http\Request;
use Lukman\Http\Response;

final class MediaController extends BaseController
{
    private function model(): Media
    {
        return new Media($this->app->db());
    }

    public function index(Request $request): Response
    {
        $html = $this->app->render('admin.media.index', [
            'authUser' => Auth::user(),
            'media'    => $this->model()->all(),
            'appName'  => $this->appName(),
        ]);

        return new Response($html);
    }

    public function store(Request $request): Response
    {
        $data                = $request->only(['filename', 'path', 'mime_type', 'size']);
        $data['uploader_id'] = Auth::id() ?? 0;

        $this->model()->create($data);

        return new RedirectResponse('/admin/media');
    }

    public function destroy(Request $request, string $id): Response
    {
        $this->model()->delete((int) $id);

        return new RedirectResponse('/admin/media');
    }
}
