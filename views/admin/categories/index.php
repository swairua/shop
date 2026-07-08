<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Categories</h4>
    <a href="<?= base_url('admin/categoryCreate') ?>" class="btn btn-primary" data-modal="1"><i class="bi bi-plus"></i> New Category</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Slug</th>
                        <th>Parent</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php function renderCategoryRow($cat, $depth = 0) { ?>
                        <tr>
                            <td><?= str_repeat('&nbsp;&nbsp;&nbsp;&nbsp;', $depth) ?><?= $depth > 0 ? '└ ' : '' ?><?= $cat['name'] ?></td>
                            <td><?= $cat['slug'] ?></td>
                            <td><?= $cat['parent_id'] ? 'Subcategory' : 'Top Level' ?></td>
                            <td><?= get_status_badge($cat['status']) ?></td>
                            <td>
                                <a href="<?= base_url('admin/categoryEdit/' . $cat['id']) ?>" class="btn btn-sm btn-warning" data-modal="1"><i class="bi bi-pencil"></i></a>
                                <a href="<?= base_url('admin/categoryDelete/' . $cat['id']) ?>" class="btn btn-sm btn-danger btn-delete" data-url="<?= base_url('admin/categoryDelete/' . $cat['id']) ?>"><i class="bi bi-trash"></i></a>
                            </td>
                        </tr>
                        <?php if (!empty($cat['children'])): ?>
                            <?php foreach ($cat['children'] as $child): ?>
                                <?php renderCategoryRow($child, $depth + 1); ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    <?php } ?>
                    <?php foreach ($categories as $cat): ?>
                        <?php renderCategoryRow($cat); ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
