<!DOCTYPE html>
<html><head><title>M-Pesa Receipt</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<style>body{background:#f8f9fa}.receipt{max-width:500px;margin:40px auto;background:#fff;padding:30px;border-radius:10px;box-shadow:0 0 20px rgba(0,0,0,.1)}.receipt-header{text-align:center;border-bottom:2px dashed #dee2e6;padding-bottom:15px;margin-bottom:15px}.receipt-table td{padding:8px 0}.receipt-table td:last-child{text-align:right;font-weight:600}@media print{.receipt{box-shadow:none;margin:0 auto}body{background:#fff}.btn{display:none!important}}</style></head>
<body>
<div class="receipt">
    <div class="receipt-header">
        <h4><?= App::getSetting('shop_name', 'My Shop') ?></h4>
        <p class="mb-0">M-Pesa Payment Receipt</p>
        <small class="text-muted"><?= date('Y-m-d H:i:s') ?></small>
    </div>

    <?php if ($transaction): ?>
    <table class="receipt-table w-100">
        <tr><td>M-Pesa Receipt No.</td><td><strong><?= $transaction['mpesa_receipt_number'] ?? 'N/A' ?></strong></td></tr>
        <tr><td>Transaction Reference</td><td><strong><?= $transaction['merchant_request_id'] ?? 'N/A' ?></strong></td></tr>
        <tr><td>Transaction Date</td><td><strong><?= format_date($transaction['transaction_date']) ?></strong></td></tr>
        <tr><td>Customer Name</td><td><strong><?= $transaction['customer_name'] ?? 'N/A' ?></strong></td></tr>
        <tr><td>Phone Number</td><td><strong><?= $transaction['phone_number'] ?></strong></td></tr>
        <tr><td>Invoice Number</td><td><strong><?= $transaction['order_number'] ?? 'N/A' ?></strong></td></tr>
        <tr><td>Amount Paid</td><td><strong><?= format_price($transaction['amount']) ?></strong></td></tr>
        <tr><td>Outstanding Balance</td><td><strong><?= format_price($transaction['balance'] ?? 0) ?></strong></td></tr>
        <tr><td>Status</td><td><strong><?= ucfirst($transaction['status']) ?></strong></td></tr>
    </table>

    <div class="text-center mt-4">
        <p class="text-muted small mb-1">Thank you for your payment!</p>
        <button onclick="window.print()" class="btn btn-primary btn-sm"><i class="bi bi-printer"></i> Print Receipt</button>
    </div>
    <?php else: ?>
    <p class="text-center text-muted">Transaction not found</p>
    <?php endif; ?>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
