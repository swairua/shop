<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Shipping Settings</h4>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6 class="mb-0">Shipping Zones</h6>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#zoneModal">Add Zone</button>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Name</th><th>Countries</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($zones as $z): ?>
                        <tr>
                            <td><?= $z['name'] ?></td>
                            <td><?= $z['countries'] ? truncate($z['countries'], 30) : 'All' ?></td>
                            <td><?= get_status_badge($z['status']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6 class="mb-0">Shipping Methods</h6>
                <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#methodModal">Add Method</button>
            </div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>Name</th><th>Slug</th><th>Default</th><th>Status</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($methods as $m): ?>
                        <tr>
                            <td><?= $m['name'] ?></td>
                            <td><?= $m['slug'] ?></td>
                            <td><?= $m['is_default'] ? '<i class="bi bi-check text-success"></i>' : '-' ?></td>
                            <td><?= get_status_badge($m['status']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<div class="modal fade" id="zoneModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= base_url('admin/shipping/zone/create') ?>">
                <?= csrf_field() ?>
                <div class="modal-header"><h5>Add Shipping Zone</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Zone Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Countries (comma separated, leave empty for all)</label>
                        <input type="text" name="countries" class="form-control" placeholder="Kenya, Uganda, Tanzania">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="methodModal">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="POST" action="<?= base_url('admin/shipping/method/create') ?>">
                <?= csrf_field() ?>
                <div class="modal-header"><h5>Add Shipping Method</h5><button class="btn-close" data-bs-dismiss="modal"></button></div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Method Name</label>
                        <input type="text" name="name" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Slug</label>
                        <input type="text" name="slug" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control"></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Sort Order</label>
                        <input type="number" name="sort_order" class="form-control" value="0">
                    </div>
                    <div class="mb-3 form-check">
                        <input type="checkbox" name="is_default" class="form-check-input" value="1">
                        <label class="form-check-label">Set as Default</label>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select name="status" class="form-select">
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Save</button>
                </div>
            </form>
        </div>
    </div>
</div>
