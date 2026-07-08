<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Adjust Inventory</h4>
    <a href="<?= base_url('admin/inventory') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="row">
    <div class="col-md-6">
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0">Current Stock: <strong><?= $product['quantity'] ?></strong></h6></div>
            <div class="card-body">
                <form method="POST">
                    <?= csrf_field() ?>
                    <div class="mb-3">
                        <label class="form-label">Product</label>
                        <input type="text" class="form-control" value="<?= $product['name'] ?> (<?= $product['sku'] ?>)" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Movement Type</label>
                        <select name="type" class="form-select" required>
                            <option value="purchase">Purchase (Stock In)</option>
                            <option value="return">Return (Stock In)</option>
                            <option value="sale">Sale (Stock Out)</option>
                            <option value="adjustment">Adjustment (Manual)</option>
                            <option value="transfer_out">Transfer Out</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Quantity</label>
                        <input type="number" name="quantity" class="form-control" required min="1">
                        <small class="text-muted">Enter the number of units. Purchases/returns add stock. Sales/transfers remove stock.</small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Notes</label>
                        <textarea name="notes" class="form-control" rows="2" placeholder="Reason for adjustment..."></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Adjust Stock</button>
                </form>
            </div>
        </div>
    </div>
</div>
