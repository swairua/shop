<?php if (!isset($modal) || !$modal): ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><?= isset($brand) ? 'Edit Brand' : 'New Brand' ?></h4>
    <a href="<?= base_url('admin/brands') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="<?= $brand['name'] ?? '' ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= $brand['slug'] ?? '' ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control" rows="3"><?= $brand['description'] ?? '' ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Logo</label>
                    <input type="file" name="logo" class="form-control">
                    <?php if (isset($brand) && $brand['logo']): ?>
                        <img src="<?= base_url('uploads/brands/' . $brand['logo']) ?>" height="40" class="mt-2">
                    <?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Website</label>
                    <input type="url" name="website" class="form-control" value="<?= $brand['website'] ?? '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" <?= (isset($brand) && $brand['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= (isset($brand) && $brand['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="<?= $brand['sort_order'] ?? 0 ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Meta Title</label>
                    <input type="text" name="meta_title" class="form-control" value="<?= $brand['meta_title'] ?? '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Meta Description</label>
                    <textarea name="meta_description" class="form-control" rows="2"><?= $brand['meta_description'] ?? '' ?></textarea>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><?= isset($brand) ? 'Update' : 'Create' ?> Brand</button>
        </form>
    </div>
</div>
