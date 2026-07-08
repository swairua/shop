<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Admin Users</h4>
    <a href="<?= base_url('admin/userCreate') ?>" class="btn btn-primary"><i class="bi bi-plus"></i> New User</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Name</th><th>Email</th><th>Role</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($users as $u): ?>
                    <tr>
                        <td><?= $u['name'] ?></td>
                        <td><?= $u['email'] ?></td>
                        <td><span class="badge bg-<?= $u['role'] == 'super_admin' ? 'danger' : ($u['role'] == 'admin' ? 'warning' : 'info') ?>"><?= ucfirst($u['role']) ?></span></td>
                        <td><?= get_status_badge($u['status']) ?></td>
                        <td>
                            <a href="<?= base_url('admin/userEdit/' . $u['id']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <?php if ($u['id'] != $_SESSION['admin_id']): ?>
                            <a href="<?= base_url('admin/userDelete/' . $u['id']) ?>" class="btn btn-sm btn-danger btn-delete" data-url="<?= base_url('admin/userDelete/' . $u['id']) ?>"><i class="bi bi-trash"></i></a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
