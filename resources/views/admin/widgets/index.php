<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Widgets</h1>
    
    <div style="display:flex; gap: 30px; margin-top: 20px; align-items:flex-start;">
        <div style="flex: 1;">
            <div class="box" style="padding: 20px;">
                <h2>Available Widgets</h2>
                <p>To activate a widget, select the target area and click Add.</p>
                
                <table class="form-table" style="width:100%;">
                    <?php foreach ($widgets as $baseId => $widget): ?>
                        <tr>
                            <td style="padding: 10px 0; border-bottom: 1px solid #eee;">
                                <strong><?= \App\Support\View::escape($widget->name) ?></strong>
                            </td>
                            <td style="padding: 10px 0; border-bottom: 1px solid #eee; text-align:right;">
                                <form method="POST" action="/admin/appearance/widgets" style="margin:0;">
                                    <?= \App\Support\Csrf::field() ?>
                                    <input type="hidden" name="add_widget" value="1">
                                    <input type="hidden" name="widget_base" value="<?= \App\Support\View::escape($baseId) ?>">
                                    <select name="area_id" required style="padding:4px;">
                                        <option value="">&mdash; Select Area &mdash;</option>
                                        <?php foreach ($areas as $aId => $area): ?>
                                            <option value="<?= \App\Support\View::escape($aId) ?>"><?= \App\Support\View::escape($area['name']) ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                    <button type="submit" style="background:#f3f5f6; border:1px solid #0073aa; color:#0073aa; padding:4px 8px; cursor:pointer;">Add</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>
        
        <div style="flex: 1;">
            <form method="POST" action="/admin/appearance/widgets">
                <?= \App\Support\Csrf::field() ?>
                <input type="hidden" name="save_widgets" value="1">
                
                <div style="text-align:right; margin-bottom: 15px;">
                    <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:10px 20px; cursor:pointer; font-weight:bold;">Save Widgets</button>
                </div>
                
                <?php foreach ($areas as $areaId => $area): ?>
                    <div class="box" style="padding: 20px; margin-bottom: 20px; background:#f9f9f9;">
                        <h2 style="margin-top:0;"><?= \App\Support\View::escape($area['name']) ?></h2>
                        
                        <?php 
                        $areaAssignments = $assignments[$areaId] ?? [];
                        if (empty($areaAssignments)): 
                        ?>
                            <p style="color:#666;">No widgets assigned.</p>
                        <?php else: ?>
                            <?php foreach ($areaAssignments as $index => $widgetId): ?>
                                <?php
                                if (preg_match('/^(.+)-(\d+)$/', $widgetId, $matches)) {
                                    $baseId = $matches[1];
                                    $num = $matches[2];
                                    $widget = $widgets[$baseId] ?? null;
                                    
                                    if ($widget) {
                                        $instance = $instances[$baseId][$num] ?? [];
                                        ?>
                                        <div style="background:#fff; border:1px solid #ddd; padding: 15px; margin-bottom: 10px;">
                                            <div style="display:flex; justify-content:space-between; margin-bottom:10px; border-bottom:1px solid #eee; padding-bottom:10px;">
                                                <strong><?= \App\Support\View::escape($widget->name) ?></strong>
                                                <label style="color:#a00; font-size:13px; cursor:pointer;">
                                                    <input type="checkbox" name="areas[<?= $areaId ?>][widgets][<?= $widgetId ?>][remove]" value="1"> Remove
                                                </label>
                                            </div>
                                            
                                            <div style="margin-bottom:15px;">
                                                <label style="display:inline-block; width: 60px; font-size:13px; color:#666;">Order:</label>
                                                <input type="number" name="areas[<?= $areaId ?>][widgets][<?= $widgetId ?>][order]" value="<?= $index ?>" style="width:60px; padding:4px;">
                                            </div>
                                            
                                            <div style="background:#fcfcfc; padding:10px; border:1px solid #eee;">
                                                <?php $widget->form($instance, $widgetId, $areaId); ?>
                                            </div>
                                        </div>
                                        <?php
                                    }
                                }
                                ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </form>
        </div>
    </div>
</div>
