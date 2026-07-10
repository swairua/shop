<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><i class="bi bi-activity"></i> Activity Logs (<?= $total ?>)</h4>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr><th>User</th><th>Action</th><th>Details</th><th>IP</th><th>Time</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($logs as $l): ?>
                    <tr>
                        <td><?= $l['user_name'] ?? 'System' ?></td>
                        <td><span class="badge bg-<?= $l['action'] == 'login' ? 'success' : ($l['action'] == 'logout' ? 'secondary' : 'info') ?>"><?= ucfirst($l['action']) ?></span></td>
                        <td class="text-muted small"><?= $l['details'] ?? '-' ?></td>
                        <td><code><?= $l['ip_address'] ?? '-' ?></code></td>
                        <td><?= format_date($l['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if (empty($logs)): ?>
                    <tr><td colspan="5" class="text-center text-muted py-4">No activity logs</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($totalPages > 1): ?>
    <div class="card-footer">
        <?= paginate($page, $totalPages, base_url('admin/activity-logs?page={page}')) ?>
    </div>
    <?php endif; ?>
</div>