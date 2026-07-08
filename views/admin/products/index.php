<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Products (<?= $total ?>)</h4>
    <a href="<?= base_url('admin/productCreate') ?>" class="btn btn-primary"><i class="bi bi-plus"></i> New Product</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Image</th>
                        <th>Name</th>
                        <th>SKU</th>
                        <th>Price</th>
                        <th>Stock</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td>
                            <?php if ($p['featured_image']): ?>
                                <img src="<?= base_url('uploads/products/' . $p['featured_image']) ?>" width="50" height="50" style="object-fit: cover;" alt="">
                            <?php else: ?>
                                <div class="bg-light d-flex align-items-center justify-content-center" style="width:50px;height:50px"><i class="bi bi-image text-muted"></i></div>
                            <?php endif; ?>
                        </td>
                        <td><a href="<?= base_url('admin/productEdit/' . $p['id']) ?>" class="text-decoration-none"><?= $p['name'] ?></a></td>
                        <td><?= $p['sku'] ?></td>
                        <td><?= format_price($p['selling_price']) ?></td>
                        <td>
                            <?php if ($p['quantity'] <= 0): ?>
                                <span class="badge bg-danger">Out of Stock</span>
                            <?php elseif ($p['quantity'] <= $p['reorder_level']): ?>
                                <span class="badge bg-warning text-dark"><?= $p['quantity'] ?></span>
                            <?php else: ?>
                                <?= $p['quantity'] ?>
                            <?php endif; ?>
                        </td>
                        <td><?= get_status_badge($p['status']) ?></td>
                        <td>
                            <a href="<?= base_url('admin/productEdit/' . $p['id']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-pencil"></i></a>
                            <a href="<?= base_url('admin/inventoryAdjust/' . $p['id']) ?>" class="btn btn-sm btn-info"><i class="bi bi-boxes"></i></a>
                            <a href="<?= base_url('admin/productDelete/' . $p['id']) ?>" class="btn btn-sm btn-danger btn-delete" data-url="<?= base_url('admin/productDelete/' . $p['id']) ?>"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($totalPages > 1): ?>
    <div class="card-footer">
        <?= paginate($page, $totalPages, base_url('admin/products?page={page}')) ?>
    </div>
    <?php endif; ?>
</div>
