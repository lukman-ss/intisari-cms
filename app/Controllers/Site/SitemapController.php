<?php
declare(strict_types=1);

namespace App\Controllers\Site;

use App\Repositories\PostRepository;

class SitemapController
{
    private PostRepository $postRepo;

    public function __construct()
    {
        $this->postRepo = new PostRepository();
    }

    public function index(): void
    {
        // Get all published posts and pages
        $posts = $this->postRepo->getPublishedPostsByType('post');
        $pages = $this->postRepo->getPublishedPostsByType('page');

        $items = array_merge($posts, $pages);

        $baseUrl = rtrim($_SERVER['APP_URL'] ?? ((isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]"), '/');

        header('Content-Type: application/xml; charset=utf-8');

        echo '<?xml version="1.0" encoding="UTF-8"?>' . "\n";
        echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">' . "\n";

        // Homepage
        echo "  <url>\n";
        echo "    <loc>" . htmlspecialchars($baseUrl . '/') . "</loc>\n";
        echo "    <changefreq>daily</changefreq>\n";
        echo "    <priority>1.0</priority>\n";
        echo "  </url>\n";

        foreach ($items as $item) {
            // Check if noindex is set
            if (!empty($item['seo_metadata'])) {
                $meta = json_decode($item['seo_metadata'], true) ?: [];
                if (!empty($meta['seo_noindex'])) {
                    continue; // Skip items marked as noindex
                }
            }

            $url = $baseUrl . '/' . ltrim($item['slug'], '/');
            $date = date('c', strtotime($item['updated_at'] ?? $item['created_at']));
            
            echo "  <url>\n";
            echo "    <loc>" . htmlspecialchars($url) . "</loc>\n";
            echo "    <lastmod>{$date}</lastmod>\n";
            echo "    <changefreq>weekly</changefreq>\n";
            echo "    <priority>0.8</priority>\n";
            echo "  </url>\n";
        }

        echo '</urlset>';
    }
}
