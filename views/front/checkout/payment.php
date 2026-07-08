<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card text-center">
                <div class="card-body p-5">
                    <div class="mb-4">
                        <i class="bi bi-phone fs-1 text-success"></i>
                    </div>
                    <h4>M-Pesa Payment</h4>
                    <p class="text-muted">Please check your phone for the STK Push prompt</p>
                    <p class="mb-2">Amount: <strong><?= format_price($order['total'] ?? 0) ?></strong></p>
                    <p class="mb-3">Order: <strong>#<?= $order['order_number'] ?? '' ?></strong></p>

                    <div class="d-flex justify-content-center mb-3">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Waiting for payment...</span>
                        </div>
                    </div>
                    <p class="text-muted small">Waiting for payment confirmation...</p>

                    <div id="paymentStatus" class="alert d-none"></div>

                    <button class="btn btn-outline-primary" id="checkPaymentStatus" style="display:none">
                        Check Payment Status
                    </button>
                    <a href="<?= base_url('orders/confirmation/' . $order['id']) ?>" class="btn btn-success" id="viewOrderBtn" style="display:none">
                        View Order
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
let checkInterval;
const checkoutId = '<?= $checkout_id ?>';
const orderId = '<?= $order['id'] ?? 0 ?>';

function checkStatus() {
    if (!checkoutId) return;
    $.post('<?= base_url('cart/checkout/payment') ?>', { action: 'check_status', checkout_request_id: checkoutId }, function(res) {
        if (res.ResultCode === '0' || res.resultCode === 0) {
            clearInterval(checkInterval);
            $('#paymentStatus').removeClass('d-none alert-warning').addClass('alert-success').html('Payment confirmed!');
            $('.spinner-border').hide();
            $('#viewOrderBtn').show();
        }
    });
}

if (checkoutId) {
    checkInterval = setInterval(checkStatus, 5000);
    setTimeout(function() {
        clearInterval(checkInterval);
        $('#paymentStatus').removeClass('d-none').addClass('alert-warning').html('Payment still pending. <button class="btn btn-sm btn-primary" onclick="checkStatus()">Check Again</button>');
        $('.spinner-border').hide();
        $('#checkPaymentStatus').show();
    }, 60000);
}
</script>
