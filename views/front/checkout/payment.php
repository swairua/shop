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
                    <p class="text-muted small" id="countdownText">Waiting for payment confirmation...</p>

                    <div id="paymentStatus" class="alert d-none"></div>

                    <button class="btn btn-outline-primary" id="checkPaymentStatus" style="display:none">
                        <i class="bi bi-arrow-repeat"></i> Check Payment Status
                    </button>
                    <a href="<?= base_url('orders/confirmation/' . $order['id']) ?>" class="btn btn-success" id="viewOrderBtn" style="display:none">
                        <i class="bi bi-check-circle"></i> View Order
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
var checkoutId = '<?= $checkout_id ?>';
var orderId = '<?= $order['id'] ?? 0 ?>';
var checkInterval;
var countdown = 60;

var statusEl = document.getElementById('paymentStatus');
var spinner = document.querySelector('.spinner-border');
var countdownText = document.getElementById('countdownText');
var viewBtn = document.getElementById('viewOrderBtn');
var checkBtn = document.getElementById('checkPaymentStatus');

function checkStatus() {
    if (!checkoutId) return;
    fetch('<?= base_url('cart/checkout/payment') ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'action=check_status&checkout_request_id=' + encodeURIComponent(checkoutId)
    }).then(function(r) { return r.json(); }).then(function(res) {
        if (res.ResultCode === '0' || res.resultCode === 0 || res.ResultCode === 0) {
            clearInterval(checkInterval);
            statusEl.className = 'alert alert-success';
            statusEl.textContent = 'Payment confirmed!';
            statusEl.classList.remove('d-none');
            spinner.style.display = 'none';
            countdownText.textContent = 'Payment successful!';
            viewBtn.style.display = 'inline-block';
            checkBtn.style.display = 'none';
        }
    }).catch(function() {});
}

function tick() {
    countdown--;
    if (countdown <= 0) {
        clearInterval(checkInterval);
        spinner.style.display = 'none';
        countdownText.textContent = 'Payment still pending.';
        statusEl.className = 'alert alert-warning';
        statusEl.innerHTML = 'Payment still pending. <button class="btn btn-sm btn-primary" onclick="checkStatus()">Check Again</button>';
        statusEl.classList.remove('d-none');
        checkBtn.style.display = 'inline-block';
    } else {
        countdownText.textContent = 'Auto-retrying in ' + countdown + 's...';
    }
}

if (checkoutId) {
    checkInterval = setInterval(function() { checkStatus(); tick(); }, 1000);
}
</script>
