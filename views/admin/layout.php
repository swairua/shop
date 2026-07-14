<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= App::getSetting('shop_name', 'Admin') ?> - Admin</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/admin.css') ?>" rel="stylesheet">
    <?php if (isset($extraCss)) echo $extraCss; ?>
    <style>
        :root {
            --bs-primary: <?= App::getSetting('primary_color', '#D22B2B') ?>;
            --bs-primary-rgb: <?php $c = App::getSetting('primary_color', '#D22B2B'); echo hexdec(substr($c,1,2)) . ',' . hexdec(substr($c,3,2)) . ',' . hexdec(substr($c,5,2)); ?>;
            --bs-warning: <?= App::getSetting('accent_color', '#FFD700') ?>;
            --bs-warning-rgb: <?php $c = App::getSetting('accent_color', '#FFD700'); echo hexdec(substr($c,1,2)) . ',' . hexdec(substr($c,3,2)) . ',' . hexdec(substr($c,5,2)); ?>;
            --admin-sidebar-bg: <?= App::getSetting('header_bg', '#1A1A2E') ?>;
            --admin-sidebar-active: <?= App::getSetting('primary_color', '#D22B2B') ?>;
        }
        .btn-primary { --bs-btn-bg: var(--bs-primary); --bs-btn-border-color: var(--bs-primary); }
        .btn-primary:hover { --bs-btn-hover-bg: <?php $c = App::getSetting('primary_color', '#D22B2B'); echo 'rgba(' . hexdec(substr($c,1,2)) . ',' . hexdec(substr($c,3,2)) . ',' . hexdec(substr($c,5,2)) . ',.85)'; ?>; }
        .text-primary { color: var(--bs-primary) !important; }
        .bg-primary { background-color: var(--bs-primary) !important; }
    </style>
</head>
<body>
    <div class="d-flex" id="wrapper">
        <div class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <a href="<?= base_url('admin/dashboard') ?>" class="text-white text-decoration-none">
                    <?php if (App::getSetting('shop_logo')): ?>
                        <img src="<?= base_url('uploads/settings/' . App::getSetting('shop_logo')) ?>" height="36" alt="Logo">
                    <?php else: ?>
                        <h5><i class="bi bi-shop"></i> <?= App::getSetting('shop_name', 'Admin') ?></h5>
                    <?php endif; ?>
                </a>
            </div>
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a class="nav-link <?= is_active('admin/dashboard') ?>" href="<?= base_url('admin/dashboard') ?>">
                        <i class="bi bi-speedometer2"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= is_active('admin/categories') ?>" href="<?= base_url('admin/categories') ?>">
                        <i class="bi bi-folder"></i> Categories
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= is_active('admin/brands') ?>" href="<?= base_url('admin/brands') ?>">
                        <i class="bi bi-tag"></i> Brands
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= is_active('admin/products') ?>" href="<?= base_url('admin/products') ?>">
                        <i class="bi bi-box"></i> Products
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= is_active('admin/inventory') ?>" href="<?= base_url('admin/inventory') ?>">
                        <i class="bi bi-boxes"></i> Inventory
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= is_active('admin/orders') ?>" href="<?= base_url('admin/orders') ?>">
                        <i class="bi bi-cart-check"></i> Orders
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= is_active('admin/customers') ?>" href="<?= base_url('admin/customers') ?>">
                        <i class="bi bi-people"></i> Customers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= is_active('admin/suppliers') ?>" href="<?= base_url('admin/suppliers') ?>">
                        <i class="bi bi-truck"></i> Suppliers
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= is_active('admin/coupons') ?>" href="<?= base_url('admin/coupons') ?>">
                        <i class="bi bi-percent"></i> Coupons
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= is_active('admin/reviews') ?>" href="<?= base_url('admin/reviews') ?>">
                        <i class="bi bi-star"></i> Reviews
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= is_active('admin/shipping') ?>" href="<?= base_url('admin/shipping') ?>">
                        <i class="bi bi-truck"></i> Shipping
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= is_active('admin/mpesa') ?>" href="<?= base_url('admin/mpesa') ?>">
                        <i class="bi bi-phone"></i> M-Pesa
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= is_active('admin/reports') ?>" href="<?= base_url('admin/reports') ?>">
                        <i class="bi bi-graph-up"></i> Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= is_active('admin/users') ?>" href="<?= base_url('admin/users') ?>">
                        <i class="bi bi-person-lock"></i> Users
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?= is_active('admin/settings') ?>" href="<?= base_url('admin/settings') ?>">
                        <i class="bi bi-gear"></i> Settings
                    </a>
                </li>
            </ul>
        </div>

        <div id="content-wrapper">
            <nav class="navbar navbar-expand navbar-light bg-white shadow-sm px-3">
                <button class="btn btn-sm" id="sidebarToggle"><i class="bi bi-list"></i></button>
                <div class="ms-auto d-flex align-items-center">
                    <span class="me-3"><i class="bi bi-person-circle"></i> <?= $_SESSION['admin_name'] ?? '' ?></span>
                    <a href="<?= base_url('') ?>" class="btn btn-sm btn-outline-primary me-2" target="_blank"><i class="bi bi-eye"></i> View Shop</a>
                    <a href="<?= base_url('admin/logout') ?>" class="btn btn-sm btn-outline-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
                </div>
            </nav>

            <div class="container-fluid px-4 py-3">
                <?php if (has_flash('success')): ?>
                    <div class="alert alert-success alert-dismissible fade show"><?= session_flash('success') ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endif; ?>
                <?php if (has_flash('error')): ?>
                    <div class="alert alert-danger alert-dismissible fade show"><?= session_flash('error') ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
                <?php endif; ?>
                <?php if (isset($content) && is_callable($content)) $content(); ?>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="<?= base_url('assets/js/admin.js') ?>"></script>
    <?php if (isset($extraJs)) echo $extraJs; ?>

