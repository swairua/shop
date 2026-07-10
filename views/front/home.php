<?php
$heroHeading = App::getSetting('hero_heading', 'Discover Premium Products');
$heroSub = App::getSetting('hero_subheading', 'Shop the latest trends with unbeatable prices.');
$heroBtn = App::getSetting('hero_button_text', 'Shop Now');
$heroBtnUrl = App::getSetting('hero_button_url', 'shop');
$heroBtn2 = App::getSetting('hero_secondary_text', 'Learn More');
$heroBtn2Url = App::getSetting('hero_secondary_url', '#featured');
$heroBgStart = App::getSetting('hero_bg_start', '#0f172a');
$heroBgEnd = App::getSetting('hero_bg_end', '#1e3a5f');
$heroOverlay = App::getSetting('hero_overlay', '0.6');
$heroAnim = App::getSetting('hero_animation', 'particles');
$shopName = App::getSetting('shop_name', 'My Shop');
?>
<style>
.hero-modern {
    position: relative;
    min-height: 92vh;
    display: flex;
    align-items: center;
    overflow: hidden;
    background: linear-gradient(135deg, <?= $heroBgStart ?>, <?= $heroBgEnd ?>);
}
.hero-modern::before {
    content: '';
    position: absolute;
    inset: 0;
    background: radial-gradient(ellipse at 20% 50%, rgba(255,255,255,.05) 0%, transparent 60%),
                radial-gradient(ellipse at 80% 20%, rgba(255,255,255,.03) 0%, transparent 50%);
    pointer-events: none;
}
.hero-overlay {
    position: absolute;
    inset: 0;
    background: rgba(0,0,0,<?= $heroOverlay ?>);
    pointer-events: none;
}
.hero-particle {
    position: absolute;
    border-radius: 50%;
    background: rgba(255,255,255,.08);
    pointer-events: none;
    animation: floatParticle 20s infinite ease-in-out;
}
@keyframes floatParticle {
    0%,100% { transform: translateY(0) scale(1); opacity: .3; }
    25% { transform: translateY(-30px) scale(1.2); opacity: .6; }
    50% { transform: translateY(-60px) scale(.8); opacity: .2; }
    75% { transform: translateY(-20px) scale(1.1); opacity: .5; }
}
.hero-floating-shape {
    position: absolute;
    border: 2px solid rgba(255,255,255,.06);
    pointer-events: none;
    animation: morphShape 25s infinite ease-in-out;
}
@keyframes morphShape {
    0%,100% { border-radius: 60% 40% 30% 70% / 60% 30% 70% 40%; transform: rotate(0deg) scale(1); }
    50% { border-radius: 30% 60% 70% 40% / 50% 60% 30% 60%; transform: rotate(180deg) scale(1.1); }
}
.hero-title-anim {
    opacity: 0;
    animation: heroFadeUp .8s .2s forwards;
}
.hero-sub-anim {
    opacity: 0;
    animation: heroFadeUp .8s .5s forwards;
}
.hero-cta-anim {
    opacity: 0;
    animation: heroFadeUp .8s .8s forwards;
}
.hero-search-anim {
    opacity: 0;
    animation: heroFadeUp .8s 1.1s forwards;
}
@keyframes heroFadeUp {
    from { opacity: 0; transform: translateY(30px); }
    to { opacity: 1; transform: translateY(0); }
}
.hero-title-type {
    display: inline;
    background: linear-gradient(135deg, #fff 30%, <?= App::getSetting('primary_color', '#2563eb') ?> 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
}
.hero-brand-pill {
    display: inline-flex;
    align-items: center;
    gap: .5rem;
    padding: .35rem 1rem;
    border-radius: 50px;
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.08);
    color: rgba(255,255,255,.7);
    font-size: .8rem;
    font-weight: 600;
    backdrop-filter: blur(4px);
    transition: background .3s, transform .3s;
}
.hero-brand-pill:hover {
    background: rgba(255,255,255,.12);
    transform: translateY(-2px);
    color: #fff;
}
.hero-brand-pill i { font-size: 1rem; }
.hero-slider-card {
    background: rgba(255,255,255,.06);
    border: 1px solid rgba(255,255,255,.1);
    border-radius: 1.25rem;
    overflow: hidden;
    backdrop-filter: blur(12px);
    position: relative;
    transition: transform .3s, box-shadow .3s;
}
.hero-slider-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 12px 40px rgba(0,0,0,.25);
}
.hero-slider-img-link {
    display: block;
    width: 100%;
    aspect-ratio: 4/3;
    overflow: hidden;
    background: rgba(0,0,0,.2);
}
.hero-slider-img-link img {
    width: 100%;
    height: 100%;
    object-fit: cover;
    transition: transform .5s;
}
.hero-slider-card:hover .hero-slider-img-link img {
    transform: scale(1.08);
}
.hero-slider-badge {
    position: absolute;
    top: .75rem;
    left: .75rem;
    background: #ef4444;
    color: #fff;
    font-size: .7rem;
    font-weight: 700;
    padding: .2rem .6rem;
    border-radius: 50px;
    z-index: 2;
}
.hero-slider-body {
    padding: 1rem 1.1rem 1.1rem;
}
.hero-slider-body h6 {
    color: #fff;
    font-size: .9rem;
    font-weight: 700;
    margin-bottom: .35rem;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}
