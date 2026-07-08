<div class="container py-4">
    <h3>Compare Products</h3>
    <?php
    $compareIds = [];
    if (is_customer()) {
        $result = $this->db->query("SELECT product_id FROM compare_list WHERE customer_id = {$_SESSION['customer_id']}");
        $compareIds = array_column($result->fetch_all(MYSQLI_ASSOC), 'product_id');
    } else {
        $compareIds = $_SESSION['compare'] ?? [];
    }

    $products = [];
    if (!empty($compareIds)) {
        $ids = implode(',', array_map('intval', $compareIds));
        $products = $this->db->query("SELECT * FROM products WHERE id IN ({$ids}) AND status = 'active'")->fetch_all(MYSQLI_ASSOC);
    }
    ?>
    <?php if (empty($products)): ?>
        <div class="text-center py-5">
            <i class="bi bi-arrow-left-right fs-1 text-muted"></i>
            <h5 class="mt-2">No products to compare</h5>
            <a href="<?= base_url('shop') ?>" class="btn btn-primary">Browse Products</a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table table-bordered">
                <thead class="table-light">
                    <tr>
                        <th style="width:150px">Feature</th>
                        <?php foreach ($products as $p): ?>
                            <th class="text-center"><?= $p['name'] ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <th>Image</th>
                        <?php foreach ($products as $p): ?>
                            <td class="text-center">
                                <img src="<?= product_image($p['featured_image'] ?? '', '150x150', $p['name']) ?>" style="height:100px;object-fit:cover;">
                            </td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th>Price</th>
                        <?php foreach ($products as $p): ?>
                            <td class="text-center"><?= format_price($p['selling_price']) ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th>SKU</th>
                        <?php foreach ($products as $p): ?>
                            <td class="text-center"><?= $p['sku'] ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th>Stock</th>
                        <?php foreach ($products as $p): ?>
                            <td class="text-center"><?= $p['quantity'] > 0 ? '<span class="text-success">In Stock</span>' : '<span class="text-danger">Out of Stock</span>' ?></td>
                        <?php endforeach; ?>
                    </tr>
                    <tr>
                        <th>Action</th>
                        <?php foreach ($products as $p): ?>
                            <td class="text-center">
                                <button class="btn btn-primary btn-sm add-to-cart" data-product-id="<?= $p['id'] ?>">Add to Cart</button>
                            </td>
                        <?php endforeach; ?>
                    </tr>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
