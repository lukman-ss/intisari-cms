<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Tools</h1>

    <!-- Export Section -->
    <div class="box" style="max-width:700px; margin-top:20px; padding:20px; margin-bottom:20px;">
        <h2 style="margin-top:0; font-size:16px;">Export</h2>
        <p style="color:#555; font-size:13px;">Export your site content as a JSON file. You can use this to back up your content or migrate to another installation.</p>

        <div style="display:flex; flex-wrap:wrap; gap:10px; margin-top:15px;">
            <a href="/admin/tools/export?type=all"
                style="background:#0073aa; color:#fff; padding:9px 18px; text-decoration:none; border-radius:3px; font-size:13px;">
                Export All
            </a>
            <a href="/admin/tools/export?type=posts"
                style="background:#555; color:#fff; padding:9px 18px; text-decoration:none; border-radius:3px; font-size:13px;">
                Posts Only
            </a>
            <a href="/admin/tools/export?type=pages"
                style="background:#555; color:#fff; padding:9px 18px; text-decoration:none; border-radius:3px; font-size:13px;">
                Pages Only
            </a>
            <a href="/admin/tools/export?type=users"
                style="background:#555; color:#fff; padding:9px 18px; text-decoration:none; border-radius:3px; font-size:13px;">
                Users Only
            </a>
            <a href="/admin/tools/export?type=settings"
                style="background:#555; color:#fff; padding:9px 18px; text-decoration:none; border-radius:3px; font-size:13px;">
                Settings Only
            </a>
        </div>
    </div>

    <!-- Import Section -->
    <div class="box" style="max-width:700px; padding:20px; margin-bottom:20px;">
        <h2 style="margin-top:0; font-size:16px;">Import</h2>
        <p style="color:#555; font-size:13px;">Import content from a previously exported Intisari CMS JSON file. Existing content will <strong>not</strong> be overwritten — items will be added as new entries.</p>

        <div style="background:#fff8e1; border-left:4px solid #ffb900; padding:10px 14px; font-size:13px; margin-bottom:15px;">
            ⚠️ Only import JSON files exported from Intisari CMS. Importing invalid files may cause errors.
        </div>

        <form method="POST" action="/admin/tools/import" enctype="multipart/form-data">
            <?= \App\Support\Csrf::field() ?>
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:6px; font-weight:bold;">Choose JSON File</label>
                <input type="file" name="import_file" accept=".json,application/json" required
                    style="font-size:13px;">
            </div>
            <button type="submit" style="background:#46b450; color:#fff; border:none; padding:9px 18px; cursor:pointer; border-radius:3px; font-size:13px;">
                Import
            </button>
        </form>
    </div>

    <!-- API Tokens Quick Link -->
    <div class="box" style="max-width:700px; padding:20px;">
        <h2 style="margin-top:0; font-size:16px;">API Tokens</h2>
        <p style="color:#555; font-size:13px;">Manage API tokens for programmatic access to your site's REST API.</p>
        <a href="/admin/tools/api-tokens"
            style="background:#0073aa; color:#fff; padding:9px 18px; text-decoration:none; border-radius:3px; font-size:13px;">
            Manage API Tokens
        </a>
    </div>
</div>
