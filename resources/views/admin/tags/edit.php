<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Edit Tag</h1>
    <form method="POST" action="/admin/tags/<?= $tag['id'] ?>" class="box" style="max-width: 600px; margin-top: 20px;">
        <?= \App\Support\Csrf::field() ?>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Name</label>
            <input type="text" name="name" value="<?= \App\Support\View::escape($tag['name']) ?>" style="width:100%; padding:8px;" required>
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Slug</label>
            <input type="text" name="slug" value="<?= \App\Support\View::escape($tag['slug']) ?>" style="width:100%; padding:8px;">
        </div>
        
        <div style="margin-bottom: 15px;">
            <label style="display:block; margin-bottom:5px;">Description</label>
            <textarea name="description" rows="4" style="width:100%; padding:8px;"><?= \App\Support\View::escape($tag['description'] ?? '') ?></textarea>
        </div>

        <?php
        $seoData = !empty($tag['seo_metadata']) ? json_decode($tag['seo_metadata'], true) : [];
        $seoTitle = $seoData['seo_title'] ?? '';
        $seoDesc = $seoData['seo_description'] ?? '';
        $seoKeywords = $seoData['seo_keywords'] ?? '';
        $seoNoindex = !empty($seoData['seo_noindex']) ? 'checked' : '';
        $seoNofollow = !empty($seoData['seo_nofollow']) ? 'checked' : '';
        $seoCanonical = $seoData['seo_canonical'] ?? '';
        $seoOgTitle = $seoData['seo_og_title'] ?? '';
        $seoOgDesc = $seoData['seo_og_description'] ?? '';
        $seoOgImage = $seoData['seo_og_image'] ?? '';
        ?>
        <!-- SEO Settings Box -->
        <div class="box" style="margin-bottom: 20px; padding: 15px; border: 1px solid #ddd; background: #fafafa;">
            <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px; color: #0073aa;">SEO Settings</h3>
            
            <div style="margin-bottom: 15px; border-bottom: 1px dashed #eee; padding-bottom: 15px;">
                <h4 style="margin: 0 0 10px 0; font-size: 14px;">General</h4>
                <div style="margin-bottom: 12px;">
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">SEO Title</label>
                    <input type="text" name="seo_title" value="<?= \App\Support\View::escape($seoTitle) ?>" style="width:100%; padding:8px; box-sizing:border-box;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">Meta Description</label>
                    <textarea name="seo_description" style="width:100%; padding:8px; height:60px; box-sizing:border-box;"><?= \App\Support\View::escape($seoDesc) ?></textarea>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">Focus Keywords</label>
                    <input type="text" name="seo_keywords" value="<?= \App\Support\View::escape($seoKeywords) ?>" style="width:100%; padding:8px; box-sizing:border-box;">
                </div>
            </div>

            <div style="margin-bottom: 15px; border-bottom: 1px dashed #eee; padding-bottom: 15px;">
                <h4 style="margin: 0 0 10px 0; font-size: 14px;">Advanced</h4>
                <div style="margin-bottom: 8px;">
                    <label style="display:block; font-size: 13px;">
                        <input type="checkbox" name="seo_noindex" value="1" <?= $seoNoindex ?>> <strong>No Index</strong>
                    </label>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display:block; font-size: 13px;">
                        <input type="checkbox" name="seo_nofollow" value="1" <?= $seoNofollow ?>> <strong>No Follow</strong>
                    </label>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">Canonical URL</label>
                    <input type="url" name="seo_canonical" value="<?= \App\Support\View::escape($seoCanonical) ?>" style="width:100%; padding:8px; box-sizing:border-box;">
                </div>
            </div>

            <div>
                <h4 style="margin: 0 0 10px 0; font-size: 14px;">Social</h4>
                <div style="margin-bottom: 12px;">
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">Social Title</label>
                    <input type="text" name="seo_og_title" value="<?= \App\Support\View::escape($seoOgTitle) ?>" style="width:100%; padding:8px; box-sizing:border-box;">
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">Social Description</label>
                    <textarea name="seo_og_description" style="width:100%; padding:8px; height:60px; box-sizing:border-box;"><?= \App\Support\View::escape($seoOgDesc) ?></textarea>
                </div>
                <div style="margin-bottom: 12px;">
                    <label style="display:block; font-weight:bold; margin-bottom:5px;">Social Image URL</label>
                    <input type="text" name="seo_og_image" value="<?= \App\Support\View::escape($seoOgImage) ?>" style="width:100%; padding:8px; box-sizing:border-box;">
                </div>
            </div>
        </div>
        
        <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:10px 20px; cursor:pointer;">Update Tag</button>
    </form>
</div>
