<div class="container py-4">
    <h3 class="fw-bold mb-4"><i class="bi bi-heart text-danger"></i> My Wishlist</h3>
    <?php
    $wishlist = [];
    if (is_customer()) {
        $result = $this->db->query("SELECT p.*, 1 as in_wishlist FROM wishlist w JOIN products p ON w.product_id = p.id WHERE w.customer_id = " . intval($_SESSION['customer_id']) . " ORDER BY w.created_at DESC");
        $wishlist = $result->fetch_all(MYSQLI_ASSOC);
    }
    ?>
    <?php if (empty($wishlist)): ?>
        <div class="empty-state">
            <i class="bi bi-heart"></i>
            <h5>Your wishlist is empty</h5>
            <p>Save items you love to your wishlist</p>
            <a href="<?= base_url('shop') ?>" class="btn btn-primary"><i class="bi bi-bag-check"></i> Browse Products</a>
        </div>
    <?php else: ?>
        <div class="row g-3">
            <?php foreach ($wishlist as $p): ?>
                <div class="col-6 col-md-4 col-lg-3" data-aos="fade-up">
                    <?php include __DIR__ . '/partials/product_card.php'; ?>
                </div>
            <?php endforeach; ?>
        </div>
    <?php endif; ?>
</div>