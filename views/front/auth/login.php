<div class="auth-split">
    <div class="auth-brand">
        <div class="auth-brand-content">
            <i class="bi bi-shop" style="font-size:3rem;opacity:.8;"></i>
            <h2>Welcome Back</h2>
            <p>Sign in to access your account, view orders, and manage your wishlist.</p>
            <div class="mt-4">
                <p class="mb-1">New here?</p>
                <a href="<?= base_url('register') ?>" class="btn btn-outline-light btn-sm rounded-pill px-4">Create Account</a>
            </div>
        </div>
    </div>
    <div class="auth-form">
        <div class="auth-form-inner">
            <h4 class="fw-bold mb-1">Login</h4>
            <p class="text-muted mb-4">Sign in to your account</p>

            <?php if (has_flash('error')): ?>
                <div class="alert alert-danger"><?= session_flash('error') ?></div>
            <?php endif; ?>

            <form method="POST" action="<?= base_url('auth/login') ?>">
                <?= csrf_field() ?>

                <div class="floating-label-group">
                    <input type="email" name="email" class="form-control" placeholder=" " required>
                    <label>Email Address</label>
                </div>

                <div class="floating-label-group">
                    <input type="password" name="password" class="form-control" placeholder=" " required id="loginPassword">
                    <label>Password</label>
                    <button type="button" class="password-toggle" onclick="togglePass('loginPassword', this)" tabindex="-1">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>

                <div class="d-flex justify-content-between align-items-center mb-3">
                    <div class="form-check">
                        <input type="checkbox" name="remember" class="form-check-input" id="remember">
                        <label class="form-check-label small" for="remember">Remember Me</label>
                    </div>
                    <a href="<?= base_url('forgot-password') ?>" class="small text-primary text-decoration-none">Forgot Password?</a>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-lg">Login</button>
            </form>

            <div class="text-center mt-3">
                <p class="text-muted small">or continue with</p>
                <div class="d-flex gap-2">
                    <a href="#" class="social-login-btn flex-fill"><i class="bi bi-google"></i> Google</a>
                    <a href="#" class="social-login-btn flex-fill"><i class="bi bi-facebook"></i> Facebook</a>
                </div>
            </div>

            <p class="text-center mt-3 small text-muted">
                Don't have an account? <a href="<?= base_url('register') ?>" class="fw-bold">Register</a>
            </p>
        </div>
    </div>
</div>

<script>
function togglePass(id, btn) {
    var input = document.getElementById(id);
    if (input.type === 'password') {
        input.type = 'text';
        btn.innerHTML = '<i class="bi bi-eye-slash"></i>';
    } else {
        input.type = 'password';
        btn.innerHTML = '<i class="bi bi-eye"></i>';
    }
}
</script>