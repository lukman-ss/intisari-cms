<?php

declare(strict_types=1);

namespace App\Routing;

use App\Repositories\OptionRepository;
use App\Database\ConnectionFactory;

class PermalinkResolver
{
    private OptionRepository $options;

    public function __construct()
    {
        $this->options = new OptionRepository();
    }

    public function resolve(string $uri, array $queryParams): ?array
    {
        $uri = trim($uri, '/');
        
        if ($uri === 'admin' || str_starts_with($uri, 'admin/')) {
            return null;
        }
        if ($uri === 'install' || str_starts_with($uri, 'install/')) {
            return null;
        }
        if (str_starts_with($uri, 'assets/') || str_starts_with($uri, 'themes/')) {
            return null;
        }

        $structure = $this->options->get('permalink_structure', 'post_name');
        
        if (isset($queryParams['p'])) {
            return $this->findPostById((int)$queryParams['p'], 'post');
        }
        if (isset($queryParams['page_id'])) {
            return $this->findPostById((int)$queryParams['page_id'], 'page');
        }

        if ($uri === '') {
            return null;
        }

        // Pages are resolved first if they exist with this exact slug
        if (!str_contains($uri, '/')) {
            $page = $this->findPostBySlug($uri, 'page');
            if ($page) {
                return ['type' => 'page', 'id' => $page['id']];
            }
        }

        if ($structure === 'post_name') {
            if (!str_contains($uri, '/')) {
                $post = $this->findPostBySlug($uri, 'post');
                if ($post) {
                    return ['type' => 'post', 'id' => $post['id']];
                }
            }
        } elseif ($structure === 'date_name') {
            if (preg_match('#^\d{4}/\d{2}/([^/]+)$#', $uri, $matches)) {
                $post = $this->findPostBySlug($matches[1], 'post');
                if ($post) {
                    return ['type' => 'post', 'id' => $post['id']];
                }
            }
        } elseif ($structure === 'category_name') {
            if (preg_match('#^[^/]+/([^/]+)$#', $uri, $matches)) {
                $post = $this->findPostBySlug($matches[1], 'post');
                if ($post) {
                    return ['type' => 'post', 'id' => $post['id']];
                }
            }
        }

        return null;
    }

    private function findPostById(int $id, string $type): ?array
    {
        $db = ConnectionFactory::make();
        $stmt = $db->prepare("SELECT id FROM posts WHERE id = ? AND type = ? AND status = 'published'");
        $stmt->execute([$id, $type]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? ['type' => $type, 'id' => (int)$result['id']] : null;
    }

    private function findPostBySlug(string $slug, string $type): ?array
    {
        $db = ConnectionFactory::make();
        $stmt = $db->prepare("SELECT id FROM posts WHERE slug = ? AND type = ? AND status = 'published'");
        $stmt->execute([$slug, $type]);
        $result = $stmt->fetch(\PDO::FETCH_ASSOC);
        return $result ? ['type' => $type, 'id' => (int)$result['id']] : null;
    }
}
