<?php declare(strict_types=1); ?>
<div class="wrap">
    <h1>Add New Page</h1>
    <form method="POST" action="/admin/pages" id="page-form" style="margin-top: 20px; display: flex; gap: 24px; align-items: flex-start;">
        <?= \App\Support\Csrf::field() ?>

        <!-- Main Column -->
        <div style="flex: 1; min-width: 0;">
            <div style="margin-bottom: 15px;">
                <input type="text" name="title" id="page-title" placeholder="Add title"
                    style="width:100%; padding:10px; font-size: 22px; box-sizing:border-box;" required>
            </div>

            <div style="margin-bottom: 8px; font-size: 13px; color:#555;">
                Slug:&nbsp;
                <span id="slug-preview" style="color:#0073aa;">/</span>
                <input type="text" name="slug" id="page-slug"
                    style="display:inline-block; padding:3px 6px; font-size:13px; width:300px; margin-left:4px;">
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Content</label>
                <textarea name="content" id="page-content"
                    style="width:100%; padding:10px; height:400px; font-family:monospace; box-sizing:border-box;"
                    placeholder="Write your page content here..."></textarea>
            </div>

            <div style="margin-bottom: 15px;">
                <label style="display:block; font-weight:bold; margin-bottom:5px;">Excerpt</label>
                <textarea name="excerpt" style="width:100%; padding:10px; height:100px; box-sizing:border-box;"
                    placeholder="Short description (optional)..."></textarea>
            </div>
        </div>

        <!-- Sidebar Column -->
        <div style="width: 280px; flex-shrink: 0;">

            <!-- Publish Box -->
            <div class="box" style="margin-bottom: 20px; padding: 15px;">
                <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px;">Publish</h3>
                <div style="margin-bottom: 12px;">
                    <label style="display:block; margin-bottom:4px; font-weight:bold;">Status</label>
                    <select name="status" style="width:100%; padding:6px;">
                        <option value="draft">Draft</option>
                        <option value="published">Published</option>
                    </select>
                </div>
                <div id="autosave-status" style="font-size:12px; color:#888; margin-bottom:10px;">Auto-save: disabled (save page first)</div>
                <div style="text-align: right;">
                    <button type="submit" style="background:#0073aa; color:#fff; border:none; padding:9px 18px; cursor:pointer; border-radius:3px;">
                        Save Page
                    </button>
                </div>
            </div>

            <!-- Featured Image Box -->
            <div class="box" style="margin-bottom: 20px; padding: 15px;">
                <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px;">Featured Image</h3>
                <input type="hidden" name="featured_image_id" id="featured-image-id" value="">
                <div id="featured-image-preview"
                    style="margin-bottom:10px; min-height:80px; background:#f5f5f5; border:2px dashed #ddd; display:flex; align-items:center; justify-content:center; cursor:pointer;"
                    onclick="openMediaPicker()">
                    <span style="color:#888; font-size:13px;">Click to set featured image</span>
                </div>
                <div>
                    <button type="button" onclick="openMediaPicker()" style="font-size:12px; background:none; border:none; color:#0073aa; cursor:pointer; padding:0;">
                        Set featured image
                    </button>
                    <button type="button" id="remove-featured-img" onclick="removeFeaturedImage()"
                        style="font-size:12px; background:none; border:none; color:#a00; cursor:pointer; padding:0; margin-left:10px; display:none;">
                        Remove
                    </button>
                </div>
            </div>

            <!-- Page Attributes Box -->
            <div class="box" style="margin-bottom: 20px; padding: 15px;">
                <h3 style="margin-top:0; border-bottom:1px solid #eee; padding-bottom:10px;">Page Attributes</h3>
                <div style="margin-bottom: 12px;">
                    <label style="display:block; margin-bottom:4px; font-weight:bold;">Parent Page</label>
                    <select name="parent_id" style="width:100%; padding:6px;">
                        <option value="0">(no parent)</option>
                        <?php foreach ($allPages as $p): ?>
                            <option value="<?= $p->id ?>"><?= \App\Support\View::escape($p->title) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label style="display:block; margin-bottom:4px; font-weight:bold;">Order</label>
                    <input type="number" name="menu_order" value="0" style="width:100%; padding:6px; box-sizing:border-box;">
                </div>
            </div>

        </div>
    </form>
