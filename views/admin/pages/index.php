<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-file-text"></i> Pages</h4>
    <a href="<?= base_url('admin/pageCreate') ?>" class="btn btn-primary"><i class="bi bi-plus"></i> New Page</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Title</th><th>Slug</th><th>Status</th><th>Updated</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($pages as $p): ?>
                    <tr>
                        <td><strong><?= $p['title'] ?></strong></td>
                        <td><code><?= $p['slug'] ?></code></td>
                        <td><?= get_status_badge($p['status']) ?></td>
                        <td><?= format_date_short($p['updated_at'] ?? $p['created_at']) ?></td>
                        <td>
                            <a href="<?= base_url('admin/pageEdit/' . $p['id']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <a href="<?= base_url($p['slug']) ?>" class="btn btn-sm btn-info" target="_blank"><i class="bi bi-eye"></i></a>
                            <a href="<?= base_url('admin/pageDelete/' . $p['id']) ?>" class="btn btn-sm btn-danger btn-delete" data-url="<?= base_url('admin/pageDelete/' . $p['id']) ?>"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($pages)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">No pages yet</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>