<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <style>
        :root {
            --admin-primary: <?= App::getSetting('primary_color', '#D22B2B') ?>;
            --admin-bg: <?= App::getSetting('header_bg', '#1A1A2E') ?>;
        }
        body { background: linear-gradient(135deg, var(--admin-bg) 0%, var(--admin-primary) 100%); min-height: 100vh; display: flex; align-items: center; }
        .card { border: none; border-radius: 15px; box-shadow: 0 10px 40px rgba(0,0,0,.2); }
        .btn-primary { background: var(--admin-primary); border-color: var(--admin-primary); }
    </style>
</head>
<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="card">
                    <div class="card-body p-5">
                        <h3 class="text-center mb-4">
                            <?php if (App::getSetting('shop_logo')): ?>
                                <img src="<?= base_url('uploads/settings/' . App::getSetting('shop_logo')) ?>" height="40" alt="Logo">
                            <?php else: ?>
                                <i class="bi bi-shop"></i> Admin Login
                            <?php endif; ?>
                        </h3>
                        <?php if (has_flash('error')): ?>
                            <div class="alert alert-danger"><?= session_flash('error') ?></div>
                        <?php endif; ?>
                        <form method="POST">
                            <?= csrf_field() ?>
                            <div class="mb-3">
                                <label class="form-label">Email</label>
                                <input type="email" name="email" class="form-control form-control-lg" required>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Password</label>
                                <input type="password" name="password" class="form-control form-control-lg" required>
                            </div>
                            <div class="mb-3 form-check">
                                <input type="checkbox" name="remember" class="form-check-input" id="remember">
                                <label class="form-check-label" for="remember">Remember Me</label>
                            </div>
                            <button type="submit" class="btn btn-primary btn-lg w-100">Login</button>
                        </form>
                        <p class="text-center text-muted mt-3 small">Default: admin@shop.com / admin123</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
