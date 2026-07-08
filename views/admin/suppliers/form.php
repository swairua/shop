<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><?= isset($supplier) ? 'Edit Supplier' : 'New Supplier' ?></h4>
    <a href="<?= base_url('admin/suppliers') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST">
            <?= csrf_field() ?>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Company Name <span class="text-danger">*</span></label>
                    <input type="text" name="name" class="form-control" value="<?= $supplier['name'] ?? '' ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Contact Person</label>
                    <input type="text" name="contact_person" class="form-control" value="<?= $supplier['contact_person'] ?? '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control" value="<?= $supplier['email'] ?? '' ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label class="form-label">Phone</label>
                    <input type="text" name="phone" class="form-control" value="<?= $supplier['phone'] ?? '' ?>">
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="address" class="form-control" rows="2"><?= $supplier['address'] ?? '' ?></textarea>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">City</label>
                    <input type="text" name="city" class="form-control" value="<?= $supplier['city'] ?? '' ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">State</label>
                    <input type="text" name="state" class="form-control" value="<?= $supplier['state'] ?? '' ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Postal Code</label>
                    <input type="text" name="postal_code" class="form-control" value="<?= $supplier['postal_code'] ?? '' ?>">
                </div>
            </div>
            <div class="row">
                <div class="col-md-4 mb-3">
                    <label class="form-label">Country</label>
                    <input type="text" name="country" class="form-control" value="<?= $supplier['country'] ?? '' ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Tax ID</label>
                    <input type="text" name="tax_id" class="form-control" value="<?= $supplier['tax_id'] ?? '' ?>">
                </div>
                <div class="col-md-4 mb-3">
                    <label class="form-label">Status</label>
                    <select name="status" class="form-select">
                        <option value="active" <?= (isset($supplier) && $supplier['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                        <option value="inactive" <?= (isset($supplier) && $supplier['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                    </select>
                </div>
            </div>
            <div class="mb-3">
                <label class="form-label">Payment Terms</label>
                <input type="text" name="payment_terms" class="form-control" value="<?= $supplier['payment_terms'] ?? '' ?>">
            </div>
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control" rows="2"><?= $supplier['notes'] ?? '' ?></textarea>
            </div>
            <button type="submit" class="btn btn-primary"><?= isset($supplier) ? 'Update' : 'Create' ?> Supplier</button>
        </form>
    </div>
</div>
