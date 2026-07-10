<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><?= isset($page) ? 'Edit Page' : 'New Page' ?></h4>
    <a href="<?= base_url('admin/pages') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Title <span class="text-danger">*</span></label>
                    <input type="text" name="title" class="form-control" value="<?= $page['title'] ?? '' ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= $page['slug'] ?? '' ?>">
                </div>
                <div class="col-md-2 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="draft" <?= (isset($page) && $page['status'] == 'draft') ? 'selected' : '' ?>>Draft</option>
                        <option value="published" <?= (isset($page) && $page['status'] == 'published') ? 'selected' : '' ?>>Published</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Content</label>
                <textarea name="content" class="form-control" rows="12" style="font-family:monospace;"><?= $page['content'] ?? '' ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="<?= $page['meta_title'] ?? '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="2"><?= $page['meta_description'] ?? '' ?></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><?= isset($page) ? 'Update' : 'Create' ?> Page</button>
        </form>
    </div>
</div>