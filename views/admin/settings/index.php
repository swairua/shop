<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-gear"></i> Settings</h4>
</div>

<div class="row">
    <div class="col-md-2">
        <div class="list-group mb-3 shadow-sm">
            <a href="<?= base_url('admin/settings?group=general') ?>" class="list-group-item list-group-item-action <?= $group == 'general' ? 'active' : '' ?>"><i class="bi bi-sliders"></i> General</a>
            <a href="<?= base_url('admin/settings?group=appearance') ?>" class="list-group-item list-group-item-action <?= $group == 'appearance' ? 'active' : '' ?>"><i class="bi bi-palette"></i> Appearance</a>
            <a href="<?= base_url('admin/settings?group=tax') ?>" class="list-group-item list-group-item-action <?= $group == 'tax' ? 'active' : '' ?>"><i class="bi bi-percent"></i> Tax</a>
            <a href="<?= base_url('admin/settings?group=shipping') ?>" class="list-group-item list-group-item-action <?= $group == 'shipping' ? 'active' : '' ?>"><i class="bi bi-truck"></i> Shipping</a>
            <a href="<?= base_url('admin/settings?group=orders') ?>" class="list-group-item list-group-item-action <?= $group == 'orders' ? 'active' : '' ?>"><i class="bi bi-cart-check"></i> Orders</a>
            <a href="<?= base_url('admin/settings?group=catalog') ?>" class="list-group-item list-group-item-action <?= $group == 'catalog' ? 'active' : '' ?>"><i class="bi bi-box"></i> Catalog</a>
            <a href="<?= base_url('admin/settings?group=seo') ?>" class="list-group-item list-group-item-action <?= $group == 'seo' ? 'active' : '' ?>"><i class="bi bi-search"></i> SEO</a>
            <a href="<?= base_url('admin/settings?group=social') ?>" class="list-group-item list-group-item-action <?= $group == 'social' ? 'active' : '' ?>"><i class="bi bi-share"></i> Social</a>
            <a href="<?= base_url('admin/settings?group=hero') ?>" class="list-group-item list-group-item-action <?= $group == 'hero' ? 'active' : '' ?>"><i class="bi bi-image"></i> Hero</a>
            <a href="<?= base_url('admin/pages') ?>" class="list-group-item list-group-item-action <?= is_active('admin/pages') ?>"><i class="bi bi-file-text"></i> Pages</a>
            <a href="<?= base_url('admin/subscribers') ?>" class="list-group-item list-group-item-action <?= is_active('admin/subscribers') ?>"><i class="bi bi-envelope"></i> Subscribers</a>
            <a href="<?= base_url('admin/activity-logs') ?>" class="list-group-item list-group-item-action <?= is_active('admin/activity-logs') ?>"><i class="bi bi-activity"></i> Activity Logs</a>
        </div>
    </div>
    <div class="col-md-10">
        <div class="card shadow-sm">
            <div class="card-header">
                <h6 class="mb-0"><i class="bi bi-pencil-square"></i> <?= ucfirst($group) ?> Settings</h6>
            </div>
            <div class="card-body">
                <form method="POST" enctype="multipart/form-data">
                    <?= csrf_field() ?>
                    <input type="hidden" name="group" value="<?= $group ?>">
                    <?php foreach ($settings as $key => $value): ?>
                        <?php
                        $label = ucwords(str_replace('_', ' ', preg_replace('/^(hero_|shop_)/', '', $key)));
                        $type = 'text';
                        if (strpos($key, 'email') !== false) $type = 'email';
                        if (strpos($key, 'url') !== false || strpos($key, 'facebook') !== false || strpos($key, 'twitter') !== false || strpos($key, 'instagram') !== false || strpos($key, 'tiktok') !== false || strpos($key, 'linkedin') !== false || strpos($key, 'pinterest') !== false) $type = 'url';
                        if (strpos($key, 'description') !== false || strpos($key, 'subheading') !== false) $type = 'textarea';
                        if (strpos($key, 'logo') !== false || strpos($key, 'favicon') !== false || strpos($key, 'image') !== false) $type = 'image';
                        if (strpos($key, 'rate') !== false || strpos($key, 'price') !== false) $type = 'number';
                        if (strpos($key, 'enable') !== false || strpos($key, 'enabled') !== false) $type = 'select';
                        if (strpos($key, 'color') !== false || strpos($key, 'bg_start') !== false || strpos($key, 'bg_end') !== false) $type = 'color';
                        if (strpos($key, 'animation') !== false) $type = 'animation_select';
                        if ($key === 'hero_overlay') $type = 'range';
                        ?>
                        <div class="mb-3">
                            <label class="form-label"><?= $label ?></label>
                            <?php if ($type === 'textarea'): ?>
                                <textarea name="<?= $key ?>" class="form-control" rows="3"><?= $value ?></textarea>
                            <?php elseif ($type === 'image'): ?>
                                <input type="file" name="<?= $key ?>" class="form-control">
                                <?php if ($value): ?>
                                    <img src="<?= base_url('uploads/settings/' . $value) ?>" height="40" class="mt-2 rounded">
                                <?php endif; ?>
                            <?php elseif ($type === 'select'): ?>
                                <select name="<?= $key ?>" class="form-select">
                                    <option value="1" <?= $value == '1' ? 'selected' : '' ?>>Enabled</option>
                                    <option value="0" <?= $value == '0' ? 'selected' : '' ?>>Disabled</option>
                                </select>
                            <?php elseif ($type === 'color'): ?>
                                <div class="d-flex align-items-center gap-2">
                                    <input type="color" name="<?= $key ?>" value="<?= $value ?>" class="form-control form-control-color" style="width:60px;height:40px;padding:3px;cursor:pointer;" oninput="this.nextElementSibling.value=this.value">
                                    <input type="text" value="<?= $value ?>" class="form-control" style="max-width:200px;font-family:monospace;" oninput="this.previousElementSibling.value='#'+this.value.replace('#','')" placeholder="#hex">
                                </div>
                            <?php elseif ($type === 'animation_select'): ?>
                                <select name="<?= $key ?>" class="form-select">
                                    <option value="particles" <?= $value == 'particles' ? 'selected' : '' ?>>Particles</option>
                                    <option value="gradient" <?= $value == 'gradient' ? 'selected' : '' ?>>Animated Gradient</option>
                                    <option value="floating" <?= $value == 'floating' ? 'selected' : '' ?>>Floating Shapes</option>
                                    <option value="none" <?= $value == 'none' ? 'selected' : '' ?>>None</option>
                                </select>
                            <?php elseif ($type === 'range'): ?>
                                <div class="d-flex align-items-center gap-3">
                                    <input type="range" name="<?= $key ?>" min="0" max="1" step="0.1" value="<?= $value ?>" class="form-range" style="max-width:300px" oninput="this.nextElementSibling.textContent=this.value">
                                    <span class="badge bg-secondary fs-6"><?= $value ?></span>
                                </div>
                            <?php else: ?>
                                <input type="<?= $type ?>" name="<?= $key ?>" class="form-control" value="<?= $value ?>">
                            <?php endif; ?>
                        </div>
                    <?php endforeach; ?>
                    <button type="submit" class="btn btn-primary"><i class="bi bi-check-lg"></i> Save Settings</button>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
.list-group-item i { width: 20px; }
.form-control-color { cursor: pointer; }
</style>
<script>
document.querySelectorAll('input[type="color"]').forEach(function(el) {
    el.addEventListener('input', function() {
        var textInput = this.parentElement.querySelector('input[type="text"]');
        if (textInput) textInput.value = this.value;
        var hidden = this.closest('.d-flex').querySelector('input[type="hidden"]');
        if (hidden) hidden.value = this.value;
    });
});
</script>