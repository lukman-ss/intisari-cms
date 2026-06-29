<?php

declare(strict_types=1);

namespace App\Controllers\Admin;

use App\Auth\Capability;
use App\Auth\CapabilityChecker;
use App\Widgets\WidgetManager;
use App\Support\Flash;
use App\Support\Redirect;
use Lukman\Http\Request;
use Lukman\Http\Response;

class WidgetController
{
    private WidgetManager $manager;

    public function __construct()
    {
        $this->manager = new WidgetManager();
        $this->manager->registerArea('sidebar-1', 'Main Sidebar');
        
        $textWidget = new class('text', 'Text Widget') extends \App\Widgets\Widget {
            public function widget(array $args, array $instance): void {
                echo $args['before_widget'];
                if (!empty($instance['title'])) {
                    echo $args['before_title'] . \App\Support\View::escape($instance['title']) . $args['after_title'];
                }
                echo '<div class="textwidget">' . nl2br(\App\Support\View::escape($instance['text'] ?? '')) . '</div>';
                echo $args['after_widget'];
            }
            public function form(array $instance, string $widgetId = '', string $areaId = ''): void {
                $titleName = $this->getFieldName('title', $widgetId, $areaId);
                $textName = $this->getFieldName('text', $widgetId, $areaId);
                
                $title = \App\Support\View::escape($instance['title'] ?? '');
                $text = \App\Support\View::escape($instance['text'] ?? '');
                
                echo "<p><label>Title:</label> <input type='text' name='{$titleName}' value='{$title}' style='width:100%; padding:4px;'></p>";
                echo "<p><label>Text:</label> <textarea name='{$textName}' style='width:100%; height:80px; padding:4px;'>{$text}</textarea></p>";
            }
        };
        $this->manager->registerWidget($textWidget);
    }

    public function index(Request $request): string|Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            Flash::set('error', 'You do not have permission to manage widgets.');
            return Redirect::to('/admin/dashboard');
        }

        $areas = $this->manager->getAreas();
        $widgets = $this->manager->getWidgets();
        $assignments = $this->manager->getAssignments();
        $instances = $this->manager->getInstances();

        $content = app()->render('admin/widgets/index', [
            'areas' => $areas,
            'widgets' => $widgets,
            'assignments' => $assignments,
            'instances' => $instances
        ]);

        return app()->render('layouts/admin', [
            'title' => 'Widgets',
            'content' => $content
        ]);
    }

    public function store(Request $request): Response
    {
        if (!CapabilityChecker::checkCurrentUser(Capability::MANAGE_OPTIONS)) {
            return Redirect::to('/admin/dashboard');
        }

        $assignments = $this->manager->getAssignments();
        $instances = $this->manager->getInstances();

        if (!empty($_POST['add_widget']) && !empty($_POST['area_id']) && !empty($_POST['widget_base'])) {
            $areaId = $_POST['area_id'];
            $baseId = $_POST['widget_base'];
            
            if (!isset($assignments[$areaId])) {
                $assignments[$areaId] = [];
            }
            
            $currentMax = 0;
            if (isset($instances[$baseId])) {
                if (!empty($instances[$baseId])) {
                    $currentMax = max(array_keys($instances[$baseId]));
                }
            }
            $nextNum = $currentMax + 1;
            
            $widgetId = "{$baseId}-{$nextNum}";
            $assignments[$areaId][] = $widgetId;
            
            if (!isset($instances[$baseId])) {
                $instances[$baseId] = [];
            }
            $instances[$baseId][$nextNum] = [];
            
            $this->manager->saveAssignments($assignments);
            $this->manager->saveInstances($instances);
            
            Flash::set('success', 'Widget added.');
            return Redirect::back('/admin/appearance/widgets');
        }

        if (!empty($_POST['save_widgets'])) {
            $newAssignments = [];
            
            if (!empty($_POST['areas']) && is_array($_POST['areas'])) {
                foreach ($_POST['areas'] as $areaId => $areaData) {
                    $newAssignments[$areaId] = [];
                    
                    if (!empty($areaData['widgets']) && is_array($areaData['widgets'])) {
                        uasort($areaData['widgets'], function($a, $b) {
                            $orderA = (int)($a['order'] ?? 0);
                            $orderB = (int)($b['order'] ?? 0);
                            return $orderA <=> $orderB;
                        });
                        
                        foreach ($areaData['widgets'] as $widgetId => $wData) {
                            if (!empty($wData['remove'])) {
                                continue;
                            }
                            
                            $newAssignments[$areaId][] = $widgetId;
                            
                            if (preg_match('/^(.+)-(\d+)$/', $widgetId, $matches)) {
                                $baseId = $matches[1];
                                $num = $matches[2];
                                
                                $widget = $this->manager->getWidgets()[$baseId] ?? null;
                                if ($widget) {
                                    $oldInstance = $instances[$baseId][$num] ?? [];
                                    $newInstanceFields = $wData;
                                    unset($newInstanceFields['order'], $newInstanceFields['remove']);
                                    
                                    $instances[$baseId][$num] = $widget->update($newInstanceFields, $oldInstance);
                                }
                            }
                        }
                    }
                }
            }
            
            $this->manager->saveAssignments($newAssignments);
            $this->manager->saveInstances($instances);
            
            Flash::set('success', 'Widgets saved.');
        }

        return Redirect::back('/admin/appearance/widgets');
    }
}
