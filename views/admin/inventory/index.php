<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Inventory</h4>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Product</th>
                        <th>SKU</th>
                        <th>Current Stock</th>
                        <th>Reorder Level</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($products as $p): ?>
                    <tr>
                        <td><?= $p['name'] ?></td>
                        <td><?= $p['sku'] ?></td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="progress flex-grow-1 me-2" style="height:8px;max-width:100px">
                                    <?php
                                    $pct = $p['reorder_level'] > 0 ? min(100, ($p['quantity'] / $p['reorder_level']) * 100) : 100;
                                    $color = $p['quantity'] <= 0 ? 'bg-danger' : ($p['quantity'] <= $p['reorder_level'] ? 'bg-warning' : 'bg-success');
                                    ?>
                                    <div class="progress-bar <?= $color ?>" style="width:<?= $pct ?>%"></div>
                                </div>
                                <span class="fw-bold"><?= $p['quantity'] ?></span>
                            </div>
                        </td>
                        <td><?= $p['reorder_level'] ?></td>
                        <td>
                            <?php if ($p['quantity'] <= 0): ?>
                                <?= get_status_badge('out_of_stock') ?>
                            <?php elseif ($p['quantity'] <= $p['reorder_level']): ?>
                                <?= get_status_badge('low_stock') ?>
                            <?php else: ?>
                                <?= get_status_badge('in_stock') ?>
                            <?php endif; ?>
                        </td>
                        <td>
                            <a href="<?= base_url('admin/inventoryAdjust/' . $p['id']) ?>" class="btn btn-sm btn-warning"><i class="bi bi-plus-minus"></i> Adjust</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php if ($totalPages > 1): ?>
    <div class="card-footer">
        <?= paginate($page, $totalPages, base_url('admin/inventory?page={page}')) ?>
    </div>
    <?php endif; ?>
</div>
