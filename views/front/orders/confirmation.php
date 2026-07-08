<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6 text-center">
            <div class="mb-4">
                <i class="bi bi-check-circle-fill text-success fs-1"></i>
            </div>
            <h4>Order Placed Successfully!</h4>
            <p class="text-muted">Thank you for your order.</p>
            <p class="mb-1">Order Number: <strong>#<?= $order['order_number'] ?></strong></p>
            <p>Total: <strong><?= format_price($order['total']) ?></strong></p>

            <div class="card mt-3">
                <div class="card-body">
                    <h6>Order Items</h6>
                    <?php foreach ($order['items'] as $item): ?>
                    <div class="d-flex justify-content-between">
                        <span><?= $item['product_name'] ?> × <?= $item['quantity'] ?></span>
                        <span><?= format_price($item['total_price']) ?></span>
                    </div>
                    <?php endforeach; ?>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <span>Total</span>
                        <strong><?= format_price($order['total']) ?></strong>
                    </div>
                </div>
            </div>

            <div class="mt-3">
                <a href="<?= base_url('account') ?>" class="btn btn-primary">View My Orders</a>
                <a href="<?= base_url('shop') ?>" class="btn btn-outline-primary">Continue Shopping</a>
            </div>
        </div>
    </div>
</div>
