<div class="container py-4">
    <h3 class="fw-bold mb-4"><i class="bi bi-credit-card"></i> Checkout</h3>

    <div class="checkout-steps">
        <div class="checkout-step completed">Information</div>
        <div class="checkout-step active">Shipping</div>
        <div class="checkout-step">Payment</div>
        <div class="checkout-step">Confirm</div>
    </div>

    <form method="POST" id="checkoutForm">
        <?= csrf_field() ?>
        <input type="hidden" name="action" value="checkout">
        <div class="row">
            <div class="col-lg-7">
                <div class="checkout-section">
                    <div class="section-header">
                        <span class="step-number">1</span> Contact Information
                    </div>
                    <div class="section-body">
                        <?php if ($customer): ?>
                            <div class="d-flex align-items-center gap-2">
                                <i class="bi bi-person-circle fs-3 text-muted"></i>
                                <div>
                                    <strong><?= $customer['name'] ?></strong><br>
                                    <span class="text-muted small"><?= $customer['email'] ?></span>
                                </div>
                            </div>
                        <?php else: ?>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="floating-label-group">
                                        <input type="text" name="name" class="form-control" placeholder=" " required>
                                        <label>Full Name <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="floating-label-group">
                                        <input type="email" name="email" class="form-control" placeholder=" " required>
                                        <label>Email <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="floating-label-group">
                                        <input type="tel" name="phone" class="form-control" placeholder=" " required>
                                        <label>Phone <span class="text-danger">*</span></label>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="checkout-section">
                    <div class="section-header">
                        <span class="step-number">2</span> Shipping Address
                    </div>
                    <div class="section-body">
                        <?php if (!empty($addresses)): ?>
                            <?php foreach ($addresses as $addr): ?>
                            <div class="form-check mb-2">
                                <input type="radio" name="address_id" value="<?= $addr['id'] ?>" class="form-check-input" id="addr<?= $addr['id'] ?>" <?= $addr['is_default'] ? 'checked' : '' ?>>
                                <label class="form-check-label" for="addr<?= $addr['id'] ?>">
                                    <?= $addr['address_line1'] ?>, <?= $addr['city'] ?>, <?= $addr['country'] ?>
                                </label>
                            </div>
                            <?php endforeach; ?>
                            <hr>
                        <?php endif; ?>
                        <div class="row">
                            <div class="col-12 mb-3">
                                <div class="floating-label-group">
                                    <input type="text" name="address_line1" class="form-control" placeholder=" " required>
                                    <label>Address Line 1 <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-12 mb-3">
                                <div class="floating-label-group">
                                    <input type="text" name="address_line2" class="form-control" placeholder=" ">
                                    <label>Address Line 2</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="floating-label-group">
                                    <input type="text" name="city" class="form-control" placeholder=" " required>
                                    <label>City <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="floating-label-group">
                                    <input type="text" name="state" class="form-control" placeholder=" ">
                                    <label>State/Region</label>
                                </div>
                            </div>
                            <div class="col-md-4 mb-3">
                                <div class="floating-label-group">
                                    <input type="text" name="postal_code" class="form-control" placeholder=" ">
                                    <label>Postal Code</label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="floating-label-group">
                                    <input type="text" name="country" class="form-control" value="Kenya" placeholder=" " required>
                                    <label>Country <span class="text-danger">*</span></label>
                                </div>
                            </div>
                            <div class="col-md-6 mb-3">
                                <div class="floating-label-group">
                                    <input type="tel" name="phone" class="form-control" placeholder=" ">
                                    <label>Phone</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="checkout-section">
                    <div class="section-header">
                        <span class="step-number">3</span> Payment Method
                    </div>
                    <div class="section-body">
                        <?php foreach ($paymentMethods as $pm): ?>
                        <div class="form-check mb-2">
                            <input type="radio" name="payment_method" value="<?= $pm['slug'] ?>" class="form-check-input" id="pm<?= $pm['id'] ?>" <?= $pm['is_default'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="pm<?= $pm['id'] ?>">
                                <strong><?= $pm['name'] ?></strong>
                                <?php if ($pm['description']): ?><br><small class="text-muted"><?= $pm['description'] ?></small><?php endif; ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                        <div class="form-check mb-2">
                            <input type="radio" name="payment_method" value="paypal" class="form-check-input" id="pmPaypal">
                            <label class="form-check-label" for="pmPaypal">
                                <strong><i class="bi bi-paypal" style="color:#003087;"></i> PayPal</strong>
                                <br><small class="text-muted">Pay with your PayPal account</small>
                            </label>
                        </div>
                        <?php $waNum = App::getSetting('whatsapp_number', ''); ?>
                        <?php if ($waNum): ?>
                        <hr>
                        <div class="form-check mb-2">
                            <input type="radio" name="payment_method" value="whatsapp" class="form-check-input" id="pmWhatsapp">
                            <label class="form-check-label" for="pmWhatsapp">
                                <strong class="text-success"><i class="bi bi-whatsapp"></i> WhatsApp Order</strong>
                                <br><small class="text-muted">Order via WhatsApp and pay on delivery</small>
                            </label>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="checkout-section">
                    <div class="section-header">
                        <span class="step-number">4</span> Shipping Method
                    </div>
                    <div class="section-body">
                        <?php foreach ($shippingMethods as $sm): ?>
                        <div class="form-check mb-2">
                            <input type="radio" name="shipping_method" value="<?= $sm['slug'] ?>" class="form-check-input" id="sm<?= $sm['id'] ?>" <?= $sm['is_default'] ? 'checked' : '' ?>>
                            <label class="form-check-label" for="sm<?= $sm['id'] ?>">
                                <strong><?= $sm['name'] ?></strong>
                                <?php if ($sm['description']): ?><br><small class="text-muted"><?= $sm['description'] ?></small><?php endif; ?>
                            </label>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>

                <div class="mb-3">
                    <div class="floating-label-group">
                        <textarea name="notes" class="form-control" rows="2" placeholder=" "></textarea>
                        <label>Order Notes (Optional)</label>
                    </div>
                </div>
            </div>

            <div class="col-lg-5">
                <div class="cart-summary-card card">
                    <div class="card-header">Order Summary</div>
                    <div class="card-body p-0">
                        <div class="p-3 border-bottom">
                            <?php foreach ($cart['items'] as $item): ?>
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <div class="d-flex align-items-center gap-2">
                                    <img src="<?= product_image($item['featured_image'] ?? '', '40x40', $item['name']) ?>" width="40" height="40" style="object-fit:cover;border-radius:var(--radius);">
                                    <div>
                                        <small class="fw-medium"><?= $item['name'] ?></small>
                                        <small class="d-block text-muted">× <?= $item['quantity'] ?></small>
                                    </div>
                                </div>
                                <span class="fw-medium"><?= format_price($item['total_price']) ?></span>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <div class="list-group list-group-flush">
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Subtotal</span>
                                <strong><?= format_price($cart['subtotal'] ?? 0) ?></strong>
                            </div>
                            <div class="list-group-item p-3">
                                <?php if (!$cart['coupon_id']): ?>
                                <div class="input-group input-group-sm">
                                    <input type="text" class="form-control" id="checkoutCouponCode" placeholder="Coupon code">
                                    <button class="btn btn-outline-primary" id="checkoutApplyCoupon" type="button">Apply</button>
                                </div>
                                <div id="checkoutCouponMsg" class="mt-1 small"></div>
                                <?php else: ?>
                                <div class="d-flex justify-content-between align-items-center small text-success">
                                    <span>Coupon <strong><?= $cart['coupon_code'] ?></strong> applied</span>
                                    <button class="btn-close" id="checkoutRemoveCoupon" type="button"></button>
                                </div>
                                <?php endif; ?>
                            </div>
                            <?php if (($cart['discount'] ?? 0) > 0): ?>
                            <div class="list-group-item d-flex justify-content-between text-success">
                                <span>Discount</span>
                                <strong>-<?= format_price($cart['discount']) ?></strong>
                            </div>
                            <?php endif; ?>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Tax</span>
                                <strong><?= format_price($cart['tax'] ?? 0) ?></strong>
                            </div>
                            <div class="list-group-item d-flex justify-content-between">
                                <span>Shipping</span>
                                <strong><?= format_price($cart['shipping_cost'] ?? 0) ?></strong>
                            </div>
                            <div class="list-group-item total d-flex justify-content-between">
                                <span>Total</span>
                                <strong><?= format_price($cart['total'] ?? 0) ?></strong>
                            </div>
                        </div>
                    </div>
                    <div class="card-body">
                        <button type="submit" class="btn btn-primary btn-lg w-100" id="placeOrderBtn">
                            <i class="bi bi-credit-card"></i> Place Order
                        </button>
                        <div id="paypal-button-container" class="mt-2" style="display:none;"></div>
                        <div class="text-center mt-2">
                            <small class="text-muted"><i class="bi bi-shield-check"></i> Secure checkout</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
document.getElementById('checkoutForm').addEventListener('submit', function(e) {
    if (document.querySelector('input[name="payment_method"]:checked')?.value === 'paypal') {
        e.preventDefault();
        return;
    }
    var btn = document.getElementById('placeOrderBtn');
    btn.classList.add('btn-loading');
    btn.disabled = true;
});

var paypalClientId = '<?= App::getSetting('paypal_client_id', '') ?>';
var pmRadios = document.querySelectorAll('input[name="payment_method"]');
var placeOrderBtn = document.getElementById('placeOrderBtn');
var paypalContainer = document.getElementById('paypal-button-container');

function togglePaypalButton() {
    var selected = document.querySelector('input[name="payment_method"]:checked');
    if (selected && selected.value === 'paypal' && paypalClientId) {
        placeOrderBtn.style.display = 'none';
        paypalContainer.style.display = 'block';
        if (typeof paypal !== 'undefined') {
            paypal.Buttons({
                createOrder: function() {
                    return fetch('<?= base_url('api/paypal/create-order') ?>', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: new URLSearchParams(new FormData(document.getElementById('checkoutForm'))).toString()
                    }).then(function(r) { return r.json(); }).then(function(d) {
                        if (d.error) throw new Error(d.error);
                        return d.id;
                    });
                },
                onApprove: function(data) {
                    return fetch('<?= base_url('api/paypal/capture-order') ?>', {
                        method: 'POST',
                        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                        body: 'paypal_order_id=' + data.orderID
                    }).then(function(r) { return r.json(); }).then(function(d) {
                        if (d.success) {
                            window.location.href = '<?= base_url('orders/confirmation/') ?>' + d.order_id;
                        } else {
                            showToast(d.message || 'Payment failed', 'error');
                        }
                    });
                },
                onError: function(err) {
                    showToast('PayPal error: ' + err.toString(), 'error');
                }
            }).render('#paypal-button-container');
        }
    } else {
        placeOrderBtn.style.display = 'block';
        paypalContainer.style.display = 'none';
    }
}

pmRadios.forEach(function(r) { r.addEventListener('change', togglePaypalButton); });
togglePaypalButton();

/* Checkout coupon AJAX */
document.getElementById('checkoutApplyCoupon')?.addEventListener('click', function() {
    var code = document.getElementById('checkoutCouponCode').value;
    if (!code) return;
    var msg = document.getElementById('checkoutCouponMsg');
    fetch('<?= base_url('api/cart/apply-coupon') ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'code=' + encodeURIComponent(code)
    }).then(function(r) { return r.json(); }).then(function(res) {
        msg.textContent = res.message;
        msg.style.color = res.success ? 'var(--bs-success)' : 'var(--bs-danger)';
        if (res.success) setTimeout(function() { location.reload(); }, 1000);
    });
});

document.getElementById('checkoutRemoveCoupon')?.addEventListener('click', function() {
    fetch('<?= base_url('api/cart/remove-coupon') ?>', { method: 'POST' })
    .then(function(r) { return r.json(); })
    .then(function(res) { if (res.success) location.reload(); });
});
</script>