<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('shop') ?>">Shop</a></li>
            <li class="breadcrumb-item active"><?= $product['name'] ?></li>
        </ol>
    </nav>

    <div class="row g-4">
        <div class="col-md-6">
            <div class="product-gallery">
                <div class="main-image" id="mainImageWrap">
                    <img src="<?= product_image($product['featured_image'] ?? '', '600x600', $product['name']) ?>" id="mainImage" alt="<?= $product['name'] ?>">
                    <div class="zoom-lens" id="zoomLens"></div>
                </div>
                <?php if (!empty($images)): ?>
                <div class="thumb-list" id="thumbList">
                    <div class="thumb-item active" data-src="<?= product_image($product['featured_image'] ?? '', '600x600', $product['name']) ?>">
                        <img src="<?= product_image($product['featured_image'] ?? '', '600x600', $product['name']) ?>" alt="">
                    </div>
                    <?php foreach ($images as $img): ?>
                    <div class="thumb-item" data-src="<?= base_url('uploads/products/' . $img['image']) ?>">
                        <img src="<?= base_url('uploads/products/' . $img['image']) ?>" alt="">
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <div class="col-md-6 product-info">
            <h1 class="product-title"><?= $product['name'] ?></h1>

            <?php if ($rating['total'] > 0): ?>
            <div class="d-flex align-items-center mb-2">
                <div class="rating-stars me-2" style="color:var(--accent);font-size:1rem;">
                    <?php for ($i = 1; $i <= 5; $i++): ?>
                        <i class="bi bi-star<?= $i <= round($rating['average']) ? '-fill' : '' ?>"></i>
                    <?php endfor; ?>
                </div>
                <span class="small text-muted">(<?= $rating['total'] ?> review<?= $rating['total'] !== 1 ? 's' : '' ?>)</span>
            </div>
            <?php endif; ?>

            <div class="mb-3">
                <?php if ($product['discount_price']): ?>
                    <span class="product-price text-danger"><?= format_price($product['discount_price']) ?></span>
                    <span class="product-price-old ms-2"><?= format_price($product['selling_price']) ?></span>
                    <span class="badge bg-danger ms-2">-<?= round((1 - $product['discount_price'] / $product['selling_price']) * 100) ?>% OFF</span>
                <?php else: ?>
                    <span class="product-price"><?= format_price($product['selling_price']) ?></span>
                <?php endif; ?>
            </div>

            <p class="text-secondary"><?= nl2br($product['short_description'] ?? '') ?></p>

            <div class="mb-3 d-flex gap-2 flex-wrap">
                <?php if ($product['quantity'] > 0): ?>
                    <span class="badge bg-success py-2 px-3"><i class="bi bi-check-circle me-1"></i> In Stock (<?= $product['quantity'] ?> available)</span>
                <?php else: ?>
                    <span class="badge bg-danger py-2 px-3"><i class="bi bi-x-circle me-1"></i> Out of Stock</span>
                <?php endif; ?>
                <?php if ($product['sku']): ?><span class="badge bg-secondary py-2 px-3">SKU: <?= $product['sku'] ?></span><?php endif; ?>
            </div>

            <div class="d-flex align-items-center gap-3 mb-3">
                <div class="qty-stepper">
                    <button onclick="updateQty(-1)"><i class="bi bi-dash"></i></button>
                    <input type="number" id="qty" value="1" min="1" max="<?= $product['quantity'] ?: 1 ?>">
                    <button onclick="updateQty(1)"><i class="bi bi-plus"></i></button>
                </div>
                <button class="btn btn-primary btn-lg flex-grow-1 add-to-cart" data-product-id="<?= $product['id'] ?>" style="border-radius:50px;">
                    <i class="bi bi-cart-plus"></i> Add to Cart
                </button>
            </div>

            <div class="d-flex gap-2 mb-3">
                <button class="btn btn-outline-danger add-to-wishlist <?= $inWishlist ? 'active' : '' ?>" data-product-id="<?= $product['id'] ?>"><i class="bi bi-heart<?= $inWishlist ? '-fill' : '' ?>"></i> Wishlist</button>
                <button class="btn btn-outline-secondary add-to-compare" data-product-id="<?= $product['id'] ?>"><i class="bi bi-arrow-left-right"></i> Compare</button>
            </div>

            <div class="d-flex gap-2 mb-3">
                <span class="text-muted small">Share:</span>
                <a href="https://www.facebook.com/sharer/sharer.php?u=<?= urlencode(base_url('product/' . $product['slug'])) ?>" target="_blank" class="text-muted"><i class="bi bi-facebook"></i></a>
                <a href="https://twitter.com/intent/tweet?url=<?= urlencode(base_url('product/' . $product['slug'])) ?>&text=<?= urlencode($product['name']) ?>" target="_blank" class="text-muted"><i class="bi bi-twitter-x"></i></a>
                <a href="https://wa.me/?text=<?= urlencode($product['name'] . ' - ' . base_url('product/' . $product['slug'])) ?>" target="_blank" class="text-muted"><i class="bi bi-whatsapp"></i></a>
                <a href="mailto:?subject=<?= urlencode($product['name']) ?>&body=<?= urlencode(base_url('product/' . $product['slug'])) ?>" class="text-muted"><i class="bi bi-envelope"></i></a>
                <button class="btn btn-sm btn-link text-muted p-0" onclick="navigator.clipboard.writeText('<?= base_url('product/' . $product['slug']) ?>').then(function(){showToast('Link copied!','success')})"><i class="bi bi-link-45deg"></i></button>
            </div>

            <hr>
            <div class="row text-muted small">
                <?php if ($product['category_name']): ?>
                <div class="col-6 mb-2">Category: <strong class="text-dark"><?= $product['category_name'] ?></strong></div>
                <?php endif; ?>
                <?php if ($product['brand_name']): ?>
                <div class="col-6 mb-2">Brand: <strong class="text-dark"><?= $product['brand_name'] ?></strong></div>
                <?php endif; ?>
                <?php if ($product['weight']): ?>
                <div class="col-6 mb-2">Weight: <strong class="text-dark"><?= $product['weight'] ?> kg</strong></div>
                <?php endif; ?>
                <?php if ($product['type']): ?>
                <div class="col-6 mb-2">Type: <strong class="text-dark"><?= ucfirst($product['type']) ?></strong></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="row mt-4">
        <div class="col-12">
            <ul class="nav nav-tabs" id="productTabs">
                <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#description">Description</a></li>
                <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#reviews">Reviews (<?= $rating['total'] ?>)</a></li>
            </ul>
            <div class="tab-content p-3 border border-top-0 rounded-bottom">
                <div class="tab-pane fade show active" id="description">
                    <?= nl2br($product['description'] ?? '') ?>
                </div>
                <div class="tab-pane fade" id="reviews">
                    <?php if (!empty($reviews)): ?>
                        <?php foreach ($reviews as $r): ?>
                        <div class="mb-3 pb-3 border-bottom">
                            <div class="d-flex justify-content-between">
                                <strong><?= $r['customer_name'] ?? $r['name'] ?></strong>
                                <small class="text-muted"><?= format_date($r['created_at']) ?></small>
                            </div>
                            <div class="mb-1">
                                <?php for ($i = 1; $i <= 5; $i++): ?>
                                    <i class="bi bi-star<?= $i <= $r['rating'] ? '-fill text-warning' : '' ?>"></i>
                                <?php endfor; ?>
                            </div>
                            <?php if ($r['title']): ?><strong><?= $r['title'] ?></strong><?php endif; ?>
                            <p class="mb-0"><?= nl2br($r['review'] ?? '') ?></p>
                        </div>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <p class="text-muted">No reviews yet.</p>
                    <?php endif; ?>

                    <?php if (\App::getSetting('enable_reviews', '1') == '1'): ?>
                    <h5 class="mt-3">Write a Review</h5>
                    <form id="reviewForm">
                        <div class="mb-2">
                            <div class="rating-input">
                                <?php for ($i = 5; $i >= 1; $i--): ?>
                                    <input type="radio" name="rating" value="<?= $i ?>" id="star<?= $i ?>" <?= $i == 5 ? 'checked' : '' ?>>
                                    <label for="star<?= $i ?>"><i class="bi bi-star-fill"></i></label>
                                <?php endfor; ?>
                            </div>
                        </div>
                        <?php if (!is_customer()): ?>
                        <div class="row mb-2">
                            <div class="col"><input type="text" name="name" class="form-control" placeholder="Your Name" required></div>
                            <div class="col"><input type="email" name="email" class="form-control" placeholder="Your Email" required></div>
                        </div>
                        <?php endif; ?>
                        <div class="mb-2"><input type="text" name="title" class="form-control" placeholder="Review Title"></div>
                        <div class="mb-2"><textarea name="review" class="form-control" rows="3" placeholder="Write your review..."></textarea></div>
                        <button type="submit" class="btn btn-primary btn-sm">Submit Review</button>
                    </form>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

    <?php if (!empty($related)): ?>
    <section class="mt-4" data-aos="fade-up">
        <div class="section-header">
            <h4>Related Products</h4>
        </div>
        <div class="row g-3">
            <?php foreach ($related as $p): ?>
                <div class="col-6 col-md-3">
                    <?php include __DIR__ . '/partials/product_card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>
    <?php endif; ?>
