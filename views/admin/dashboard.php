<div class="d-flex justify-content-between align-items-center mb-4">
    <h4 class="mb-0">Dashboard</h4>
    <span class="text-muted"><?= date('l, F j, Y') ?></span>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card border-primary border-start border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Total Revenue</h6>
                        <h4 class="mb-0"><?= format_price($totalRevenue) ?></h4>
                    </div>
                    <div class="text-primary"><i class="bi bi-currency-dollar fs-1"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-success border-start border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Total Orders</h6>
                        <h4 class="mb-0"><?= $totalOrders ?></h4>
                    </div>
                    <div class="text-success"><i class="bi bi-cart-check fs-1"></i></div>
                </div>
                <small class="text-muted"><?= $salesStats['total_orders'] ?? 0 ?> orders (30 days)</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-info border-start border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Active Products</h6>
                        <h4 class="mb-0"><?= $totalProducts ?></h4>
                    </div>
                    <div class="text-info"><i class="bi bi-box fs-1"></i></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card border-warning border-start border-4">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h6 class="text-muted mb-1">Customers</h6>
                        <h4 class="mb-0"><?= $totalCustomers ?></h4>
                    </div>
                    <div class="text-warning"><i class="bi bi-people fs-1"></i></div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Sales Chart (30 Days)</h6>
            </div>
            <div class="card-body">
                <canvas id="salesChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Order Status</h6>
            </div>
            <div class="card-body">
                <canvas id="orderStatusChart" height="250"></canvas>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header d-flex justify-content-between">
                <h6 class="mb-0">Recent Orders</h6>
                <a href="<?= base_url('admin/orders') ?>" class="btn btn-sm btn-primary">View All</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Order #</th>
                                <th>Customer</th>
                                <th>Total</th>
                                <th>Status</th>
                                <th>Date</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($recentOrders as $o): ?>
                            <tr>
                                <td><a href="<?= base_url('admin/orderView/' . $o['id']) ?>"><?= $o['order_number'] ?></a></td>
                                <td><?= $o['customer_name'] ?? 'Guest' ?></td>
                                <td><?= format_price($o['total']) ?></td>
                                <td><?= get_status_badge($o['order_status']) ?></td>
                                <td><?= format_date_short($o['created_at']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Top Selling Products</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php foreach ($topSelling as $p): ?>
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <?= $p['name'] ?>
                        <span class="badge bg-primary rounded-pill"><?= $p['total_sold'] ?? 0 ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-header">
                <h6 class="mb-0">Low Stock Alerts</h6>
            </div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php if (empty($lowStock)): ?>
                        <li class="list-group-item text-muted">No low stock items</li>
                    <?php else: ?>
                        <?php foreach ($lowStock as $p): ?>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <?= $p['name'] ?>
                            <span class="badge bg-warning text-dark"><?= $p['quantity'] ?> left</span>
                        </li>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const salesCtx = document.getElementById('salesChart').getContext('2d');
    const salesData = <?= json_encode($salesChart) ?>;
    new Chart(salesCtx, {
        type: 'line',
        data: {
            labels: salesData.map(d => d.date),
            datasets: [{
                label: 'Revenue',
                data: salesData.map(d => d.revenue),
                borderColor: '#4e73df',
                backgroundColor: 'rgba(78,115,223,0.1)',
                tension: 0.4
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });

    const statusCtx = document.getElementById('orderStatusChart').getContext('2d');
    const statusData = <?= json_encode($orderStatusCounts) ?>;
    new Chart(statusCtx, {
        type: 'doughnut',
        data: {
            labels: statusData.map(d => d.order_status),
            datasets: [{
                data: statusData.map(d => d.count),
                backgroundColor: ['#4e73df', '#1cc88a', '#36b9cc', '#f6c23e', '#e74a3b', '#858796']
            }]
        },
        options: { responsive: true, maintainAspectRatio: false }
    });
});
</script>
