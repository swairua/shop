<div class="container py-4">
    <h3 class="fw-bold mb-4"><i class="bi bi-cart"></i> Shopping Cart</h3>

    <?php if (empty($cart['items'])): ?>
    <div class="empty-state">
        <i class="bi bi-cart-x"></i>
        <h5>Your cart is empty</h5>
        <p>Looks like you haven't added anything yet.</p>
        <a href="<?= base_url('shop') ?>" class="btn btn-primary"><i class="bi bi-bag-check"></i> Continue Shopping</a>
    </div>
    <?php else: ?>
    <div class="row">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-body p-0">
                    <table class="cart-table d-none d-md-table table mb-0">
                        <thead>
                            <tr>
                                <th>Product</th>
                                <th>Price</th>
                                <th>Quantity</th>
                                <th>Total</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($cart['items'] as $item): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center gap-3">
                                        <img src="<?= product_image($item['featured_image'] ?? '', '64x64', $item['name']) ?>" class="cart-item-thumb" alt="">
                                        <div>
                                            <div class="cart-item-title"><a href="<?= base_url('product/' . $item['slug']) ?>"><?= $item['name'] ?></a></div>
                                        </div>
                                    </div>
                                </td>
                                <td><?= format_price($item['unit_price']) ?></td>
                                <td>
                                    <div class="qty-stepper" style="border-radius:6px;">
                                        <button class="update-cart" data-action="minus" data-item-id="<?= $item['id'] ?>"><i class="bi bi-dash"></i></button>
                                        <input type="number" class="cart-qty" value="<?= $item['quantity'] ?>" min="1" data-item-id="<?= $item['id'] ?>">
                                        <button class="update-cart" data-action="plus" data-item-id="<?= $item['id'] ?>"><i class="bi bi-plus"></i></button>
                                    </div>
                                </td>
                                <td><strong><?= format_price($item['total_price']) ?></strong></td>
                                <td>
                                    <button class="btn btn-sm btn-outline-danger remove-item" data-item-id="<?= $item['id'] ?>"><i class="bi bi-trash"></i></button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>

                    <div class="d-md-none">
                        <?php foreach ($cart['items'] as $item): ?>
                        <div class="cart-item d-flex align-items-center p-3 border-bottom">
                            <img src="<?= product_image($item['featured_image'] ?? '', '80x80', $item['name']) ?>" width="72" height="72" style="object-fit:cover;border-radius:var(--radius);" class="me-3">
                            <div class="flex-grow-1">
                                <div class="cart-item-title"><a href="<?= base_url('product/' . $item['slug']) ?>"><?= $item['name'] ?></a></div>
                                <div class="text-muted small mb-2"><?= format_price($item['unit_price']) ?></div>
                                <div class="d-flex align-items-center gap-2">
                                    <div class="qty-stepper" style="border-radius:6px;">
                                        <button class="update-cart" data-action="minus" data-item-id="<?= $item['id'] ?>"><i class="bi bi-dash"></i></button>
                                        <input type="number" class="cart-qty" value="<?= $item['quantity'] ?>" min="1" data-item-id="<?= $item['id'] ?>" style="width:36px;">
                                        <button class="update-cart" data-action="plus" data-item-id="<?= $item['id'] ?>"><i class="bi bi-plus"></i></button>
                                    </div>
                                    <strong class="ms-auto"><?= format_price($item['total_price']) ?></strong>
                                    <button class="btn btn-sm btn-outline-danger remove-item" data-item-id="<?= $item['id'] ?>"><i class="bi bi-trash"></i></button>
                                </div>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <div class="mt-2">
                <a href="<?= base_url('shop') ?>" class="btn btn-outline-primary"><i class="bi bi-arrow-left"></i> Continue Shopping</a>
            </div>
        </div>

        <div class="col-lg-4 mt-3 mt-lg-0">
            <div class="cart-summary-card card">
                <div class="card-header">Order Summary</div>
                <div class="list-group list-group-flush">
                    <div class="list-group-item">
                        <span>Subtotal</span>
                        <strong><?= format_price($cart['subtotal'] ?? 0) ?></strong>
                    </div>
                    <?php if (($cart['discount'] ?? 0) > 0): ?>
                    <div class="list-group-item text-success">
                        <span>Discount</span>
                        <strong>-<?= format_price($cart['discount']) ?></strong>
                    </div>
                    <?php endif; ?>
                    <div class="list-group-item">
                        <span>Tax</span>
                        <strong><?= format_price($cart['tax'] ?? 0) ?></strong>
                    </div>
                    <div class="list-group-item">
                        <span>Shipping</span>
                        <strong><?= format_price($cart['shipping_cost'] ?? 0) ?></strong>
                    </div>
                    <div class="list-group-item total">
                        <span>Total</span>
                        <strong><?= format_price($cart['total'] ?? 0) ?></strong>
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!$cart['coupon_id']): ?>
                    <div class="mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control" id="couponCode" placeholder="Coupon code">
                            <button class="btn btn-outline-primary" id="applyCoupon">Apply</button>
                        </div>
                        <div id="couponMessage" class="mt-1 small"></div>
                    </div>
                    <?php else: ?>
                    <div class="alert alert-success py-2 small d-flex justify-content-between align-items-center">
                        <span>Coupon <strong><?= $cart['coupon_code'] ?></strong> applied</span>
                        <button class="btn-close" id="removeCoupon"></button>
                    </div>
                    <?php endif; ?>

                    <a href="<?= base_url('cart/checkout') ?>" class="btn btn-primary w-100 btn-lg">
                        <i class="bi bi-credit-card"></i> Proceed to Checkout
                    </a>

                    <?php $waNum = App::getSetting('whatsapp_number', ''); ?>
                    <?php if ($waNum): ?>
                    <?php
                        $waMsg = 'Hi! I want to order:' . PHP_EOL;
                        foreach ($cart['items'] as $item) {
                            $waMsg .= '• ' . $item['name'] . ' x' . $item['quantity'] . ' - ' . format_price($item['total_price']) . PHP_EOL;
                        }
                        $waMsg .= PHP_EOL . 'Total: ' . format_price($cart['total'] ?? 0);
                    ?>
                    <a href="<?= wa_link($waMsg) ?>" target="_blank" class="btn btn-whatsapp w-100 mt-2">
                        <i class="bi bi-whatsapp"></i> Order via WhatsApp
                    </a>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<div class="modal fade" id="removeItemModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered modal-sm">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-body text-center py-4">
                <div class="mb-3 text-danger">
                    <i class="bi bi-exclamation-triangle-fill fs-1"></i>
                </div>
                <h5 class="fw-bold mb-1">Remove Item?</h5>
                <p class="text-muted small mb-3" id="removeItemName">Are you sure you want to remove this item?</p>
                <div class="d-flex gap-2 justify-content-center">
                    <button type="button" class="btn btn-outline-secondary px-4" data-bs-dismiss="modal">Cancel</button>
                    <button type="button" class="btn btn-danger px-4" id="confirmRemoveBtn">
                        <i class="bi bi-trash"></i> Remove
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var removeItemId = null;

document.querySelectorAll('.remove-item').forEach(function(btn) {
    btn.addEventListener('click', function() {
        removeItemId = this.getAttribute('data-item-id');
        var itemName = this.closest('.cart-item, tr')?.querySelector('.cart-item-title')?.textContent?.trim() || 'this item';
        document.getElementById('removeItemName').textContent = 'Remove "' + itemName + '" from your cart?';
        new bootstrap.Modal(document.getElementById('removeItemModal')).show();
    });
});

document.getElementById('confirmRemoveBtn').addEventListener('click', function() {
    if (!removeItemId) return;
    var btn = this;
    btn.classList.add('btn-loading');
    btn.disabled = true;
    $.post(baseUrl + 'api/cart/remove', { item_id: removeItemId }, function(res) {
        if (res.success) location.reload();
    });
});
</script>