</div>

<div class="lightbox-overlay" id="lightbox">
    <button class="close-btn" onclick="closeLightbox()"><i class="bi bi-x-lg"></i></button>
    <img src="" alt="" id="lightboxImg">
</div>

<div class="mobile-cart-bar" id="mobileCartBar">
    <div class="price">
        <?php if ($product['discount_price']): ?>
            <?= format_price($product['discount_price']) ?>
        <?php else: ?>
            <?= format_price($product['selling_price']) ?>
        <?php endif; ?>
    </div>
    <button class="btn btn-primary add-to-cart" data-product-id="<?= $product['id'] ?>">
        <i class="bi bi-cart-plus"></i> Add to Cart
    </button>
</div>

<script>
function updateQty(delta) {
    var input = document.getElementById('qty');
    var val = parseInt(input.value) + delta;
    if (val < 1) val = 1;
    if (val > <?= $product['quantity'] ?: 1 ?>) val = <?= $product['quantity'] ?: 1 ?>;
    input.value = val;
}

document.getElementById('qty').addEventListener('change', function() {
    if (this.value < 1) this.value = 1;
    if (this.value > <?= $product['quantity'] ?: 1 ?>) this.value = <?= $product['quantity'] ?: 1 ?>;
});

document.querySelectorAll('.thumb-item').forEach(function(item) {
    item.addEventListener('click', function() {
        document.querySelectorAll('.thumb-item').forEach(function(t) { t.classList.remove('active'); });
        this.classList.add('active');
        document.getElementById('mainImage').src = this.getAttribute('data-src');
    });
});

