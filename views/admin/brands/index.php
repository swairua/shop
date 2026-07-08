<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0">Brands</h4>
    <a href="<?= base_url('admin/brandCreate') ?>" class="btn btn-primary" data-modal="1"><i class="bi bi-plus"></i> New Brand</a>
</div>

<div class="card">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Logo</th>
                        <th>Name</th>
                        <th>Products</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($brands as $b): ?>
                    <tr>
                        <td>
                            <?php if ($b['logo']): ?>
                                <img src="<?= base_url('uploads/brands/' . $b['logo']) ?>" height="30" alt="">
                            <?php else: ?>
                                <i class="bi bi-building text-muted fs-5"></i>
                            <?php endif; ?>
                        </td>
                        <td><?= $b['name'] ?></td>
                        <td><?= $b['product_count'] ?? 0 ?></td>
                        <td><?= get_status_badge($b['status']) ?></td>
                        <td>
                            <a href="<?= base_url('admin/brandEdit/' . $b['id']) ?>" class="btn btn-sm btn-warning" data-modal="1"><i class="bi bi-pencil"></i></a>
                            <a href="<?= base_url('admin/brandDelete/' . $b['id']) ?>" class="btn btn-sm btn-danger btn-delete" data-url="<?= base_url('admin/brandDelete/' . $b['id']) ?>"><i class="bi bi-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
