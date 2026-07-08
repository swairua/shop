<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Suppliers</h4>
    <a href="<?= base_url('admin/supplierCreate') ?>" class="btn btn-primary"><i class="bi bi-plus"></i> New Supplier</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Name</th><th>Contact</th><th>Email</th><th>Phone</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($suppliers as $s): ?>
                    <tr>
                        <td><?= $s['name'] ?></td>
                        <td><?= $s['contact_person'] ?? '-' ?></td>
                        <td><?= $s['email'] ?? '-' ?></td>
                        <td><?= $s['phone'] ?? '-' ?></td>
                        <td><?= get_status_badge($s['status']) ?></td>
                        <td>
                            <a href="<?= base_url('admin/supplierEdit/' . $s['id']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <a href="<?= base_url('admin/supplierDelete/' . $s['id']) ?>" class="btn btn-sm btn-danger btn-delete" data-url="<?= base_url('admin/supplierDelete/' . $s['id']) ?>"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($totalPages > 1): ?>
    <div class="card-footer">
        <?= paginate($page, $totalPages, base_url('admin/suppliers?page={page}')) ?>
    </div>
    <?php endif; ?>
</div>