</div>

<!-- Media Picker Modal -->
<div id="media-picker-modal" style="display:none; position:fixed; top:0; left:0; width:100%; height:100%; background:rgba(0,0,0,.6); z-index:9999; overflow-y:auto;">
    <div style="background:#fff; width:90%; max-width:900px; margin:40px auto; border-radius:4px; padding:20px;">
        <div style="display:flex; justify-content:space-between; align-items:center; border-bottom:1px solid #ddd; padding-bottom:12px; margin-bottom:16px;">
            <h2 style="margin:0; font-size:18px;">Select Media</h2>
            <button onclick="closeMediaPicker()" style="background:none; border:none; font-size:20px; cursor:pointer; color:#666;">&times;</button>
        </div>
        <div id="media-picker-grid" style="display:grid; grid-template-columns:repeat(auto-fill,minmax(120px,1fr)); gap:12px;">
            <p style="color:#888;">Loading media...</p>
        </div>
    </div>
</div>

<script>
document.getElementById('page-title').addEventListener('input', function() {
    const slug = document.getElementById('page-slug');
    if (!slug._userModified) {
        const val = this.value.toLowerCase().replace(/[^a-z0-9\s-]/g,'').trim().replace(/\s+/g,'-');
        slug.value = val;
        document.getElementById('slug-preview').textContent = '/' + val;
    }
});
document.getElementById('page-slug').addEventListener('input', function() {
    this._userModified = true;
    document.getElementById('slug-preview').textContent = '/' + this.value;
});
function openMediaPicker() {
    document.getElementById('media-picker-modal').style.display = 'block';
    loadMediaGrid();
}
function closeMediaPicker() { document.getElementById('media-picker-modal').style.display = 'none'; }
function loadMediaGrid() {
    fetch('/api/v1/media').then(r => r.json()).then(data => {
        const grid = document.getElementById('media-picker-grid');
        const items = data.data || data;
        if (!items.length) { grid.innerHTML = '<p style="color:#888;">No media found.</p>'; return; }
        grid.innerHTML = items.map(m => {
            const meta = typeof m.metadata === 'string' ? JSON.parse(m.metadata || '{}') : (m.metadata || {});
            const url = meta.url || '/storage/uploads/' + m.filename;
            const isImg = m.mime_type && m.mime_type.startsWith('image/');
            return `<div onclick="selectMedia(${m.id},'${url}')" style="cursor:pointer; border:2px solid #ddd; border-radius:4px; overflow:hidden; aspect-ratio:1; display:flex; align-items:center; justify-content:center; background:#f5f5f5; padding:4px;">
                ${isImg ? `<img src="${url}" style="width:100%; height:100%; object-fit:cover;">` : `<span style="font-size:11px; text-align:center; word-break:break-all;">${m.filename}</span>`}
            </div>`;
        }).join('');
    }).catch(() => { document.getElementById('media-picker-grid').innerHTML = '<p style="color:#a00;">Failed to load media.</p>'; });
}
function selectMedia(id, url) {
    document.getElementById('featured-image-id').value = id;
    document.getElementById('featured-image-preview').innerHTML = `<img src="${url}" style="width:100%; height:auto; display:block;">`;
    document.getElementById('remove-featured-img').style.display = 'inline';
    closeMediaPicker();
}
function removeFeaturedImage() {
    document.getElementById('featured-image-id').value = '';
    document.getElementById('featured-image-preview').innerHTML = '<span style="color:#888; font-size:13px;">Click to set featured image</span>';
    document.getElementById('remove-featured-img').style.display = 'none';
}
</script>
