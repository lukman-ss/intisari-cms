<?php

declare(strict_types=1);

namespace App\Widgets;

use App\Repositories\OptionRepository;

class WidgetManager
{
    private array $widgets = [];
    private array $areas = [];
    private OptionRepository $options;

    public function __construct()
    {
        $this->options = new OptionRepository();
    }

    public function registerWidget(Widget $widget): void
    {
        $this->widgets[$widget->idBase] = $widget;
    }

    public function getWidgets(): array
    {
        return $this->widgets;
    }

    public function registerArea(string $id, string $name, array $args = []): void
    {
        $this->areas[$id] = array_merge([
            'name' => $name,
            'before_widget' => '<div id="%1$s" class="widget %2$s">',
            'after_widget'  => '</div>',
            'before_title'  => '<h2 class="widget-title">',
            'after_title'   => '</h2>',
        ], $args);
    }

    public function getAreas(): array
    {
        return $this->areas;
    }

    public function getAssignments(): array
    {
        $data = $this->options->get('widget_areas');
        if ($data) {
            $decoded = json_decode((string)$data, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        return [];
    }

    public function saveAssignments(array $assignments): bool
    {
        return $this->options->set('widget_areas', json_encode($assignments));
    }
    
    public function getInstances(): array
    {
        $data = $this->options->get('widget_instances');
        if ($data) {
            $decoded = json_decode((string)$data, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }
        return [];
    }

    public function saveInstances(array $instances): bool
    {
        return $this->options->set('widget_instances', json_encode($instances));
    }

    public function renderArea(string $areaId): void
    {
        if (!isset($this->areas[$areaId])) {
            return;
        }

        $area = $this->areas[$areaId];
        $assignments = $this->getAssignments();
        
        if (empty($assignments[$areaId]) || !is_array($assignments[$areaId])) {
            return;
        }

        $instances = $this->getInstances();

        foreach ($assignments[$areaId] as $widgetId) {
            if (preg_match('/^(.+)-(\d+)$/', $widgetId, $matches)) {
                $baseId = $matches[1];
                if (isset($this->widgets[$baseId])) {
                    $widget = $this->widgets[$baseId];
                    $instanceData = $instances[$baseId][$matches[2]] ?? [];
                    
                    $args = $area;
                    $args['widget_id'] = $widgetId;
                    $args['before_widget'] = sprintf($args['before_widget'], htmlspecialchars($widgetId), htmlspecialchars('widget_' . $baseId));
                    
                    $widget->widget($args, $instanceData);
                }
            }
        }
    }
}
