<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Product Reports</h4>
</div>

<div class="row g-3">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Top Selling Products</h6></div>
            <div class="card-body p-0">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr><th>#</th><th>Product</th><th>Total Sold</th><th>Revenue</th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($topSelling as $i => $p): ?>
                        <tr>
                            <td><?= $i + 1 ?></td>
                            <td><?= $p['name'] ?></td>
                            <td><?= $p['total_sold'] ?? 0 ?></td>
                            <td><?= format_price(($p['total_sold'] ?? 0) * $p['selling_price']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Low Stock Products</h6></div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php foreach ($lowStock as $p): ?>
                    <li class="list-group-item d-flex justify-content-between">
                        <?= $p['name'] ?>
                        <span class="badge bg-warning"><?= $p['quantity'] ?></span>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Out of Stock</h6></div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php foreach ($outOfStock as $p): ?>
                    <li class="list-group-item list-group-item-danger"><?= $p['name'] ?></li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
