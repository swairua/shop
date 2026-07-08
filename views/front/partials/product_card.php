<div class="product-card">
    <div class="card-img-wrap">
        <a href="<?= base_url('product/' . $p['slug']) ?>">
            <img src="<?= product_image($p['featured_image'] ?? '', '300x300', $p['name']) ?>" alt="<?= $p['name'] ?>" loading="lazy">
        </a>
        <button class="wishlist-btn add-to-wishlist <?= (isset($p['in_wishlist']) && $p['in_wishlist']) ? 'active' : '' ?>" data-product-id="<?= $p['id'] ?>">
            <i class="bi bi-heart<?= (isset($p['in_wishlist']) && $p['in_wishlist']) ? '-fill' : '' ?>"></i>
        </button>
        <div class="badge-pos">
            <?php if (isset($p['discount_price']) && $p['discount_price'] && $p['selling_price'] > 0): ?>
                <span class="badge badge-discount">-<?= round((1 - $p['discount_price'] / $p['selling_price']) * 100) ?>%</span>
            <?php endif; ?>
            <?php if (isset($p['is_new_arrival']) && $p['is_new_arrival']): ?>
                <span class="badge badge-new">New</span>
            <?php endif; ?>
        </div>
        <div class="card-overlay">
            <button class="btn btn-light btn-sm add-to-cart" data-product-id="<?= $p['id'] ?>">
                <i class="bi bi-cart-plus"></i> Add
            </button>
            <a href="<?= base_url('product/' . $p['slug']) ?>" class="btn btn-outline-light btn-sm">
                <i class="bi bi-eye"></i> View
            </a>
        </div>
    </div>
    <div class="card-body">
        <?php if (isset($p['rating']) && $p['rating'] > 0): ?>
        <div class="rating-stars mb-1">
            <?php for ($i = 1; $i <= 5; $i++): ?>
                <i class="bi bi-star<?= $i <= round($p['rating']) ? '-fill' : '' ?>"></i>
            <?php endfor; ?>
            <?php if (isset($p['rating_count'])): ?>
                <span class="rating-count">(<?= $p['rating_count'] ?>)</span>
            <?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="card-title">
            <a href="<?= base_url('product/' . $p['slug']) ?>"><?= $p['name'] ?></a>
        </div>
        <div class="d-flex align-items-center">
            <?php if (isset($p['discount_price']) && $p['discount_price']): ?>
                <span class="price"><?= format_price($p['discount_price']) ?></span>
                <span class="price-old"><?= format_price($p['selling_price']) ?></span>
            <?php else: ?>
                <span class="price"><?= format_price($p['selling_price']) ?></span>
            <?php endif; ?>
        </div>
        <?php if (isset($p['quantity'])): ?>
        <div class="stock-indicator">
            <span class="dot <?= $p['quantity'] > 10 ? 'in-stock' : ($p['quantity'] > 0 ? 'low-stock' : 'out-of-stock') ?>"></span>
            <span><?= $p['quantity'] > 0 ? ($p['quantity'] > 10 ? 'In Stock' : 'Only ' . $p['quantity'] . ' left') : 'Out of Stock' ?></span>
        </div>
        <?php endif; ?>
    </div>
</div>