.hero-slider-rating {
    display: flex;
    align-items: center;
    gap: .1rem;
    margin-bottom: .4rem;
    font-size: .75rem;
}
.hero-slider-rating i { color: #f59e0b; }
.hero-slider-rating i.bi-star { color: rgba(255,255,255,.25); }
.hero-slider-rating span { color: rgba(255,255,255,.4); font-size: .7rem; margin-left: .25rem; }
.hero-slider-price {
    margin-bottom: .35rem;
    display: flex;
    align-items: center;
    gap: .5rem;
}
.hero-slider-price .current {
    font-size: 1.1rem;
    font-weight: 800;
    color: #fff;
}
.hero-slider-price .old {
    font-size: .8rem;
    color: rgba(255,255,255,.4);
    text-decoration: line-through;
}
.hero-slider-stock {
    display: flex;
    align-items: center;
    gap: .35rem;
    margin-bottom: .65rem;
    font-size: .75rem;
    color: rgba(255,255,255,.6);
}
.hero-slider-stock .dot {
    width: 7px;
    height: 7px;
    border-radius: 50%;
    display: inline-block;
}
.hero-slider-stock .dot.in-stock { background: #10b981; }
.hero-slider-stock .dot.low-stock { background: #f59e0b; }
.hero-slider-stock .dot.out-of-stock { background: #ef4444; }
.hero-slider-actions {
    display: flex;
    gap: .5rem;
}
.hero-slider-actions .btn {
    flex: 1;
    font-size: .78rem;
    border-radius: 50px;
    padding: .35rem .5rem;
}
.hero-slider-actions .btn-primary {
    background: <?= App::getSetting('primary_color', '#2563eb') ?>;
    border: none;
}
.hero-slider-actions .btn-outline-light {
    border-color: rgba(255,255,255,.2);
}
.hero-slider-ctrl {
    width: 32px;
    height: 32px;
    top: 50%;
    transform: translateY(-50%);
    background: rgba(255,255,255,.1);
    border-radius: 50%;
    opacity: 0;
    transition: opacity .3s;
}
#heroProductCarousel:hover .hero-slider-ctrl { opacity: 1; }
.hero-slider-ctrl .carousel-control-prev-icon,
.hero-slider-ctrl .carousel-control-next-icon {
    width: 16px;
    height: 16px;
}
.carousel-indicators { margin-bottom: .25rem; }
.carousel-indicators button {
    width: 8px;
    height: 8px;
    border-radius: 50%;
    background: rgba(255,255,255,.3);
    border: none;
}
.carousel-indicators button.active { background: <?= App::getSetting('primary_color', '#2563eb') ?>; }
.hero-slider-fallback .hero-slider-body { padding: 2rem 1.5rem; }
.hero-slider-fallback .hero-slider-body h6 { font-size: 1.1rem; white-space: normal; }
.hero-wave-bottom {
    position: absolute;
    bottom: -1px;
    left: 0;
    width: 100%;
    line-height: 0;
    pointer-events: none;
}
.hero-wave-bottom svg {
    display: block;
    width: 100%;
    height: 60px;
}
.category-hero-card {
    border: none;
    border-radius: 1rem;
    overflow: hidden;
    background: rgba(255,255,255,.05);
    backdrop-filter: blur(8px);
    border: 1px solid rgba(255,255,255,.08);
    transition: transform .3s, background .3s;
}
.category-hero-card:hover {
    transform: translateY(-4px);
    background: rgba(255,255,255,.1);
}
.category-hero-card .card-body {
    padding: 1.25rem;
    text-align: center;
}
.category-hero-card i {
    font-size: 1.75rem;
    color: <?= App::getSetting('primary_color', '#2563eb') ?>;
    opacity: .8;
}
.category-hero-card h6 {
    color: #fff;
    font-size: .85rem;
    margin-top: .5rem;
    margin-bottom: 0;
}
</style>

<section class="hero-modern" id="hero">
    <div class="hero-overlay"></div>

    <?php if ($heroAnim === 'particles'): ?>
    <div class="hero-particle" style="width:300px;height:300px;top:-5%;right:-5%;animation-delay:0s;"></div>
    <div class="hero-particle" style="width:200px;height:200px;bottom:10%;left:-3%;animation-delay:-5s;"></div>
    <div class="hero-particle" style="width:150px;height:150px;top:30%;right:15%;animation-delay:-10s;"></div>
    <div class="hero-particle" style="width:250px;height:250px;bottom:-8%;right:25%;animation-delay:-15s;"></div>
    <?php elseif ($heroAnim === 'floating'): ?>
    <div class="hero-floating-shape" style="width:250px;height:250px;top:-8%;right:-5%;"></div>
    <div class="hero-floating-shape" style="width:180px;height:180px;bottom:5%;left:-4%;animation-delay:-8s;"></div>
    <div class="hero-floating-shape" style="width:120px;height:120px;top:40%;right:10%;animation-delay:-16s;"></div>
    <?php endif; ?>

    <div class="container position-relative" style="z-index:2;">
        <div class="row align-items-center">
            <div class="col-lg-7">
                <div class="hero-title-anim">
                    <span class="badge bg-white bg-opacity-10 text-white border border-white border-opacity-20 rounded-pill px-3 py-2 mb-3 fs-6 fw-normal">
                        <i class="bi bi-lightning-fill text-warning me-1"></i> <?= $shopName ?>
                    </span>
                    <h1 class="display-3 fw-bold text-white mb-3 lh-1">
                        <span class="hero-title-type"><?= $heroHeading ?></span>
                    </h1>
                </div>
                <div class="hero-sub-anim">
                    <p class="lead text-white text-opacity-75 mb-4" style="max-width:540px;font-size:1.15rem;">
                        <?= $heroSub ?>
                    </p>
                </div>
                <div class="hero-cta-anim">
                    <div class="d-flex flex-wrap gap-3 mb-4">
                        <a href="<?= base_url($heroBtnUrl) ?>" class="btn btn-lg text-white fw-semibold px-4 py-3 rounded-pill shadow-lg" style="background:<?= App::getSetting('primary_color', '#2563eb') ?>;border:none;">
                            <i class="bi bi-bag-check me-2"></i><?= $heroBtn ?>
                        </a>
                        <a href="<?= base_url($heroBtn2Url) ?>" class="btn btn-lg btn-outline-light fw-semibold px-4 py-3 rounded-pill border-opacity-25">
                            <i class="bi bi-play-circle me-2"></i><?= $heroBtn2 ?>
                        </a>
                    </div>
                </div>
                <div class="hero-search-anim">
                    <form action="<?= base_url('shop') ?>" method="GET" class="mb-3" style="max-width:480px;">
                        <div class="input-group input-group-lg">
                            <span class="input-group-text bg-white bg-opacity-10 text-white border-0"><i class="bi bi-search"></i></span>
                            <input type="text" name="q" class="form-control bg-white bg-opacity-10 text-white border-0" placeholder="Search products..." style="backdrop-filter:blur(4px);">
                            <button class="btn text-white fw-semibold px-4 border-0" style="background:<?= App::getSetting('primary_color', '#2563eb') ?>;" type="submit"><i class="bi bi-arrow-right"></i></button>
                        </div>
                    </form>
                    <div class="d-flex flex-wrap gap-2 align-items-center">
                        <span class="text-white text-opacity-50 small me-2">Trusted by:</span>
                        <span class="hero-brand-pill"><i class="bi bi-shield-check text-success"></i> Secure</span>
                        <span class="hero-brand-pill"><i class="bi bi-truck text-info"></i> Fast Delivery</span>
                        <span class="hero-brand-pill"><i class="bi bi-arrow-repeat text-warning"></i> Easy Returns</span>
                    </div>
                </div>
            </div>
            <div class="col-lg-5 d-none d-lg-block">
                <div class="hero-cta-anim" style="animation-delay:1.3s;">
                    <?php if (!empty($featuredProducts)): ?>
                    <div id="heroProductCarousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="4000" data-bs-pause="hover">
                        <div class="carousel-indicators">
                            <?php foreach ($featuredProducts as $i => $p): ?>
                            <button type="button" data-bs-target="#heroProductCarousel" data-bs-slide-to="<?= $i ?>" class="<?= $i === 0 ? 'active' : '' ?>" aria-label="Slide <?= $i + 1 ?>"></button>
                            <?php endforeach; ?>
                        </div>
                        <div class="carousel-inner">
                            <?php foreach ($featuredProducts as $i => $p): ?>
                            <div class="carousel-item <?= $i === 0 ? 'active' : '' ?>">
                                <div class="hero-slider-card">
                                    <a href="<?= base_url('product/' . $p['slug']) ?>" class="hero-slider-img-link">
                                        <img src="<?= product_image($p['featured_image'] ?? '', '400x400', $p['name']) ?>" alt="<?= $p['name'] ?>" loading="lazy">
                                    </a>
                                    <?php if (isset($p['discount_price']) && $p['discount_price'] && $p['selling_price'] > 0): ?>
                                    <span class="hero-slider-badge">-<?= round((1 - $p['discount_price'] / $p['selling_price']) * 100) ?>%</span>
                                    <?php endif; ?>
                                    <div class="hero-slider-body">
                                        <h6><?= $p['name'] ?></h6>
                                        <?php if (isset($p['rating']) && $p['rating'] > 0): ?>
                                        <div class="hero-slider-rating">
                                            <?php for ($r = 1; $r <= 5; $r++): ?>
                                            <i class="bi bi-star<?= $r <= round($p['rating']) ? '-fill' : '' ?>"></i>
                                            <?php endfor; ?>
                                            <?php if (isset($p['rating_count'])): ?><span>(<?= $p['rating_count'] ?>)</span><?php endif; ?>
                                        </div>
                                        <?php endif; ?>
                                        <div class="hero-slider-price">
                                            <?php if (isset($p['discount_price']) && $p['discount_price']): ?>
                                            <span class="current"><?= format_price($p['discount_price']) ?></span>
                                            <span class="old"><?= format_price($p['selling_price']) ?></span>
                                            <?php else: ?>
                                            <span class="current"><?= format_price($p['selling_price']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                        <?php if (isset($p['quantity'])): ?>
                                        <div class="hero-slider-stock">
                                            <span class="dot <?= $p['quantity'] > 10 ? 'in-stock' : ($p['quantity'] > 0 ? 'low-stock' : 'out-of-stock') ?>"></span>
                                            <span><?= $p['quantity'] > 0 ? ($p['quantity'] > 10 ? 'In Stock' : 'Only ' . $p['quantity'] . ' left') : 'Out of Stock' ?></span>
                                        </div>
                                        <?php endif; ?>
                                        <div class="hero-slider-actions">
                                            <button class="btn btn-sm btn-primary add-to-cart" data-product-id="<?= $p['id'] ?>"><i class="bi bi-cart-plus"></i> Add</button>
                                            <a href="<?= base_url('product/' . $p['slug']) ?>" class="btn btn-sm btn-outline-light"><i class="bi bi-eye"></i> View</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                        <button class="carousel-control-prev hero-slider-ctrl" type="button" data-bs-target="#heroProductCarousel" data-bs-slide="prev">
                            <span class="carousel-control-prev-icon"></span>
                        </button>
                        <button class="carousel-control-next hero-slider-ctrl" type="button" data-bs-target="#heroProductCarousel" data-bs-slide="next">
                            <span class="carousel-control-next-icon"></span>
                        </button>
                    </div>
                    <?php else: ?>
                    <div class="hero-slider-card hero-slider-fallback">
                        <div class="hero-slider-body text-center py-5">
                            <div style="font-size:3rem;margin-bottom:1rem;opacity:.3;"><i class="bi bi-bag-check-fill"></i></div>
                            <h6>Discover Our Collection</h6>
                            <p class="small text-white text-opacity-50 mb-3">Explore amazing products at great prices</p>
                            <a href="<?= base_url('shop') ?>" class="btn btn-sm btn-primary"><i class="bi bi-arrow-right"></i> Shop Now</a>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <?php if (!empty($categories)): ?>
        <div class="row g-2 mt-5 hero-search-anim" style="animation-delay:1.6s;">
            <div class="col-12"><p class="text-white text-opacity-50 small mb-2 fw-semibold"><i class="bi bi-grid"></i> SHOP BY CATEGORY</p></div>
            <?php $catIcons = ['phone','laptop','tshirt','gem','house-door','watch','headphones','camera']; ?>
            <?php foreach (array_slice($categories, 0, 6) as $i => $cat): ?>
            <div class="col-4 col-md-2">
                <a href="<?= base_url('category/' . $cat['slug']) ?>" class="text-decoration-none">
                    <div class="category-hero-card">
                        <div class="card-body">
                            <i class="bi bi-<?= $catIcons[$i % count($catIcons)] ?>"></i>
                            <h6><?= $cat['name'] ?></h6>
                        </div>
                    </div>
                </a>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <div class="hero-wave-bottom">
        <svg viewBox="0 0 1440 60" preserveAspectRatio="none" fill="#f8fafc">
            <path d="M0,20 C360,55 1080,55 1440,20 L1440,60 L0,60 Z"></path>
        </svg>
    </div>
</section>

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
</div>

