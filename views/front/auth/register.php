<div class="auth-split">
    <div class="auth-brand">
        <div class="auth-brand-content">
            <i class="bi bi-person-plus" style="font-size:3rem;opacity:.8;"></i>
            <h2>Join Us</h2>
            <p>Create an account to enjoy exclusive offers, faster checkout, and order tracking.</p>
            <div class="mt-4">
                <p class="mb-1">Already have an account?</p>
                <a href="<?= base_url('login') ?>" class="btn btn-outline-light btn-sm rounded-pill px-4">Sign In</a>
            </div>
        </div>
    </div>
    <div class="auth-form">
        <div class="auth-form-inner">
            <h4 class="fw-bold mb-1">Create Account</h4>
            <p class="text-muted mb-4">Fill in your details to get started</p>

            <form method="POST" action="<?= base_url('auth/register') ?>">
                <?= csrf_field() ?>

                <div class="floating-label-group">
                    <input type="text" name="name" class="form-control" placeholder=" " required>
                    <label>Full Name</label>
                </div>

                <div class="floating-label-group">
                    <input type="email" name="email" class="form-control" placeholder=" " required>
                    <label>Email Address</label>
                </div>

                <div class="floating-label-group">
                    <input type="tel" name="phone" class="form-control" placeholder=" ">
                    <label>Phone Number</label>
                </div>

                <div class="floating-label-group">
                    <input type="password" name="password" class="form-control" placeholder=" " required id="regPassword">
                    <label>Password</label>
                    <button type="button" class="password-toggle" onclick="togglePass('regPassword', this)" tabindex="-1">
                        <i class="bi bi-eye"></i>
                    </button>
                </div>

                <div class="mb-3">
                    <div class="progress" style="height:4px;" id="pwStrength">
                        <div class="progress-bar" role="progressbar" style="width:0%;"></div>
                    </div>
                    <small class="text-muted" id="pwText">Password strength</small>
                </div>

                <button type="submit" class="btn btn-primary w-100 btn-lg">Create Account</button>
            </form>

            <p class="text-center mt-3 small text-muted">
                Already have an account? <a href="<?= base_url('login') ?>" class="fw-bold">Login</a>
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

document.getElementById('regPassword').addEventListener('input', function() {
    var val = this.value;
    var bar = document.querySelector('#pwStrength .progress-bar');
    var text = document.getElementById('pwText');
    var strength = 0;
    if (val.length >= 6) strength += 25;
    if (val.length >= 10) strength += 25;
    if (/[A-Z]/.test(val)) strength += 15;
    if (/[0-9]/.test(val)) strength += 15;
    if (/[^A-Za-z0-9]/.test(val)) strength += 20;
    bar.style.width = Math.min(strength, 100) + '%';
    if (strength < 30) { bar.className = 'progress-bar bg-danger'; text.textContent = 'Weak'; }
    else if (strength < 60) { bar.className = 'progress-bar bg-warning'; text.textContent = 'Fair'; }
    else if (strength < 80) { bar.className = 'progress-bar bg-info'; text.textContent = 'Good'; }
    else { bar.className = 'progress-bar bg-success'; text.textContent = 'Strong'; }
});
</script>