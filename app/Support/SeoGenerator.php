<?php
declare(strict_types=1);

namespace App\Support;

use App\Models\Post;

class SeoGenerator
{
    public static function render(?Post $post, string $defaultTitle = 'Intisari CMS'): string
    {
        $siteTitle = function_exists('get_setting') ? get_setting('site_title', $defaultTitle) : $defaultTitle;
        
        $title = $siteTitle;
        $description = '';
        $keywords = '';
        $robots = ['index', 'follow'];
        $canonical = '';
        $ogTitle = '';
        $ogDescription = '';
        $ogImage = '';
        
        // Base URL helper
        $baseUrl = rtrim($_SERVER['APP_URL'] ?? ((isset($_SERVER['HTTPS']) ? "https" : "http") . "://$_SERVER[HTTP_HOST]"), '/');

        if ($post) {
            $meta = !empty($post->seo_metadata) ? json_decode($post->seo_metadata, true) : [];
            
            // Standard Meta
            $title = !empty($meta['seo_title']) ? $meta['seo_title'] . ' - ' . $siteTitle : $post->title . ' - ' . $siteTitle;
            $description = $meta['seo_description'] ?? ($post->excerpt ?? '');
            $keywords = $meta['seo_keywords'] ?? '';
            
            // Robots
            if (!empty($meta['seo_noindex'])) {
                $robots[0] = 'noindex';
            }
            if (!empty($meta['seo_nofollow'])) {
                $robots[1] = 'nofollow';
            }
            
            // Canonical
            if (!empty($meta['seo_canonical'])) {
                $canonical = $meta['seo_canonical'];
            } else {
                $canonical = $baseUrl . '/' . ltrim($post->slug, '/');
            }
            
            // Open Graph Fallbacks
            $ogTitle = !empty($meta['seo_og_title']) ? $meta['seo_og_title'] : $title;
            $ogDescription = !empty($meta['seo_og_description']) ? $meta['seo_og_description'] : $description;
            
            if (!empty($meta['seo_og_image'])) {
                $ogImage = $meta['seo_og_image'];
            } elseif (!empty($post->featured_image_url)) {
                $ogImage = $baseUrl . '/storage/uploads/' . $post->featured_image_url;
            }
        }

        $html = [];
        $html[] = '<title>' . View::escape($title) . '</title>';
        
        if ($description) {
            $html[] = '<meta name="description" content="' . View::escape($description) . '">';
        }
        
        if ($keywords) {
            $html[] = '<meta name="keywords" content="' . View::escape($keywords) . '">';
        }
        
        $html[] = '<meta name="robots" content="' . implode(', ', $robots) . '">';
        
        if ($canonical) {
            $html[] = '<link rel="canonical" href="' . View::escape($canonical) . '">';
        }
        
        // Open Graph
        $html[] = '<meta property="og:locale" content="en_US">';
        $html[] = '<meta property="og:type" content="' . ($post ? 'article' : 'website') . '">';
        $html[] = '<meta property="og:title" content="' . View::escape($ogTitle ?: $title) . '">';
        
        if ($ogDescription) {
            $html[] = '<meta property="og:description" content="' . View::escape($ogDescription) . '">';
        }
        if ($canonical) {
            $html[] = '<meta property="og:url" content="' . View::escape($canonical) . '">';
        }
        $html[] = '<meta property="og:site_name" content="' . View::escape($siteTitle) . '">';
        
        if ($ogImage) {
            $html[] = '<meta property="og:image" content="' . View::escape($ogImage) . '">';
            $html[] = '<meta name="twitter:card" content="summary_large_image">';
        } else {
            $html[] = '<meta name="twitter:card" content="summary">';
        }
        
        // Schema.org Article JSON-LD
        if ($post) {
            $schema = [
                '@context' => 'https://schema.org',
                '@type' => 'Article',
                'headline' => $title,
                'description' => $description,
                'url' => $canonical,
                'datePublished' => $post->published_at ?? $post->created_at,
                'dateModified' => $post->updated_at,
                'author' => [
                    '@type' => 'Person',
                    'name' => 'Author' // In a full app, fetch author name
                ]
            ];
            
            if ($ogImage) {
                $schema['image'] = [$ogImage];
            }
            
            $html[] = '<script type="application/ld+json">';
            $html[] = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
            $html[] = '</script>';
        }

        return implode("\n    ", $html);
    }

    public static function renderBreadcrumbs(array $crumbs): string
    {
        if (empty($crumbs)) {
            return '';
        }

        $html = ['<nav aria-label="breadcrumb" class="seo-breadcrumbs"><ol itemscope itemtype="https://schema.org/BreadcrumbList">'];
        $schemaItemList = [];

        $position = 1;
        foreach ($crumbs as $index => $crumb) {
            $isLast = ($index === count($crumbs) - 1);
            $name = View::escape($crumb['name']);
            $url = $crumb['url'] ? View::escape($crumb['url']) : '';

            // HTML representation
            $html[] = '<li itemprop="itemListElement" itemscope itemtype="https://schema.org/ListItem">';
            if (!$isLast && $url) {
                $html[] = '<a itemprop="item" href="' . $url . '"><span itemprop="name">' . $name . '</span></a>';
            } else {
                $html[] = '<span itemprop="name" aria-current="page">' . $name . '</span>';
            }
            $html[] = '<meta itemprop="position" content="' . $position . '" />';
            $html[] = '</li>';

            // JSON-LD Array
            $schemaItem = [
                '@type' => 'ListItem',
                'position' => $position,
                'name' => $crumb['name']
            ];
            if (!$isLast && $url) {
                $schemaItem['item'] = $crumb['url'];
            }
            $schemaItemList[] = $schemaItem;

            $position++;
        }

        $html[] = '</ol></nav>';

        // JSON-LD Schema
        $schema = [
            '@context' => 'https://schema.org',
            '@type' => 'BreadcrumbList',
            'itemListElement' => $schemaItemList
        ];

        $html[] = '<script type="application/ld+json">';
        $html[] = json_encode($schema, JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
        $html[] = '</script>';

        return implode("\n", $html);
    }
}
