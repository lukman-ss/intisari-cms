<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\Capability;
use App\Auth\CapabilityChecker;
use App\Repositories\OptionRepository;
use App\Database\ConnectionFactory;
use App\Support\Flash;
use App\Support\Redirect;
use Lukman\Http\Request;
use Lukman\Http\Response;

class SettingController
{
    private OptionRepository $repo;
    
    private array $whitelist = [
        'general' => ['site_title', 'tagline', 'timezone', 'locale'],
        'reading' => ['homepage_mode', 'homepage_page_id', 'posts_page_id', 'posts_per_page'],
        'discussion' => ['comment_moderation', 'allow_comments', 'close_comments_days', 'close_comments_after',
                         'notify_new_comment', 'notify_moderation', 'comment_previously_approved',
                         'comment_max_links', 'comment_blacklist'],
        'media' => ['upload_size_limit', 'thumbnail_size_w', 'thumbnail_size_h', 'medium_size_w', 'medium_size_h', 'large_size_w', 'large_size_h'],
        'permalinks' => ['permalink_structure']
    ];

    public function __construct()
    {
        $this->repo = new OptionRepository();
    }

    private function getPages(): array
    {
        $db = ConnectionFactory::make();
        $stmt = $db->query("SELECT id, title FROM posts WHERE type = 'page' AND status = 'published' ORDER BY title ASC");
        return $stmt->fetchAll(\PDO::FETCH_ASSOC) ?: [];
    }

    public function general(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $options = $this->repo->getMany($this->whitelist['general']);
        $content = app()->render('admin/settings/general', ['options' => $options]);
        return app()->render('layouts/admin', ['title' => 'General Settings', 'content' => $content]);
    }

    public function updateGeneral(Request $request): Response
    {
        return $this->updateGroup('general');
    }

    public function reading(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $options = $this->repo->getMany($this->whitelist['reading']);
        $pages = $this->getPages();
        $content = app()->render('admin/settings/reading', ['options' => $options, 'pages' => $pages]);
        return app()->render('layouts/admin', ['title' => 'Reading Settings', 'content' => $content]);
    }

    public function updateReading(Request $request): Response
    {
        return $this->updateGroup('reading');
    }

    public function discussion(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $options = $this->repo->getMany($this->whitelist['discussion']);
        $content = app()->render('admin/settings/discussion', ['options' => $options]);
        return app()->render('layouts/admin', ['title' => 'Discussion Settings', 'content' => $content]);
    }

    public function updateDiscussion(Request $request): Response
    {
        // Checkboxes that are not submitted if unchecked — default to '0'
        $checkboxKeys = ['comment_moderation', 'allow_comments', 'close_comments_days',
                         'notify_new_comment', 'notify_moderation', 'comment_previously_approved'];
        foreach ($checkboxKeys as $key) {
            if (!isset($_POST[$key])) {
                $_POST[$key] = '0';
            }
        }
        return $this->updateGroup('discussion');
    }

    public function media(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $options = $this->repo->getMany($this->whitelist['media']);
        $content = app()->render('admin/settings/media', ['options' => $options]);
        return app()->render('layouts/admin', ['title' => 'Media Settings', 'content' => $content]);
    }

    public function updateMedia(Request $request): Response
    {
        return $this->updateGroup('media');
    }

    public function permalinks(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $options = $this->repo->getMany($this->whitelist['permalinks']);
        $content = app()->render('admin/settings/permalinks', ['options' => $options]);
        return app()->render('layouts/admin', ['title' => 'Permalink Settings', 'content' => $content]);
    }

    public function updatePermalinks(Request $request): Response
    {
        return $this->updateGroup('permalinks');
    }

    private function updateGroup(string $group): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $keys = $this->whitelist[$group] ?? [];
        foreach ($keys as $key) {
            if (isset($_POST[$key])) {
                $val = is_array($_POST[$key]) ? '' : (string)$_POST[$key];
                $this->repo->set($key, strip_tags($val));
            }
        }

        Flash::set('success', 'Settings updated.');
        return Redirect::back("/admin/settings/{$group}");
    }
}
