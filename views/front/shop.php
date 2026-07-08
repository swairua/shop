<div class="container py-4">
    <div class="row">
        <div class="col-lg-3">
            <div class="filter-sidebar">
                <div class="d-flex d-lg-none justify-content-between align-items-center mb-2">
                    <h6 class="mb-0">Filters</h6>
                    <button class="btn btn-outline-primary btn-sm" data-bs-toggle="offcanvas" data-bs-target="#filterOffcanvas">
                        <i class="bi bi-sliders"></i> Show Filters
                    </button>
                </div>

                <div class="d-none d-lg-block">
                    <div class="card">
                        <div class="card-header">Categories</div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <?php function renderCatFilter($cats, $depth = 0) { ?>
                                    <?php foreach ($cats as $cat): ?>
                                    <li class="list-group-item" style="padding-left: <?= 15 + $depth * 20 ?>px">
                                        <a href="<?= base_url('shop?category=' . $cat['id']) ?>" class="<?= (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'active fw-bold' : '' ?>">
                                            <?= $cat['name'] ?>
                                        </a>
                                    </li>
                                    <?php if (!empty($cat['children'])): ?>
                                        <?php renderCatFilter($cat['children'], $depth + 1); ?>
                                    <?php endif; ?>
                                    <?php endforeach; ?>
                                <?php } ?>
                                <?php renderCatFilter($categories); ?>
                            </ul>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">Price Range</div>
                        <div class="card-body">
                            <div id="priceRange"></div>
                            <div class="d-flex justify-content-between mt-2 small text-muted">
                                <span id="priceMin">KSh 0</span>
                                <span id="priceMax">KSh 500,000</span>
                            </div>
                            <form method="GET" action="<?= base_url('shop') ?>" class="mt-2" id="priceForm">
                                <?php foreach ($_GET as $k => $v): ?>
                                    <?php if (!in_array($k, ['min_price', 'max_price', 'page'])): ?>
                                        <input type="hidden" name="<?= $k ?>" value="<?= $v ?>">
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <input type="hidden" name="min_price" id="minPriceInput" value="<?= $_GET['min_price'] ?? '' ?>">
                                <input type="hidden" name="max_price" id="maxPriceInput" value="<?= $_GET['max_price'] ?? '' ?>">
                                <button type="submit" class="btn btn-sm btn-primary w-100 mt-2">Filter</button>
                            </form>
                        </div>
                    </div>

                    <div class="card">
                        <div class="card-header">Brands</div>
                        <div class="card-body p-0">
                            <ul class="list-group list-group-flush">
                                <?php foreach ($brands as $b): ?>
                                    <li class="list-group-item">
                                        <a href="<?= base_url('shop?brand=' . $b['id']) ?>" class="<?= (isset($_GET['brand']) && $_GET['brand'] == $b['id']) ? 'active fw-bold' : '' ?>">
                                            <?= $b['name'] ?>
                                        </a>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>

            <div class="offcanvas offcanvas-start d-lg-none" id="filterOffcanvas">
                <div class="offcanvas-header border-bottom">
                    <h5 class="mb-0">Filters</h5>
                    <button class="btn-close" data-bs-dismiss="offcanvas"></button>
                </div>
                <div class="offcanvas-body">
                    <?php include __DIR__ . '/partials/filter_content.php'; ?>
                </div>
            </div>
        </div>

        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3 flex-wrap gap-2">
                <div>
                    <h4 class="mb-0 fw-bold"><?= $total ?> Product<?= $total !== 1 ? 's' : '' ?></h4>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="grid-list-toggle btn-group btn-group-sm">
                        <button class="btn btn-outline-secondary active" onclick="setView('grid')" id="gridView"><i class="bi bi-grid-3x3-gap"></i></button>
                        <button class="btn btn-outline-secondary" onclick="setView('list')" id="listView"><i class="bi bi-list-ul"></i></button>
                    </div>
                    <select class="form-select form-select-sm" style="width:auto" onchange="window.location='<?= base_url('shop') ?>?sort='+this.value+'<?= isset($_GET['category']) ? '&category=' . $_GET['category'] : '' ?><?= isset($_GET['brand']) ? '&brand=' . $_GET['brand'] : '' ?>'">
                        <option value="newest" <?= ($filters['sort'] ?? '') == 'newest' ? 'selected' : '' ?>>Newest</option>
                        <option value="popular" <?= ($filters['sort'] ?? '') == 'popular' ? 'selected' : '' ?>>Popular</option>
                        <option value="price_asc" <?= ($filters['sort'] ?? '') == 'price_asc' ? 'selected' : '' ?>>Price: Low to High</option>
                        <option value="price_desc" <?= ($filters['sort'] ?? '') == 'price_desc' ? 'selected' : '' ?>>Price: High to Low</option>
                        <option value="name_asc" <?= ($filters['sort'] ?? '') == 'name_asc' ? 'selected' : '' ?>>Name: A-Z</option>
                    </select>
                </div>
            </div>

            <?php
            $activeFilters = [];
            if (!empty($_GET['category'])) $activeFilters[] = ['label' => 'Category: ' . $_GET['category'], 'url' => '?category=&' . http_build_query(array_diff_key($_GET, ['category' => '']))];
            if (!empty($_GET['brand'])) $activeFilters[] = ['label' => 'Brand: ' . $_GET['brand'], 'url' => '?brand=&' . http_build_query(array_diff_key($_GET, ['brand' => '']))];
            ?>
            <?php if (!empty($activeFilters)): ?>
            <div class="filter-pills">
                <?php foreach ($activeFilters as $f): ?>
                    <a href="<?= base_url('shop' . $f['url']) ?>" class="filter-pill"><i class="bi bi-x"></i> <?= $f['label'] ?></a>
                <?php endforeach; ?>
                <a href="<?= base_url('shop') ?>" class="filter-pill" style="background:var(--danger);color:#fff;">Clear All</a>
            </div>
            <?php endif; ?>

            <?php if (empty($products)): ?>
                <div class="empty-state">
                    <i class="bi bi-box"></i>
                    <h5>No products found</h5>
                    <p>Try adjusting your filters</p>
                    <a href="<?= base_url('shop') ?>" class="btn btn-primary">Clear Filters</a>
                </div>
            <?php else: ?>
                <div class="row g-3" id="productsContainer">
                    <?php foreach ($products as $p): ?>
                        <div class="col-6 col-md-4 product-col" data-aos="fade-up">
                            <?php include __DIR__ . '/partials/product_card.php'; ?>
                        </div>
                    <?php endforeach; ?>
                </div>
                <div class="d-flex justify-content-center mt-4">
                    <?= paginate($page, $totalPages, base_url('shop?page={page}' . (isset($_GET['category']) ? '&category=' . $_GET['category'] : '') . (isset($_GET['brand']) ? '&brand=' . $_GET['brand'] : '') . (isset($_GET['sort']) ? '&sort=' . $_GET['sort'] : '') . (isset($_GET['min_price']) ? '&min_price=' . $_GET['min_price'] : '') . (isset($_GET['max_price']) ? '&max_price=' . $_GET['max_price'] : ''))) ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script>
