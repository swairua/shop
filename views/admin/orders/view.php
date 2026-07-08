<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Order #<?= $order['order_number'] ?></h4>
    <a href="<?= base_url('admin/orders') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0">Order Items</h6></div>
            <div class="card-body p-0">
                <table class="table mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Product</th>
                            <th>SKU</th>
                            <th>Qty</th>
                            <th>Price</th>
                            <th>Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($order['items'] as $item): ?>
                        <tr>
                            <td><?= $item['product_name'] ?></td>
                            <td><?= $item['product_sku'] ?></td>
                            <td><?= $item['quantity'] ?></td>
                            <td><?= format_price($item['unit_price']) ?></td>
                            <td><?= format_price($item['total_price']) ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="4" class="text-end">Subtotal:</th>
                            <th><?= format_price($order['subtotal']) ?></th>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end">Discount:</td>
                            <td>-<?= format_price($order['discount']) ?></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end">Tax:</td>
                            <td><?= format_price($order['tax']) ?></td>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end">Shipping:</td>
                            <td><?= format_price($order['shipping_cost']) ?></td>
                        </tr>
                        <tr>
                            <th colspan="4" class="text-end">Total:</th>
                            <th><?= format_price($order['total']) ?></th>
                        </tr>
                        <tr>
                            <td colspan="4" class="text-end">Paid:</td>
                            <td><?= format_price($order['paid_amount']) ?></td>
                        </tr>
                        <?php if ($order['outstanding_balance'] > 0): ?>
                        <tr>
                            <td colspan="4" class="text-end text-danger">Outstanding:</td>
                            <td class="text-danger"><?= format_price($order['outstanding_balance']) ?></td>
                        </tr>
                        <?php endif; ?>
                    </tfoot>
                </table>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0">Status History</h6></div>
            <div class="card-body p-0">
                <div class="list-group list-group-flush">
                    <?php foreach ($order['history'] as $h): ?>
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between">
                            <span><?= get_status_badge($h['status']) ?> <?= $h['comment'] ?></span>
                            <small class="text-muted"><?= format_date($h['created_at']) ?> by <?= $h['changed_by'] ?></small>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0">Update Status</h6></div>
            <div class="card-body">
                <form method="POST" action="<?= base_url('admin/orders/update-status/' . $order['id']) ?>">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <select name="status" class="form-select">
                            <option value="pending" <?= $order['order_status'] == 'pending' ? 'selected' : '' ?>>Pending</option>
                            <option value="processing" <?= $order['order_status'] == 'processing' ? 'selected' : '' ?>>Processing</option>
                            <option value="shipped" <?= $order['order_status'] == 'shipped' ? 'selected' : '' ?>>Shipped</option>
                            <option value="delivered" <?= $order['order_status'] == 'delivered' ? 'selected' : '' ?>>Delivered</option>
                            <option value="cancelled" <?= $order['order_status'] == 'cancelled' ? 'selected' : '' ?>>Cancelled</option>
                            <option value="refunded" <?= $order['order_status'] == 'refunded' ? 'selected' : '' ?>>Refunded</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <textarea name="comment" class="form-control" rows="2" placeholder="Comment..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary w-100">Update Status</button>
                </form>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0">Customer Details</h6></div>
            <div class="card-body">
                <?php
                $customer = (new Customer())->find($order['customer_id']);
                if ($customer): ?>
                    <p class="mb-1"><strong><?= $customer['name'] ?></strong></p>
                    <p class="mb-1 text-muted small"><?= $customer['email'] ?></p>
                    <p class="mb-1 text-muted small"><?= $customer['phone'] ?></p>
                <?php else: ?>
                    <p class="text-muted">Guest</p>
                <?php endif; ?>
            </div>
        </div>

        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0">Order Info</h6></div>
            <div class="card-body">
                <p class="mb-1"><strong>Payment:</strong> <?= ucfirst($order['payment_method']) ?></p>
                <p class="mb-1"><strong>Shipping:</strong> <?= ucfirst($order['shipping_method']) ?></p>
                <p class="mb-1"><strong>Created:</strong> <?= format_date($order['created_at']) ?></p>
                <?php if ($order['notes']): ?>
                <p class="mb-1"><strong>Notes:</strong> <?= $order['notes'] ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
