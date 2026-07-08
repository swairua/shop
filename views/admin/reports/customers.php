<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Customer Reports</h4>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Customer</th>
                        <th>Email</th>
                        <th>Orders</th>
                        <th>Total Spent</th>
                        <th>Joined</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($customers as $i => $c): ?>
                    <tr>
                        <td><?= $i + 1 ?></td>
                        <td><?= $c['name'] ?></td>
                        <td><?= $c['email'] ?></td>
                        <td><span class="badge bg-primary"><?= $c['order_count'] ?? 0 ?></span></td>
                        <td><?= format_price($c['total_spent'] ?? 0) ?></td>
                        <td><?= format_date_short($c['created_at']) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
