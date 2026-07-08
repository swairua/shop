<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><?= isset($user) ? 'Edit User' : 'New User' ?></h4>
    <a href="<?= base_url('admin/users') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="<?= $user['name'] ?? '' ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email <span class="text-danger">*</span></label>
                    <input type="email" name="email" class="form-control" value="<?= $user['email'] ?? '' ?>" required>
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Password <?= !isset($user) ? '<span class="text-danger">*</span>' : '' ?></label>
                    <input type="password" name="password" class="form-control" <?= !isset($user) ? 'required' : '' ?>>
                    <?php if (isset($user)): ?><small class="text-muted">Leave blank to keep current</small><?php endif; ?>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= $user['phone'] ?? '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Role</label>
                    <select name="role" class="form-select">
                        <option value="staff" <?= (isset($user) && $user['role'] == 'staff') ? 'selected' : '' ?>>Staff</option>
                        <option value="admin" <?= (isset($user) && $user['role'] == 'admin') ? 'selected' : '' ?>>Admin</option>
                        <option value="super_admin" <?= (isset($user) && $user['role'] == 'super_admin') ? 'selected' : '' ?>>Super Admin</option>
                    </select>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" <?= (isset($user) && $user['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= (isset($user) && $user['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><?= isset($user) ? 'Update' : 'Create' ?> User</button>
        </form>
    </div>
</div>
