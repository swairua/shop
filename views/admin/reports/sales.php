<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Sales Reports</h4>
    <form method="GET" class="d-flex">
        <select name="year" class="form-select me-2">
            <?php for ($y = date('Y'); $y >= 2024; $y--): ?>
                <option value="<?= $y ?>" <?= $year == $y ? 'selected' : '' ?>><?= $y ?></option>
            <?php endfor; ?>
        </select>
        <button type="submit" class="btn btn-primary">View</button>
    </form>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Total Revenue (Year)</h6>
                <h4><?= format_price(array_sum(array_column($monthlyData, 'revenue'))) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Total Orders (Year)</h6>
                <h4><?= array_sum(array_column($monthlyData, 'orders')) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-info text-white">
            <div class="card-body">
                <h6>Avg Order Value</h6>
                <h4><?= format_price($stats['avg_order'] ?? 0) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6>Orders (30 days)</h6>
                <h4><?= $stats['total_orders'] ?? 0 ?></h4>
            </div>
        </div>
    </div>
</div>

<div class="row g-3">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Monthly Revenue - <?= $year ?></h6></div>
            <div class="card-body">
                <canvas id="monthlyChart" height="250"></canvas>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Order Status Breakdown</h6></div>
            <div class="card-body">
                <canvas id="statusChart" height="200"></canvas>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    new Chart(document.getElementById('monthlyChart'), {
        type: 'bar',
        data: {
            labels: <?= json_encode(array_map(function($d) { return date('F', mktime(0, 0, 0, $d['month'], 1)); }, $monthlyData)) ?>,
            datasets: [{
                label: 'Revenue',
                data: <?= json_encode(array_column($monthlyData, 'revenue')) ?>,
                backgroundColor: '#4e73df'
            }]
        }
    });
    new Chart(document.getElementById('statusChart'), {
        type: 'doughnut',
        data: {
            labels: <?= json_encode(array_column($statusCounts, 'order_status')) ?>,
            datasets: [{
                data: <?= json_encode(array_column($statusCounts, 'count')) ?>,
                backgroundColor: ['#4e73df','#1cc88a','#36b9cc','#f6c23e','#e74a3b']
            }]
        }
    });
});
</script>
