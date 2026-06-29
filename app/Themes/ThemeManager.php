<?php

declare(strict_types=1);

namespace App\Themes;

use App\Repositories\OptionRepository;

class ThemeManager
{
    private string $themesPath;
    private OptionRepository $options;

    public function __construct()
    {
        $this->themesPath = app()->basePath('themes');
        $this->options = new OptionRepository();
    }

    public function all(): array
    {
        $themes = [];
        
        if (!is_dir($this->themesPath)) {
            return $themes;
        }

        $dirs = glob($this->themesPath . '/*', GLOB_ONLYDIR);
        if (!$dirs) {
            return $themes;
        }

        foreach ($dirs as $dir) {
            $jsonFile = $dir . DIRECTORY_SEPARATOR . 'theme.json';
            if (is_file($jsonFile)) {
                $content = file_get_contents($jsonFile);
                $data = json_decode($content, true);
                if (is_array($data)) {
                    $themes[] = Theme::fromJson($dir, $data);
                }
            }
        }

        return $themes;
    }

    public function get(string $slug): ?Theme
    {
        foreach ($this->all() as $theme) {
            if ($theme->slug === $slug) {
                return $theme;
            }
        }
        return null;
    }

    public function getActiveTheme(): Theme
    {
        $activeSlug = $this->options->get('active_theme', 'default');
        $theme = $this->get((string)$activeSlug);
        
        if (!$theme) {
            $theme = $this->get('default');
            if (!$theme) {
                throw new \RuntimeException("Critical error: Default theme is missing.");
            }
        }
        
        return $theme;
    }

    public function activate(string $slug): bool
    {
        $theme = $this->get($slug);
        if ($theme) {
            return $this->options->set('active_theme', $slug);
        }
        return false;
    }
}
