<?php

declare(strict_types=1);

namespace App\Widgets;

abstract class Widget
{
    public function __construct(
        public readonly string $idBase,
        public readonly string $name,
        public readonly array $widgetOptions = [],
        public readonly array $controlOptions = []
    ) {}

    /**
     * Echoes the widget content.
     */
    abstract public function widget(array $args, array $instance): void;

    /**
     * Updates a particular instance of a widget.
     */
    public function update(array $newInstance, array $oldInstance): array
    {
        return $newInstance;
    }

    /**
     * Helper to generate a field name for the admin form.
     */
    public function getFieldName(string $fieldName, string $widgetId, string $areaId): string
    {
        return "areas[{$areaId}][widgets][{$widgetId}][{$fieldName}]";
    }

    /**
     * Outputs the settings update form.
     */
    public function form(array $instance, string $widgetId = '', string $areaId = ''): void
    {
        echo '<p>No options for this widget.</p>';
    }
}