document.getElementById('mainImageWrap').addEventListener('click', function() {
    document.getElementById('lightboxImg').src = document.getElementById('mainImage').src;
    document.getElementById('lightbox').classList.add('active');
});

function closeLightbox() {
    document.getElementById('lightbox').classList.remove('active');
}

document.getElementById('lightbox').addEventListener('click', function(e) {
    if (e.target === this) closeLightbox();
});

document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') closeLightbox();
});

var mainImage = document.getElementById('mainImageWrap');
var lens = document.getElementById('zoomLens');
mainImage.addEventListener('mousemove', function(e) {
    var rect = this.getBoundingClientRect();
    var x = e.clientX - rect.left;
    var y = e.clientY - rect.top;
    var xpct = x / rect.width * 100;
    var ypct = y / rect.height * 100;
    lens.style.display = 'block';
    lens.style.left = (x - 60) + 'px';
    lens.style.top = (y - 60) + 'px';
    mainImage.querySelector('img').style.transform = 'scale(1.5)';
    mainImage.querySelector('img').style.transformOrigin = xpct + '% ' + ypct + '%';
});
mainImage.addEventListener('mouseleave', function() {
    lens.style.display = 'none';
    mainImage.querySelector('img').style.transform = 'scale(1)';
});

var mobileBar = document.getElementById('mobileCartBar');
window.addEventListener('scroll', function() {
    if (window.innerWidth <= 768) {
        var productTop = document.querySelector('.product-info').getBoundingClientRect().top;
        mobileBar.style.display = productTop < 100 ? 'flex' : 'none';
    }
});
</script>