<?php

declare(strict_types=1);

namespace App\Models;

class Post
{
    public int $id;
    public int $author_id;
    public string $type = 'post';
    public string $title = '';
    public string $slug = '';
    public ?string $excerpt = null;
    public ?string $content = null;
    public string $status = 'draft';
    public int $parent_id = 0;
    public int $menu_order = 0;
    public ?int $featured_image_id = null;
    public ?string $featured_image_url = null;
    public string $comment_status = 'open';
    public ?string $published_at = null;
    public string $created_at;
    public string $updated_at;
    public ?string $deleted_at = null;

    public function __construct(array $attributes = [])
    {
        foreach ($attributes as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }
    }

    public function toArray(): array
    {
        return get_object_vars($this);
    }
}