var gridViewBtn = document.getElementById('gridView');
var listViewBtn = document.getElementById('listView');
var container = document.getElementById('productsContainer');

function setView(view) {
    if (view === 'grid') {
        container.classList.remove('list-view');
        gridViewBtn.classList.add('active');
        listViewBtn.classList.remove('active');
        document.querySelectorAll('.product-col').forEach(function(el) {
            el.className = 'col-6 col-md-4 product-col';
        });
    } else {
        container.classList.add('list-view');
        listViewBtn.classList.add('active');
        gridViewBtn.classList.remove('active');
        document.querySelectorAll('.product-col').forEach(function(el) {
            el.className = 'col-12 product-col';
        });
    }
    localStorage.setItem('shopView', view);
}

var savedView = localStorage.getItem('shopView');
if (savedView) setView(savedView);

<?php if (isset($filters['min_price']) || isset($filters['max_price'])): ?>
var minPrice = <?= $_GET['min_price'] ?? 0 ?>;
var maxPrice = <?= $_GET['max_price'] ?? 500000 ?>;
<?php else: ?>
var minPrice = 0;
var maxPrice = 500000;
<?php endif; ?>

var priceRange = document.getElementById('priceRange');
if (priceRange) {
    noUiSlider.create(priceRange, {
        start: [Number(minPrice), Number(maxPrice)],
        connect: true,
        range: { 'min': 0, 'max': 500000 },
        step: 100,
        format: { to: function(v) { return Math.round(v); }, from: function(v) { return Number(v); } }
    });
    priceRange.noUiSlider.on('update', function(values) {
        document.getElementById('priceMin').textContent = 'KSh ' + Number(values[0]).toLocaleString();
        document.getElementById('priceMax').textContent = 'KSh ' + Number(values[1]).toLocaleString();
        document.getElementById('minPriceInput').value = values[0];
        document.getElementById('maxPriceInput').value = values[1];
    });
}
</script>