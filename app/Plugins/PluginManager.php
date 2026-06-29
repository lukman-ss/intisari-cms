<?php

declare(strict_types=1);

namespace App\Plugins;

use App\Repositories\OptionRepository;

class PluginManager
{
    private string $pluginsPath;
    private OptionRepository $options;

    public function __construct()
    {
        $this->pluginsPath = app()->basePath('plugins');
        $this->options = new OptionRepository();
    }

    public function all(): array
    {
        $plugins = [];
        
        if (!is_dir($this->pluginsPath)) {
            return $plugins;
        }

        $dirs = glob($this->pluginsPath . '/*', GLOB_ONLYDIR);
        if (!$dirs) {
            return $plugins;
        }

        foreach ($dirs as $dir) {
            $jsonFile = $dir . DIRECTORY_SEPARATOR . 'plugin.json';
            if (is_file($jsonFile)) {
                $content = file_get_contents($jsonFile);
                $data = json_decode($content, true);
                if (is_array($data)) {
                    $plugins[] = Plugin::fromJson($dir, $data);
                }
            }
        }

        return $plugins;
    }

    public function get(string $slug): ?Plugin
    {
        foreach ($this->all() as $plugin) {
            if ($plugin->slug === $slug) {
                return $plugin;
            }
        }
        return null;
    }

    public function getActivePlugins(): array
    {
        $activeStr = $this->options->get('active_plugins', '[]');
        $activeSlugs = json_decode((string)$activeStr, true) ?? [];
        if (!is_array($activeSlugs)) {
            $activeSlugs = [];
        }
        
        $active = [];
        foreach ($activeSlugs as $slug) {
            $plugin = $this->get($slug);
            if ($plugin) {
                $active[] = $plugin;
            }
        }
        
        return $active;
    }

    public function activate(string $slug): bool
    {
        $plugin = $this->get($slug);
        if (!$plugin) {
            return false;
        }
        
        // Validate entry file
        if (!is_file($plugin->getEntryFile())) {
            return false;
        }

        $activeStr = $this->options->get('active_plugins', '[]');
        $activeSlugs = json_decode((string)$activeStr, true) ?? [];
        if (!is_array($activeSlugs)) {
            $activeSlugs = [];
        }
        
        if (!in_array($slug, $activeSlugs)) {
            $activeSlugs[] = $slug;
            return $this->options->set('active_plugins', json_encode($activeSlugs));
        }
        
        return true;
    }

    public function deactivate(string $slug): bool
    {
        $activeStr = $this->options->get('active_plugins', '[]');
        $activeSlugs = json_decode((string)$activeStr, true) ?? [];
        if (!is_array($activeSlugs)) {
            $activeSlugs = [];
        }
        
        if (($key = array_search($slug, $activeSlugs)) !== false) {
            unset($activeSlugs[$key]);
            // Re-index array
            $activeSlugs = array_values($activeSlugs);
            return $this->options->set('active_plugins', json_encode($activeSlugs));
        }
        
        return true;
    }
}
