<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Product Reviews</h4>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Product</th><th>Customer</th><th>Rating</th><th>Review</th><th>Status</th><th>Date</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($reviews as $r): ?>
                    <tr>
                        <td><?= $r['product_name'] ?? 'N/A' ?></td>
                        <td><?= $r['customer_name'] ?? $r['name'] ?? 'Anonymous' ?></td>
                        <td>
                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                <i class="bi bi-star<?= $i <= $r['rating'] ? '-fill text-warning' : '' ?>"></i>
                            <?php endfor; ?>
                        </td>
                        <td><?= truncate($r['review'] ?? '', 50) ?></td>
                        <td><?= get_status_badge($r['status']) ?></td>
                        <td><?= format_date_short($r['created_at']) ?></td>
                        <td>
                            <?php if ($r['status'] == 'pending'): ?>
                                <a href="<?= base_url('admin/reviewApprove/' . $r['id']) ?>" class="btn btn-sm btn-success"><i class="bi bi-check"></i></a>
                                <a href="<?= base_url('admin/reviewDisapprove/' . $r['id']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-x"></i></a>
                            <?php endif; ?>
                            <a href="<?= base_url('admin/reviewDelete/' . $r['id']) ?>" class="btn btn-sm btn-danger btn-delete" data-url="<?= base_url('admin/reviewDelete/' . $r['id']) ?>"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($totalPages > 1): ?>
    <div class="card-footer">
        <?= paginate($page, $totalPages, base_url('admin/reviews?page={page}')) ?>
    </div>
    <?php endif; ?>
</div>
