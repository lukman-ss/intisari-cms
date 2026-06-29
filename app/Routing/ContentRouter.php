<?php

declare(strict_types=1);

namespace App\Routing;

use App\Controllers\PageController;
use App\Controllers\PostController;
use App\Controllers\HomeController;
use Lukman\Http\Request;
use Lukman\Http\Response;

class ContentRouter
{
    private PermalinkResolver $resolver;

    public function __construct()
    {
        $this->resolver = new PermalinkResolver();
    }

    public function handle(Request $request): Response|string
    {
        $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        $queryParams = $_GET;

        $base = str_replace('/index.php', '', $_SERVER['SCRIPT_NAME'] ?? '');
        if ($base && str_starts_with($uri, $base)) {
            $uri = substr($uri, strlen($base));
        }
        $uri = trim($uri, '/');

        if ($uri === '' && empty($queryParams['p']) && empty($queryParams['page_id'])) {
            $home = new HomeController();
            return $home->index($request);
        }

        $result = $this->resolver->resolve($uri, $queryParams);

        if ($result) {
            if ($result['type'] === 'page') {
                $controller = new PageController();
                $slug = $this->getSlugById($result['id']);
                return $controller->show($request, $slug);
            }
            if ($result['type'] === 'post') {
                $controller = new PostController();
                $slug = $this->getSlugById($result['id']);
                return $controller->show($request, $slug);
            }
        }

        http_response_code(404);
        return '404 Not Found';
    }

    private function getSlugById(int $id): string
    {
        $db = \App\Database\ConnectionFactory::make();
        $stmt = $db->prepare("SELECT slug FROM posts WHERE id = ?");
        $stmt->execute([$id]);
        return (string)$stmt->fetchColumn();
    }
}
