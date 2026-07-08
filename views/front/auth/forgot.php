<div class="auth-split">
    <div class="auth-brand">
        <div class="auth-brand-content">
            <i class="bi bi-key" style="font-size:3rem;opacity:.8;"></i>
            <h2>Forgot Password?</h2>
            <p>Enter your email and we'll send you a reset link.</p>
            <div class="mt-4">
                <p class="mb-1">Remember your password?</p>
                <a href="<?= base_url('login') ?>" class="btn btn-outline-light btn-sm rounded-pill px-4">Back to Login</a>
            </div>
        </div>
    </div>
    <div class="auth-form">
        <div class="auth-form-inner">
            <h4 class="fw-bold mb-1">Reset Password</h4>
            <p class="text-muted mb-4">We'll send you a reset link</p>
            <form method="POST">
                <?= csrf_field() ?>
                <div class="floating-label-group">
                    <input type="email" name="email" class="form-control" placeholder=" " required>
                    <label>Email Address</label>
                </div>
                <button type="submit" class="btn btn-primary w-100 btn-lg">Send Reset Link</button>
            </form>
            <p class="text-center mt-3">
                <a href="<?= base_url('login') ?>" class="text-decoration-none small"><i class="bi bi-arrow-left"></i> Back to Login</a>
            </p>
        </div>
    </div>
</div>