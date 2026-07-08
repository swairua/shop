<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">M-Pesa Settings</h4>
    <a href="<?= base_url('admin/mpesa/transactions') ?>" class="btn btn-info"><i class="bi bi-list"></i> Transactions</a>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header"><h6 class="mb-0">API Configuration</h6></div>
            <div class="card-body">
                <form method="POST">
                    <?= csrf_field() ?>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Environment</label>
                            <select name="mpesa_environment" class="form-select">
                                <option value="sandbox" <?= $settings['mpesa_environment'] == 'sandbox' ? 'selected' : '' ?>>Sandbox</option>
                                <option value="production" <?= $settings['mpesa_environment'] == 'production' ? 'selected' : '' ?>>Production</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Shortcode (PayBill/Till)</label>
                            <input type="text" name="mpesa_shortcode" class="form-control" value="<?= $settings['mpesa_shortcode'] ?? '174379' ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Consumer Key</label>
                            <input type="text" name="mpesa_consumer_key" class="form-control" value="<?= $settings['mpesa_consumer_key'] ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Consumer Secret</label>
                            <input type="text" name="mpesa_consumer_secret" class="form-control" value="<?= $settings['mpesa_consumer_secret'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Passkey</label>
                            <input type="text" name="mpesa_passkey" class="form-control" value="<?= $settings['mpesa_passkey'] ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Till Number</label>
                            <input type="text" name="mpesa_till_number" class="form-control" value="<?= $settings['mpesa_till_number'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Initiator Name</label>
                            <input type="text" name="mpesa_initiator_name" class="form-control" value="<?= $settings['mpesa_initiator_name'] ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Initiator Password</label>
                            <input type="text" name="mpesa_initiator_password" class="form-control" value="<?= $settings['mpesa_initiator_password'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Security Certificate (Paste public key)</label>
                        <textarea name="mpesa_security_certificate" class="form-control" rows="4"><?= $settings['mpesa_security_certificate'] ?? '' ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Callback Base URL</label>
                        <input type="url" name="mpesa_callback_url" class="form-control" value="<?= $settings['mpesa_callback_url'] ?? 'http://localhost/shop/api/mpesa/callback' ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Save Settings</button>
                    <a href="<?= base_url('admin/mpesa/register-urls') ?>" class="btn btn-success">Register C2B URLs</a>
                </form>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card mb-3">
            <div class="card-header"><h6 class="mb-0">Quick Links</h6></div>
            <div class="card-body">
                <a href="<?= base_url('admin/mpesa/transactions') ?>" class="btn btn-outline-primary w-100 mb-2">View Transactions</a>
                <a href="<?= base_url('admin/reports/mpesa') ?>" class="btn btn-outline-info w-100 mb-2">M-Pesa Reports</a>
                <a href="<?= base_url('admin/mpesa/register-urls') ?>" class="btn btn-outline-success w-100">Register URLs</a>
            </div>
        </div>
        <div class="card">
            <div class="card-header"><h6 class="mb-0">API Endpoints</h6></div>
            <div class="card-body">
                <p class="small mb-1"><strong>Callback:</strong><br><code><?= $settings['mpesa_callback_url'] ?? '' ?></code></p>
                <p class="small mb-1"><strong>Validation:</strong><br><code><?= $settings['mpesa_validation_url'] ?? '' ?></code></p>
                <p class="small mb-1"><strong>Confirmation:</strong><br><code><?= $settings['mpesa_confirmation_url'] ?? '' ?></code></p>
            </div>
        </div>
    </div>
</div>
