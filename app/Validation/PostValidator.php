<?php

declare(strict_types=1);

namespace App\Validation;

use App\Repositories\PostRepository;
use App\Support\PostStatus;
use App\Support\PostType;

class PostValidator
{
    private PostRepository $repo;

    public function __construct()
    {
        $this->repo = new PostRepository();
    }

    public function validate(array $data, ?int $exceptId = null): array
    {
        $errors = [];

        if (empty($data['title'])) {
            $errors[] = 'Title is required.';
        }

        if (empty($data['slug'])) {
            $errors[] = 'Slug is required.';
        } else {
            $type = $data['type'] ?? PostType::POST;
            $existing = $this->repo->findBySlug($data['slug'], $type);
            if ($existing && $existing->id !== $exceptId) {
                $errors[] = 'Slug must be unique for this content type.';
            }
        }

        if (isset($data['status']) && !in_array($data['status'], PostStatus::all(), true)) {
            $errors[] = 'Invalid status.';
        }

        if (isset($data['type']) && !in_array($data['type'], PostType::all(), true)) {
            $errors[] = 'Invalid post type.';
        }

        return $errors;
    }
}
