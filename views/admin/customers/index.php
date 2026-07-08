<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Customers</h4>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Orders</th>
                        <th>Status</th>
                        <th>Joined</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $c): ?>
                    <tr>
                        <td>
                            <img src="<?= get_gravatar($c['email']) ?>" class="rounded-circle me-2" width="30" height="30">
                            <?= $c['name'] ?>
                        </td>
                        <td><?= $c['email'] ?></td>
                        <td><?= $c['phone'] ?? '-' ?></td>
                        <td>
                            <?php
                            $cnt = $this->db->query("SELECT COUNT(*) as cnt FROM orders WHERE customer_id = {$c['id']}")->fetch_assoc();
                            echo $cnt['cnt'];
                            ?>
                        </td>
                        <td><?= get_status_badge($c['status']) ?></td>
                        <td><?= format_date_short($c['created_at']) ?></td>
                        <td>
                            <a href="<?= base_url('admin/customerView/' . $c['id']) ?>" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($totalPages > 1): ?>
    <div class="card-footer">
        <?= paginate($page, $totalPages, base_url('admin/customers?page={page}')) ?>
    </div>
    <?php endif; ?>
</div>
