<div class="container py-4">
    <nav aria-label="breadcrumb">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?= base_url('') ?>">Home</a></li>
            <li class="breadcrumb-item"><a href="<?= base_url('shop') ?>">Shop</a></li>
            <li class="breadcrumb-item active"><?= $category['name'] ?></li>
        </ol>
    </nav>

    <h3><?= $category['name'] ?></h3>
    <?php if ($category['description']): ?>
        <p class="text-muted"><?= nl2br($category['description'] ?? '') ?></p>
    <?php endif; ?>

    <?php if (!empty($subcategories)): ?>
    <div class="row g-2 mb-4">
        <?php foreach ($subcategories as $sub): ?>
        <div class="col-md-3">
            <a href="<?= base_url('shop?category=' . $sub['id']) ?>" class="btn btn-outline-primary w-100">
                <?= str_repeat('&nbsp;', $sub['depth'] * 4) ?><?= $sub['name'] ?>
            </a>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <div class="row g-3">
        <?php foreach ($products as $p): ?>
            <?php include __DIR__ . '/partials/product_card.php'; ?>
        <?php endforeach; ?>
    </div>

    <?php if ($totalPages > 1): ?>
    <div class="mt-3">
        <?= paginate($page, $totalPages, base_url('category/' . $category['slug'] . '?page={page}')) ?>
    </div>
    <?php endif; ?>
</div>
