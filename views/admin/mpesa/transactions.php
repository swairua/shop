<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">M-Pesa Transactions</h4>
    <a href="<?= base_url('admin/mpesa') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Settings</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Receipt #</th>
                        <th>Phone</th>
                        <th>Amount</th>
                        <th>Type</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($transactions as $t): ?>
                    <tr>
                        <td><?= $t['mpesa_receipt_number'] ?? 'N/A' ?></td>
                        <td><?= $t['phone_number'] ?></td>
                        <td><?= format_price($t['amount']) ?></td>
                        <td><?= $t['transaction_type'] ?></td>
                        <td><?= get_status_badge($t['status']) ?></td>
                        <td><?= format_date($t['created_at']) ?></td>
                        <td>
                            <a href="<?= base_url('admin/mpesa/receipt/' . $t['id']) ?>" class="btn btn-sm btn-info" target="_blank"><i class="bi bi-receipt"></i> Receipt</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($totalPages > 1): ?>
    <div class="card-footer">
        <?= paginate($page, $totalPages, base_url('admin/mpesa/transactions?page={page}')) ?>
    </div>
    <?php endif; ?>
</div>
