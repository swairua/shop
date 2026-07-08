<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Orders</h4>
</div>

<div class="card mb-3">
    <div class="card-body">
        <div class="btn-group">
            <a href="<?= base_url('admin/orders') ?>" class="btn btn-sm <?= !$currentStatus ? 'btn-primary' : 'btn-outline-secondary' ?>">All</a>
            <a href="<?= base_url('admin/orders?status=pending') ?>" class="btn btn-sm <?= $currentStatus == 'pending' ? 'btn-primary' : 'btn-outline-secondary' ?>">Pending</a>
            <a href="<?= base_url('admin/orders?status=processing') ?>" class="btn btn-sm <?= $currentStatus == 'processing' ? 'btn-primary' : 'btn-outline-secondary' ?>">Processing</a>
            <a href="<?= base_url('admin/orders?status=shipped') ?>" class="btn btn-sm <?= $currentStatus == 'shipped' ? 'btn-primary' : 'btn-outline-secondary' ?>">Shipped</a>
            <a href="<?= base_url('admin/orders?status=delivered') ?>" class="btn btn-sm <?= $currentStatus == 'delivered' ? 'btn-primary' : 'btn-outline-secondary' ?>">Delivered</a>
            <a href="<?= base_url('admin/orders?status=cancelled') ?>" class="btn btn-sm <?= $currentStatus == 'cancelled' ? 'btn-primary' : 'btn-outline-secondary' ?>">Cancelled</a>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Order #</th>
                        <th>Customer</th>
                        <th>Items</th>
                        <th>Total</th>
                        <th>Payment</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($orders as $o): ?>
                    <tr>
                        <td><?= $o['order_number'] ?></td>
                        <td>
                            <?php
                            $cust = (new Customer())->find($o['customer_id']);
                            echo $cust['name'] ?? 'Guest';
                            ?>
                        </td>
                        <td>
                            <?php
                            $items = $this->db->query("SELECT COUNT(*) as cnt FROM order_items WHERE order_id = {$o['id']}")->fetch_assoc();
                            echo $items['cnt'];
                            ?>
                        </td>
                        <td><?= format_price($o['total']) ?></td>
                        <td><?= get_status_badge($o['payment_status']) ?></td>
                        <td><?= get_status_badge($o['order_status']) ?></td>
                        <td><?= format_date_short($o['created_at']) ?></td>
                        <td>
                            <a href="<?= base_url('admin/orderView/' . $o['id']) ?>" class="btn btn-sm btn-info"><i class="bi bi-eye"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($totalPages > 1): ?>
    <div class="card-footer">
        <?= paginate($page, $totalPages, base_url('admin/orders?page={page}&status=' . $currentStatus)) ?>
    </div>
    <?php endif; ?>
</div>
