# Intisari CMS

Intisari CMS is a lightweight PHP CMS application built on IntisariPHP. It includes a public site, admin dashboard, posts, pages, media, comments, users, roles, settings, themes, plugins, widgets, API tokens, and a SQLite-first installer.

## Requirements

- PHP 8.2 or newer
- Composer
- PDO SQLite extension
- Writable `storage/` and `database/` directories

## Installation

```bash
composer create-project lukman-ss/intisari-cms my-site
cd my-site
cp .env.example .env
php -S 127.0.0.1:8000 -t public
```

Open `http://127.0.0.1:8000/install` and follow the installer. The installer creates the database tables and first administrator account.

## Development

```bash
composer install
php intisari migrate
php intisari db:seed
php -S 127.0.0.1:8000 -t public
```

Useful commands:

- `php intisari migrate`
- `php intisari db:seed`
- `php intisari migrate:fresh --force`
- `composer source:check`
- `composer test`

The development seeder creates `admin@example.com / password`. Do not use seeded credentials in production.

## Security

- Keep `APP_DEBUG=false` in production.
- Do not commit `.env`, `database/cms.sqlite`, or uploaded files.
- Point the web server document root to `public/`.
- Remove write access from files that do not need runtime writes.

## License

MIT
