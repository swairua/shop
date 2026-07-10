<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= isset($metaTitle) ? $metaTitle : App::getSetting('meta_title', 'My Shop') ?></title>
    <meta name="description" content="<?= isset($metaDescription) ? $metaDescription : App::getSetting('meta_description', '') ?>">
    <meta property="og:title" content="<?= isset($ogTitle) ? $ogTitle : App::getSetting('og_title', '') ?>">
    <meta property="og:description" content="<?= isset($ogDescription) ? $ogDescription : App::getSetting('og_description', '') ?>">
    <meta property="og:image" content="<?= App::getSetting('og_image') ? base_url('uploads/settings/' . App::getSetting('og_image')) : '' ?>">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.css" rel="stylesheet">
    <link href="<?= base_url('assets/css/style.css') ?>" rel="stylesheet">
    <link href="<?= base_url('assets/css/animations.css') ?>" rel="stylesheet">
    <?php if (isset($extraCss)) echo $extraCss; ?>
    <style>
        :root {
            --primary: <?= App::getSetting('primary_color', '#2563eb') ?>;
            --primary-hover: <?= App::getSetting('primary_color', '#2563eb') ?>dd;
            --secondary: <?= App::getSetting('secondary_color', '#7c3aed') ?>;
            --accent: <?= App::getSetting('accent_color', '#f59e0b') ?>;
            --header-bg: <?= App::getSetting('header_bg', '#0f172a') ?>;
            --footer-bg: <?= App::getSetting('footer_bg', '#0f172a') ?>;
            --font-family: <?= App::getSetting('font_family', "'Inter', sans-serif") ?>;
            --radius: <?= App::getSetting('border_radius', '0.5rem') ?>;
        }
    </style>
    <script>var baseUrl = '<?= base_url('') ?>';</script>
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark navbar-main sticky-top" id="mainNav">
        <div class="container">
            <a class="navbar-brand fw-bold" href="<?= base_url('') ?>">
                <?php if (App::getSetting('shop_logo')): ?>
                    <img src="<?= base_url('uploads/settings/' . App::getSetting('shop_logo')) ?>" height="32" alt="Logo" id="navLogo">
                <?php else: ?>
                    <i class="bi bi-shop"></i> <?= App::getSetting('shop_name', 'My Shop') ?>
                <?php endif; ?>
            </a>

            <div class="d-flex align-items-center gap-2 d-lg-none">
                <button class="btn btn-link text-white p-1 position-relative" type="button" onclick="location.href='<?= base_url('cart') ?>'">
                    <i class="bi bi-cart fs-5"></i>
                    <span class="badge-count" id="cartCountMobile">0</span>
                </button>
                <button class="navbar-toggler" type="button" data-bs-toggle="offcanvas" data-bs-target="#mobileNav">
                    <span class="navbar-toggler-icon"></span>
                </button>
            </div>

            <div class="collapse navbar-collapse d-none d-lg-flex" id="navbarMain">
                <div class="search-bar mx-auto">
                    <div class="search-toggle" onclick="toggleSearch()">
                        <i class="bi bi-search"></i>
                        <span>Search products...</span>
                    </div>
                    <div class="search-form-expanded" id="searchExpanded">
                        <form class="d-flex w-100" action="<?= base_url('shop') ?>" method="GET">
                            <div class="input-group">
                                <input class="form-control" type="search" name="q" placeholder="Search products..." id="searchInput" autocomplete="off">
                                <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                                <button class="btn btn-outline-light" type="button" onclick="toggleSearch()"><i class="bi bi-x"></i></button>
                            </div>
                        </form>
                        <div class="search-results dropdown-menu show" id="searchResults"></div>
                    </div>
                </div>

                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item">
                        <a class="nav-link" href="<?= base_url('compare') ?>"><i class="bi bi-arrow-left-right"></i><span class="d-none d-xl-inline ms-1">Compare</span></a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="<?= base_url('wishlist') ?>">
                            <i class="bi bi-heart"></i><span class="d-none d-xl-inline ms-1">Wishlist</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link position-relative" href="<?= base_url('cart') ?>">
                            <i class="bi bi-cart"></i><span class="d-none d-xl-inline ms-1">Cart</span>
                            <span class="badge-count" id="cartCount">0</span>
                        </a>
                    </li>
                    <?php if (is_customer()): ?>
                        <li class="nav-item dropdown ms-2">
                            <a class="nav-link dropdown-toggle d-flex align-items-center gap-1" href="#" data-bs-toggle="dropdown">
                                <i class="bi bi-person-circle fs-5"></i>
                                <span class="d-none d-xl-inline"><?= $_SESSION['customer_name'] ?></span>
                            </a>
                            <ul class="dropdown-menu dropdown-menu-end shadow-sm border-0">
                                <li><a class="dropdown-item" href="<?= base_url('account') ?>"><i class="bi bi-person"></i> My Account</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('orders') ?>"><i class="bi bi-box"></i> My Orders</a></li>
                                <li><a class="dropdown-item" href="<?= base_url('wishlist') ?>"><i class="bi bi-heart"></i> Wishlist</a></li>
                                <li><hr class="dropdown-divider"></li>
                                <li><a class="dropdown-item text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                            </ul>
                        </li>
                    <?php else: ?>
                        <li class="nav-item ms-2">
                            <a class="nav-link" href="<?= base_url('login') ?>"><i class="bi bi-box-arrow-in-right"></i><span class="d-none d-xl-inline ms-1"> Login</span></a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="<?= base_url('register') ?>"><i class="bi bi-person-plus"></i><span class="d-none d-xl-inline ms-1"> Register</span></a>
                        </li>
                    <?php endif; ?>
                </ul>
            </div>
        </div>
    </nav>

    <div class="offcanvas offcanvas-end d-lg-none" tabindex="-1" id="mobileNav">
        <div class="offcanvas-header border-bottom">
            <h5 class="mb-0"><?= App::getSetting('shop_name', 'Menu') ?></h5>
            <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body p-0">
            <div class="p-3 border-bottom">
                <form action="<?= base_url('shop') ?>" method="GET">
                    <div class="input-group">
                        <input class="form-control" type="search" name="q" placeholder="Search...">
                        <button class="btn btn-primary" type="submit"><i class="bi bi-search"></i></button>
                    </div>
                </form>
            </div>
            <ul class="list-unstyled mb-0">
                <li><a class="nav-link" href="<?= base_url('') ?>"><i class="bi bi-house"></i> Home</a></li>
                <li><a class="nav-link" href="<?= base_url('shop') ?>"><i class="bi bi-grid"></i> Shop</a></li>
                <li><a class="nav-link" href="<?= base_url('compare') ?>"><i class="bi bi-arrow-left-right"></i> Compare</a></li>
                <li><a class="nav-link" href="<?= base_url('wishlist') ?>"><i class="bi bi-heart"></i> Wishlist</a></li>
                <li><a class="nav-link" href="<?= base_url('cart') ?>"><i class="bi bi-cart"></i> Cart <span class="badge bg-danger rounded-pill float-end" id="cartCountOffcanvas">0</span></a></li>
                <?php if (is_customer()): ?>
                    <li><hr class="my-1"></li>
                    <li><a class="nav-link" href="<?= base_url('account') ?>"><i class="bi bi-person"></i> My Account</a></li>
                    <li><a class="nav-link" href="<?= base_url('orders') ?>"><i class="bi bi-box"></i> My Orders</a></li>
                    <li><a class="nav-link text-danger" href="<?= base_url('logout') ?>"><i class="bi bi-box-arrow-right"></i> Logout</a></li>
                <?php else: ?>
                    <li><hr class="my-1"></li>
                    <li><a class="nav-link" href="<?= base_url('login') ?>"><i class="bi bi-box-arrow-in-right"></i> Login</a></li>
                    <li><a class="nav-link" href="<?= base_url('register') ?>"><i class="bi bi-person-plus"></i> Register</a></li>
                <?php endif; ?>
            </ul>
        </div>
    </div>

    <?php if (has_flash('success')): ?>
        <div class="container mt-2">
            <div class="alert alert-success alert-dismissible fade show"><?= session_flash('success') ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
        </div>
    <?php endif; ?>
    <?php if (has_flash('error')): ?>
        <div class="container mt-2">
            <div class="alert alert-danger alert-dismissible fade show"><?= session_flash('error') ?><button class="btn-close" data-bs-dismiss="alert"></button></div>
        </div>
    <?php endif; ?>

    <main>
        <?php if (isset($content) && is_callable($content)) $content(); ?>
    </main>

    <button class="back-to-top" id="backToTop" onclick="window.scrollTo({top:0,behavior:'smooth'})">
        <i class="bi bi-chevron-up"></i>
    </button>

    <?php $waNum = App::getSetting('whatsapp_number', ''); ?>
    <?php if ($waNum): ?>
    <a href="<?= wa_link() ?>" class="whatsapp-float" target="_blank" rel="noopener" title="Chat on WhatsApp">
        <i class="bi bi-whatsapp"></i>
    </a>
    <?php endif; ?>

    <footer class="site-footer">
        <div class="container">
            <div class="row g-4">
                <div class="col-md-4">
                    <h5><i class="bi bi-shop"></i> <?= App::getSetting('shop_name', 'My Shop') ?></h5>
                    <p class="small"><i class="bi bi-geo-alt"></i> <?= App::getSetting('shop_address', '') ?></p>
                    <p class="small mb-1"><i class="bi bi-envelope"></i> <?= App::getSetting('shop_email', '') ?></p>
                    <p class="small"><i class="bi bi-telephone"></i> <?= App::getSetting('shop_phone', '') ?></p>
                </div>
                <div class="col-md-2">
                    <h5>Quick Links</h5>
                    <ul>
                        <li><a href="<?= base_url('') ?>"><i class="bi bi-chevron-right"></i> Home</a></li>
                        <li><a href="<?= base_url('shop') ?>"><i class="bi bi-chevron-right"></i> Shop</a></li>
                        <li><a href="<?= base_url('cart') ?>"><i class="bi bi-chevron-right"></i> Cart</a></li>
                        <li><a href="<?= base_url('wishlist') ?>"><i class="bi bi-chevron-right"></i> Wishlist</a></li>
                        <li><a href="<?= base_url('compare') ?>"><i class="bi bi-chevron-right"></i> Compare</a></li>
                    </ul>
                </div>
                <div class="col-md-2">
                    <h5>Customer Service</h5>
                    <ul>
                        <li><a href="<?= base_url('contact') ?>"><i class="bi bi-chevron-right"></i> Contact Us</a></li>
                        <li><a href="<?= base_url('account') ?>"><i class="bi bi-chevron-right"></i> My Account</a></li>
                        <li><a href="<?= base_url('account') ?>"><i class="bi bi-chevron-right"></i> Track Order</a></li>
                        <li><a href="<?= base_url('page/shipping-info') ?>"><i class="bi bi-chevron-right"></i> Shipping Info</a></li>
                        <li><a href="<?= base_url('page/returns') ?>"><i class="bi bi-chevron-right"></i> Returns</a></li>
                    </ul>
                </div>
                <div class="col-md-4">
                    <h5>Stay Connected</h5>
                    <p class="small">Subscribe to our newsletter for exclusive offers.</p>
                    <form class="newsletter-form mb-3" id="newsletterForm">
                        <div class="input-group">
                            <input type="email" id="newsletterEmail" class="form-control" placeholder="Your email address" required>
                            <button class="btn btn-primary" type="submit">Subscribe</button>
                        </div>
                        <small class="newsletter-msg mt-1 d-block"></small>
                    </form>
                    <div class="social-links">
                        <?php if (App::getSetting('facebook_url')): ?><a href="<?= App::getSetting('facebook_url') ?>" class="social-link facebook" target="_blank" rel="noopener"><i class="bi bi-facebook"></i></a><?php endif; ?>
                        <?php if (App::getSetting('twitter_url')): ?><a href="<?= App::getSetting('twitter_url') ?>" class="social-link twitter" target="_blank" rel="noopener"><i class="bi bi-twitter-x"></i></a><?php endif; ?>
                        <?php if (App::getSetting('instagram_url')): ?><a href="<?= App::getSetting('instagram_url') ?>" class="social-link instagram" target="_blank" rel="noopener"><i class="bi bi-instagram"></i></a><?php endif; ?>
                        <?php if (App::getSetting('youtube_url')): ?><a href="<?= App::getSetting('youtube_url') ?>" class="social-link youtube" target="_blank" rel="noopener"><i class="bi bi-youtube"></i></a><?php endif; ?>
                        <?php if (App::getSetting('tiktok_url')): ?><a href="<?= App::getSetting('tiktok_url') ?>" class="social-link tiktok" target="_blank" rel="noopener"><i class="bi bi-tiktok"></i></a><?php endif; ?>
                        <?php if (App::getSetting('linkedin_url')): ?><a href="<?= App::getSetting('linkedin_url') ?>" class="social-link linkedin" target="_blank" rel="noopener"><i class="bi bi-linkedin"></i></a><?php endif; ?>
                        <?php if (App::getSetting('pinterest_url')): ?><a href="<?= App::getSetting('pinterest_url') ?>" class="social-link pinterest" target="_blank" rel="noopener"><i class="bi bi-pinterest"></i></a><?php endif; ?>
                        <?php if ($waNum): ?><a href="<?= wa_link() ?>" class="social-link whatsapp" target="_blank" rel="noopener"><i class="bi bi-whatsapp"></i></a><?php endif; ?>
                    </div>
                </div>
            </div>

            <div class="trust-badges">
                <span class="trust-badge"><i class="bi bi-shield-check"></i> Secure SSL Checkout</span>
                <span class="trust-badge"><i class="bi bi-arrow-repeat"></i> Free Returns</span>
                <span class="trust-badge"><i class="bi bi-truck"></i> Fast Delivery</span>
                <span class="trust-badge"><i class="bi bi-headset"></i> 24/7 Support</span>
            </div>

            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start mb-2 mb-md-0">
                        &copy; <?= date('Y') ?> <?= App::getSetting('shop_name', 'My Shop') ?>. All rights reserved.
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <div class="payment-icons justify-content-center justify-content-md-end">
                            <img src="https://cdn.simpleicons.org/visa" alt="Visa" height="24" title="Visa">
                            <img src="https://cdn.simpleicons.org/mastercard" alt="Mastercard" height="24" title="Mastercard">
                            <span style="display:inline-flex;align-items:center;gap:4px;opacity:.6;" title="M-Pesa">
                                <i class="bi bi-phone" style="font-size:1.1rem;"></i>
                                <span style="font-size:.65rem;font-weight:700;letter-spacing:-.5px;">M-PESA</span>
                            </span>
                            <img src="https://cdn.simpleicons.org/paypal" alt="PayPal" height="24" title="PayPal">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    <div class="toast-container" id="toastContainer"></div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/nouislider@15.7.1/dist/nouislider.min.js"></script>
    <script src="<?= base_url('assets/js/app.js') ?>"></script>
    <script>
document.getElementById('newsletterForm')?.addEventListener('submit', function(e) {
    e.preventDefault();
    var input = document.getElementById('newsletterEmail');
    var msg = this.querySelector('.newsletter-msg');
    msg.textContent = '';
    fetch('<?= base_url('api/subscribe') ?>', {
        method: 'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: 'email=' + encodeURIComponent(input.value)
    }).then(function(r) { return r.json(); }).then(function(d) {
        msg.style.color = d.success ? '#10b981' : '#ef4444';
        msg.textContent = d.message;
        if (d.success) input.value = '';
    }).catch(function() {
        msg.style.color = '#ef4444';
        msg.textContent = 'Something went wrong.';
    });
});
    </script>
    <?php if (isset($extraJs)) echo $extraJs; ?>
</body>
</html>