<?php if (!isset($modal) || !$modal): ?>
<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><?= isset($coupon) ? 'Edit Coupon' : 'New Coupon' ?></h4>
    <a href="<?= base_url('admin/coupons') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>
<?php endif; ?>

<div class="card">
    <div class="card-body">
        <form method="POST">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Code <span class="text-danger">*</span></label>
                    <input type="text" name="code" class="form-control" value="<?= $coupon['code'] ?? '' ?>" required>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Type</label>
                    <select name="type" class="form-select">
                        <option value="percentage" <?= (isset($coupon) && $coupon['type'] == 'percentage') ? 'selected' : '' ?>>Percentage</option>
                        <option value="fixed" <?= (isset($coupon) && $coupon['type'] == 'fixed') ? 'selected' : '' ?>>Fixed Amount</option>
                        <option value="free_shipping" <?= (isset($coupon) && $coupon['type'] == 'free_shipping') ? 'selected' : '' ?>>Free Shipping</option>
                    </select>
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Value</label>
                    <input type="number" step="0.01" name="value" class="form-control" value="<?= $coupon['value'] ?? 0 ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Min Order Amount</label>
                    <input type="number" step="0.01" name="min_order_amount" class="form-control" value="<?= $coupon['min_order_amount'] ?? '' ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Max Discount</label>
                    <input type="number" step="0.01" name="max_discount" class="form-control" value="<?= $coupon['max_discount'] ?? '' ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" <?= (isset($coupon) && $coupon['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= (isset($coupon) && $coupon['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Usage Limit</label>
                    <input type="number" name="usage_limit" class="form-control" value="<?= $coupon['usage_limit'] ?? '' ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Per Customer Limit</label>
                    <input type="number" name="usage_per_customer" class="form-control" value="<?= $coupon['usage_per_customer'] ?? '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Start Date</label>
                    <input type="datetime-local" name="starts_at" class="form-control" value="<?= isset($coupon) && $coupon['starts_at'] ? date('Y-m-d\TH:i', strtotime($coupon['starts_at'])) : '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Expiry Date</label>
                    <input type="datetime-local" name="expires_at" class="form-control" value="<?= isset($coupon) && $coupon['expires_at'] ? date('Y-m-d\TH:i', strtotime($coupon['expires_at'])) : '' ?>">
                </div>
            </div>
            <button type="submit" class="btn btn-primary"><?= isset($coupon) ? 'Update' : 'Create' ?> Coupon</button>
        </form>
    </div>
</div>
