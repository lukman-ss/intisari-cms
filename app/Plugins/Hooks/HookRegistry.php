<?php

declare(strict_types=1);

namespace App\Plugins\Hooks;

class HookRegistry
{
    private array $actions = [];
    private array $filters = [];

    // Actions

    public function addAction(string $tag, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        if (!isset($this->actions[$tag])) {
            $this->actions[$tag] = [];
        }
        $this->actions[$tag][$priority][] = new Action($tag, $callback, $priority, $acceptedArgs);
        ksort($this->actions[$tag]);
    }

    public function doAction(string $tag, mixed ...$args): void
    {
        if (!isset($this->actions[$tag])) {
            return;
        }

        foreach ($this->actions[$tag] as $priorityGroup) {
            foreach ($priorityGroup as $action) {
                try {
                    $slicedArgs = array_slice($args, 0, $action->acceptedArgs);
                    call_user_func($action->callback, ...$slicedArgs);
                } catch (\Throwable $e) {
                    error_log("HookRegistry Action Error [{$tag}]: " . $e->getMessage());
                }
            }
        }
    }

    // Filters

    public function addFilter(string $tag, callable $callback, int $priority = 10, int $acceptedArgs = 1): void
    {
        if (!isset($this->filters[$tag])) {
            $this->filters[$tag] = [];
        }
        $this->filters[$tag][$priority][] = new Filter($tag, $callback, $priority, $acceptedArgs);
        ksort($this->filters[$tag]);
    }

    public function applyFilters(string $tag, mixed $value, mixed ...$args): mixed
    {
        if (!isset($this->filters[$tag])) {
            return $value;
        }

        foreach ($this->filters[$tag] as $priorityGroup) {
            foreach ($priorityGroup as $filter) {
                try {
                    $allArgs = array_merge([$value], $args);
                    $slicedArgs = array_slice($allArgs, 0, $filter->acceptedArgs);
                    $value = call_user_func($filter->callback, ...$slicedArgs);
                } catch (\Throwable $e) {
                    error_log("HookRegistry Filter Error [{$tag}]: " . $e->getMessage());
                }
            }
        }

        return $value;
    }
}
