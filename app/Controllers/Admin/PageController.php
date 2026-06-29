<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\Auth;
use App\Controllers\BaseController;
use App\Models\Page;
use Lukman\Http\RedirectResponse;
use Lukman\Http\Request;
use Lukman\Http\Response;

final class PageController extends BaseController
{
    private function model(): Page
    {
        return new Page($this->app->db());
    }

    private function flashError(): ?string
    {
        $session = $this->app->session();
        return $session->started() ? $session->get('_flash_error') : null;
    }

    public function index(Request $request): Response
    {
        $html = $this->app->render('admin.pages.index', [
            'authUser' => Auth::user(),
            'pages'    => $this->model()->all(),
            'appName'  => $this->appName(),
        ]);

        return new Response($html);
    }

    public function create(Request $request): Response
    {
        $html = $this->app->render('admin.pages.create', [
            'authUser' => Auth::user(),
            'error'    => $this->flashError(),
            'appName'  => $this->appName(),
        ]);

        return new Response($html);
    }

    public function store(Request $request): Response
    {
        $data    = $request->only(['title', 'slug', 'content', 'status']);
        $session = $this->app->session();

        try {
            $this->app->validate($data, [
                'title' => 'required',
                'slug'  => 'required',
            ]);
        } catch (\Lukman\Validation\Exception\ValidationException $e) {
            if ($session->started()) {
                $session->flash('_flash_error', implode(', ', $e->errors()->all()));
            }
            return new RedirectResponse('/admin/pages/create');
        }

        $data['author_id'] = Auth::id() ?? 0;
        $data['status']    = $data['status'] !== '' ? $data['status'] : 'draft';

        $this->model()->create($data);

        return new RedirectResponse('/admin/pages');
    }

    public function edit(Request $request, string $id): Response
    {
        $page = $this->model()->find((int) $id);

        if ($page === null) {
            return new Response('Page not found.', 404);
        }

        $html = $this->app->render('admin.pages.edit', [
            'authUser' => Auth::user(),
            'page'     => $page,
            'error'    => $this->flashError(),
            'appName'  => $this->appName(),
        ]);

        return new Response($html);
    }

    public function update(Request $request, string $id): Response
    {
        $data = array_filter(
            $request->only(['title', 'slug', 'content', 'status']),
            fn ($v) => $v !== null && $v !== ''
        );

        $this->model()->update((int) $id, $data);

        return new RedirectResponse('/admin/pages');
    }

    public function destroy(Request $request, string $id): Response
    {
        $this->model()->delete((int) $id);

        return new RedirectResponse('/admin/pages');
    }
}
