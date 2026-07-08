<div class="card mb-3">
    <div class="card-header">Categories</div>
    <div class="card-body p-0">
        <ul class="list-group list-group-flush">
            <?php function renderCatFilterMobile($cats, $depth = 0) { ?>
                <?php foreach ($cats as $cat): ?>
                <li class="list-group-item" style="padding-left: <?= 15 + $depth * 20 ?>px">
                    <a href="<?= base_url('shop?category=' . $cat['id']) ?>" class="<?= (isset($_GET['category']) && $_GET['category'] == $cat['id']) ? 'active fw-bold' : '' ?>">
                        <?= $cat['name'] ?>
                    </a>
                </li>
                <?php if (!empty($cat['children'])): ?>
                    <?php renderCatFilterMobile($cat['children'], $depth + 1); ?>
                <?php endif; ?>
                <?php endforeach; ?>
            <?php } ?>
            <?php renderCatFilterMobile($categories); ?>
        </ul>
    </div>
</div>

<div class="card mb-3">
    <div class="card-header">Price Range</div>
    <div class="card-body">
        <div id="priceRangeMobile"></div>
        <div class="d-flex justify-content-between mt-2 small text-muted">
            <span id="priceMinMobile">KSh 0</span>
            <span id="priceMaxMobile">KSh 500,000</span>
        </div>
    </div>
</div>

<div class="card mb-3">
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

<button class="btn btn-primary w-100" onclick="document.querySelector('[data-bs-dismiss=offcanvas]').click()">Apply Filters</button>

<script>
setTimeout(function() {
    var pr = document.getElementById('priceRangeMobile');
    if (pr && !pr.noUiSlider) {
        noUiSlider.create(pr, {
            start: [0, 500000],
            connect: true,
            range: { 'min': 0, 'max': 500000 },
            step: 100,
            format: { to: function(v) { return Math.round(v); }, from: function(v) { return Number(v); } }
        });
        pr.noUiSlider.on('update', function(values) {
            document.getElementById('priceMinMobile').textContent = 'KSh ' + Number(values[0]).toLocaleString();
            document.getElementById('priceMaxMobile').textContent = 'KSh ' + Number(values[1]).toLocaleString();
        });
    }
}, 500);
</script>