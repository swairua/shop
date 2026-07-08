<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Coupons</h4>
    <a href="<?= base_url('admin/couponCreate') ?>" class="btn btn-primary"><i class="bi bi-plus"></i> New Coupon</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Code</th><th>Type</th><th>Value</th><th>Used</th><th>Expires</th><th>Status</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($coupons as $c): ?>
                    <tr>
                        <td><strong><?= $c['code'] ?></strong></td>
                        <td><?= $c['type'] ?></td>
                        <td><?= $c['type'] == 'percentage' ? $c['value'] . '%' : format_price($c['value']) ?></td>
                        <td><?= $c['used_count'] ?>/<?= $c['usage_limit'] ?? '∞' ?></td>
                        <td><?= $c['expires_at'] ? format_date_short($c['expires_at']) : 'Never' ?></td>
                        <td><?= get_status_badge($c['status']) ?></td>
                        <td>
                            <a href="<?= base_url('admin/couponEdit/' . $c['id']) ?>" class="btn btn-sm btn-warning" data-modal="1"><i class="bi bi-pencil"></i></a>
                            <a href="<?= base_url('admin/couponDelete/' . $c['id']) ?>" class="btn btn-sm btn-danger btn-delete" data-url="<?= base_url('admin/couponDelete/' . $c['id']) ?>"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
