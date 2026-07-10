<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-envelope"></i> Newsletter Subscribers (<?= $total ?? count($subscribers) ?>)</h4>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>Email</th><th>Name</th><th>Status</th><th>Subscribed</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($subscribers as $s): ?>
                    <tr>
                        <td><?= $s['email'] ?></td>
                        <td><?= $s['name'] ?? '-' ?></td>
                        <td><?= get_status_badge($s['status']) ?></td>
                        <td><?= format_date_short($s['created_at']) ?></td>
                        <td>
                            <a href="<?= base_url('admin/subscriberDelete/' . $s['id']) ?>" class="btn btn-sm btn-danger btn-delete" data-url="<?= base_url('admin/subscriberDelete/' . $s['id']) ?>"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($subscribers)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">No subscribers yet</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>