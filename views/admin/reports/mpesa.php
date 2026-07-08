<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">M-Pesa Reports</h4>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-3">
        <div class="card bg-success text-white">
            <div class="card-body">
                <h6>Daily Collections (<?= date('Y-m-d') ?>)</h6>
                <h4><?= format_price($daily['total'] ?? 0) ?></h4>
                <small><?= $daily['count'] ?? 0 ?> transactions</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-primary text-white">
            <div class="card-body">
                <h6>Monthly Collections (<?= date('F Y') ?>)</h6>
                <h4><?= format_price($monthly['total'] ?? 0) ?></h4>
                <small><?= $monthly['count'] ?? 0 ?> transactions</small>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-warning text-white">
            <div class="card-body">
                <h6>Pending STK</h6>
                <h4><?= count($pending) ?></h4>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card bg-danger text-white">
            <div class="card-body">
                <h6>Failed Transactions</h6>
                <h4><?= count($failed) ?></h4>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Recent Transactions</h6></div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr><th>Receipt</th><th>Phone</th><th>Amount</th><th>Status</th><th>Date</th></tr>
                        </thead>
                        <tbody>
                            <?php foreach ($transactions as $t): ?>
                            <tr>
                                <td><?= $t['mpesa_receipt_number'] ?? '-' ?></td>
                                <td><?= $t['phone_number'] ?></td>
                                <td><?= format_price($t['amount']) ?></td>
                                <td><?= get_status_badge($t['status']) ?></td>
                                <td><?= format_date($t['created_at']) ?></td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">Failed Transactions</h6></div>
            <div class="card-body p-0">
                <ul class="list-group list-group-flush">
                    <?php foreach ($failed as $f): ?>
                    <li class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span><?= $f['phone_number'] ?></span>
                            <span class="text-danger"><?= format_price($f['amount']) ?></span>
                        </div>
                        <small class="text-muted"><?= $f['result_desc'] ?? 'Unknown error' ?></small>
                    </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </div>
</div>
