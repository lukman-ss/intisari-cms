# Feature Documentation

This document lists the features currently implemented in Intisari CMS.

## Installation

- Web installer at `/install`.
- Requirement checks for PHP version, writable `storage/`, and writable database directory.
- SQLite-first setup using `database/cms.sqlite`.
- First administrator account creation during installation.
- Installation lock file at `storage/installed.lock`.

## Public Site

- Homepage routing through the content router.
- Public post archive at `/posts`.
- Dynamic public content routes:
  - `/{p1}`
  - `/{p1}/{p2}`
  - `/{p1}/{p2}/{p3}`
- Theme-based public views under `themes/default`.
- Public comments submission through `/comments`.
- 404 error view.

## Admin Dashboard

- Admin login at `/admin/login`.
- Admin logout at `/admin/logout`.
- Dashboard at `/admin/dashboard`.
- Session-based authentication.
- Capability-based access checks for protected admin actions.
- Profile page at `/admin/profile` for updating username, email, and password.

## Posts

- List, search, and filter posts by status.
- Create, edit, publish, draft, trash, restore, and permanently delete posts.
- Slug generation from title.
- Autosave endpoint for post editing.
- Revision listing, revision detail view, and revision restore.
- Bulk actions for trash, restore, and permanent delete.

## Pages

- List, search, and filter pages by status.
- Create, edit, publish, draft, trash, restore, and permanently delete pages.
- Page hierarchy support through `parent_id`.
- Page ordering support through `menu_order`.
- Autosave endpoint for page editing.
- Revision listing, revision detail view, and revision restore.
- Bulk actions for trash, restore, and permanent delete.

## Categories and Tags

- Category list, create, edit, update, and delete.
- Tag list, create, edit, update, and delete.
- Term repository for taxonomy storage.

## Media Library

- Media list with search and MIME type filter.
- Upload media files.
- Store media metadata as JSON.
- Edit title, alt text, caption, and description.
- Delete uploaded media files.
- Bulk media delete.
- Image thumbnail service is available for generated image sizes.

## Comments

- Public comment submission.
- Admin comment list with status and search filtering.
- Approve comments.
- Mark comments as spam.
- Move comments to trash.
- Permanently delete comments.
- Edit and update comments.
- Bulk moderation actions.

## Users and Roles

- User list, search, create, edit, update, and delete.
- Password hashing through `PasswordHasher`.
- Protection against deleting the current user.
- Role list and role editing.
- Capability definitions for admin permissions.

## Settings

- General settings:
  - Site title
  - Tagline
  - Timezone
  - Locale
- Reading settings:
  - Homepage mode
  - Homepage page
  - Posts page
  - Posts per page
- Discussion settings:
  - Comment moderation
  - Comment allowance
  - Comment closing rules
  - Notification flags
  - Link limit
  - Comment blacklist
- Media settings:
  - Upload size limit
  - Thumbnail size
  - Medium size
  - Large size
- Permalink settings:
  - Permalink structure

## Menus

- Menu list and create.
- Menu edit and update.
- Add menu items.
- Delete menu items.

## Themes

- Theme discovery from `themes/`.
- Theme metadata through `theme.json`.
- Theme activation from `/admin/appearance/themes`.
- Default theme included.

## Plugins

- Plugin discovery from `plugins/`.
- Plugin metadata through `plugin.json`.
- Plugin activation and deactivation.
- Hook system with actions and filters.
- Example `hello-dolly` plugin included.

## Widgets

- Widget admin screen at `/admin/appearance/widgets`.
- Widget storage/update endpoint.
- Widget manager and widget abstraction.

## Tools

- Tools screen at `/admin/tools`.
- JSON export for:
  - Posts
  - Pages
  - Users
  - Terms
  - Settings
  - All supported data
- JSON import for posts and pages.

## API

- API index at `/api/v1`.
- Posts API:
  - `GET /api/v1/posts`
  - `GET /api/v1/posts/{id}`
  - `POST /api/v1/posts`
  - `PATCH /api/v1/posts/{id}`
  - `DELETE /api/v1/posts/{id}`
- Pages API:
  - `GET /api/v1/pages`
  - `POST /api/v1/pages`
  - `PATCH /api/v1/pages/{id}`
  - `DELETE /api/v1/pages/{id}`
- Media API:
  - `GET /api/v1/media`
- Terms API:
  - `GET /api/v1/categories`
  - `GET /api/v1/tags`
- API token management under `/admin/tools/api-tokens`.
- Bearer token authentication for protected API mutations.

## Security

- Global CSRF protection for non-API POST requests.
- Global security headers middleware.
- API endpoints skip CSRF and use token authentication where required.
- Login rate limiter class is available.
- HTML sanitizer class is available.
- Public `.env`, SQLite database, upload files, sessions, and logs are ignored by Git.

## Console Commands

- `php intisari about`
- `php intisari env`
- `php intisari migrate`
- `php intisari migrate:fresh --force`
- `php intisari db:seed`
- `php intisari serve`
- `php intisari test`

## Health Check

- `GET /health` returns basic application health status.

