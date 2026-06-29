<?php

declare(strict_types=1);

namespace App\Auth;

class Capability
{
    public const MANAGE_OPTIONS = 'manage_options';
    public const MANAGE_USERS = 'manage_users';
    public const EDIT_POSTS = 'edit_posts';
    public const PUBLISH_POSTS = 'publish_posts';
    public const DELETE_POSTS = 'delete_posts';
    public const EDIT_PAGES = 'edit_pages';
    public const PUBLISH_PAGES = 'publish_pages';
    public const UPLOAD_FILES = 'upload_files';
    public const MODERATE_COMMENTS = 'moderate_comments';
    public const MANAGE_THEMES = 'manage_themes';
    public const MANAGE_PLUGINS = 'manage_plugins';
    public const UNFILTERED_HTML = 'unfiltered_html';

    public static function all(): array
    {
        return [
            self::MANAGE_OPTIONS,
            self::MANAGE_USERS,
            self::EDIT_POSTS,
            self::PUBLISH_POSTS,
            self::DELETE_POSTS,
            self::EDIT_PAGES,
            self::PUBLISH_PAGES,
            self::UPLOAD_FILES,
            self::MODERATE_COMMENTS,
            self::MANAGE_THEMES,
            self::MANAGE_PLUGINS,
            self::UNFILTERED_HTML,
        ];
    }
}