<!-- Delete Confirmation Modal -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog modal-sm modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title">Confirm Delete</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <p class="mb-0">Are you sure you want to delete this item? This action cannot be undone.</p>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <a href="#" class="btn btn-danger" id="confirmDeleteBtn">Delete</a>
            </div>
        </div>
    </div>
</div>

<!-- Inline Edit Modal -->
<div class="modal fade" id="editModal" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="editModalTitle">Edit</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="editModalBody">
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status"></div>
                    <p class="mt-2 text-muted">Loading...</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Delete modal - set confirm URL from data-url
    var dModal = document.getElementById('deleteModal');
    if (dModal) {
        dModal.addEventListener('show.bs.modal', function(e) {
            var btn = e.relatedTarget;
            if (btn) {
                document.getElementById('confirmDeleteBtn').setAttribute('href', btn.getAttribute('data-url'));
            }
        });
    }
    // Inline edit modal - fetch form content
    document.addEventListener('click', function(e) {
        var btn = e.target.closest('[data-modal="1"]');
        if (btn) {
            e.preventDefault();
            var url = btn.getAttribute('href');
            var title = btn.getAttribute('data-title') || 'Edit';
            var body = document.getElementById('editModalBody');
            document.getElementById('editModalTitle').textContent = title;
            body.innerHTML = '<div class="text-center py-4"><div class="spinner-border text-primary" role="status"></div><p class="mt-2 text-muted">Loading...</p></div>';
            var modal = new bootstrap.Modal(document.getElementById('editModal'));
            modal.show();
            fetch(url + (url.indexOf('?') > -1 ? '&' : '?') + 'partial=1')
                .then(function(r) { return r.text(); })
                .then(function(html) {
                    body.innerHTML = html;
                    var form = body.querySelector('form');
                    if (form && !form.getAttribute('action')) {
                        form.action = url;
                    }
                    if (typeof jQuery !== 'undefined' && jQuery.fn.select2) {
                        jQuery('.form-select', body).select2({dropdownParent: body.parentElement});
                    }
                })
                .catch(function() {
                    body.innerHTML = '<div class="alert alert-danger">Failed to load form.</div>';
                });
        }
    });
});
</script>
</body>
</html>

