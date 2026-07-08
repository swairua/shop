<section class="hero-section">
    <div class="container text-center text-lg-start">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <h1 class="hero-title mb-3">Welcome to <?= App::getSetting('shop_name', 'My Shop') ?></h1>
                <p class="hero-subtitle mb-4"><?= App::getSetting('meta_description', 'Discover amazing products at unbeatable prices. Shop the latest trends with fast delivery.') ?></p>
                <div class="d-flex gap-3 justify-content-center justify-content-lg-start">
                    <a href="<?= base_url('shop') ?>" class="btn btn-primary btn-lg">
                        <i class="bi bi-bag-check"></i> Shop Now
                    </a>
                    <a href="#featured" class="btn btn-outline-light btn-lg">
                        <i class="bi bi-grid"></i> Browse Categories
                    </a>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block text-center">
                <div style="font-size:12rem; opacity:.15; line-height:1;">
                    <i class="bi bi-bag-check-fill"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="hero-wave">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none">
            <path fill="#f8fafc" d="M0,20 C360,55 1080,55 1440,20 L1440,60 L0,60 Z"></path>
        </svg>
    </div>
</section>

<?php
$totalProducts = (new Model())->query("SELECT COUNT(*) as c FROM products WHERE status='active'")[0]['c'] ?? 0;
$totalCustomers = (new Model())->query("SELECT COUNT(*) as c FROM customers WHERE status='active'")[0]['c'] ?? 0;
$totalOrders = (new Model())->query("SELECT COUNT(*) as c FROM orders")[0]['c'] ?? 0;
?>
<div class="stats-bar">
    <div class="container">
        <div class="row g-3">
            <div class="col-4">
                <div class="stat-item">
                    <div class="stat-number count-up" data-target="<?= $totalProducts ?: 0 ?>"><?= $totalProducts ?: 0 ?></div>
                    <div class="stat-label">Products</div>
                </div>
            </div>
            <div class="col-4">
                <div class="stat-item">
                    <div class="stat-number count-up" data-target="<?= $totalCustomers ?: 0 ?>"><?= $totalCustomers ?: 0 ?></div>
                    <div class="stat-label">Happy Customers</div>
                </div>
            </div>
            <div class="col-4">
                <div class="stat-item">
                    <div class="stat-number count-up" data-target="<?= $totalOrders ?: 0 ?>"><?= $totalOrders ?: 0 ?></div>
                    <div class="stat-label">Orders Completed</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-4" id="featured">
    <?php if (!empty($featuredProducts)): ?>
    <section class="mb-5" data-aos="fade-up">
        <div class="section-header">
            <h3><i class="bi bi-star-fill text-warning me-2"></i>Featured Products</h3>
            <a href="<?= base_url('shop?featured=1') ?>" class="btn btn-outline-primary btn-sm">View All <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-3">
            <?php foreach ($featuredProducts as $p): ?>
                <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up">
                    <?php include __DIR__ . '/partials/product_card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($newArrivals)): ?>
    <section class="mb-5" data-aos="fade-up">
        <div class="section-header">
            <h3><i class="bi bi-clock-history text-info me-2"></i>New Arrivals</h3>
            <a href="<?= base_url('shop?sort=newest') ?>" class="btn btn-outline-primary btn-sm">View All <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-3">
            <?php foreach ($newArrivals as $p): ?>
                <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up">
                    <?php include __DIR__ . '/partials/product_card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($bestSellers)): ?>
    <section class="mb-5" data-aos="fade-up">
        <div class="section-header">
            <h3><i class="bi bi-trophy text-warning me-2"></i>Best Sellers</h3>
            <a href="<?= base_url('shop?sort=popular') ?>" class="btn btn-outline-primary btn-sm">View All <i class="bi bi-arrow-right"></i></a>
        </div>
        <div class="row g-3">
            <?php foreach ($bestSellers as $p): ?>
                <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up">
                    <?php include __DIR__ . '/partials/product_card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>

    <?php if (!empty($categories)): ?>
    <section class="mb-5" data-aos="fade-up">
        <div class="section-header">
            <h3><i class="bi bi-folder me-2 text-primary"></i>Shop by Category</h3>
        </div>
        <div class="row g-3">
            <?php foreach ($categories as $cat): ?>
            <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up">
                <a href="<?= base_url('category/' . $cat['slug']) ?>" class="text-decoration-none">
                    <div class="card text-center h-100 border-0 shadow-sm">
                        <div class="card-body py-4">
                            <div class="fs-1 text-primary mb-2"><i class="bi bi-folder2-open"></i></div>
                            <h6 class="fw-bold mb-1"><?= $cat['name'] ?></h6>
                            <span class="btn btn-sm btn-outline-primary rounded-pill px-3">Browse</span>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    document.querySelectorAll('.count-up').forEach(function(el) {
        var target = parseInt(el.getAttribute('data-target'));
        if (target === 0) return;
        var current = 0;
        var step = Math.ceil(target / 30);
        var timer = setInterval(function() {
            current += step;
            if (current >= target) {
                el.textContent = target;
                clearInterval(timer);
            } else {
                el.textContent = current;
            }
        }, 40);
    });
});
</script>