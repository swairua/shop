<div class="container py-4">
    <h3 class="fw-bold mb-4"><i class="bi bi-person-circle"></i> My Account</h3>

    <div class="row g-3 mb-4">
        <div class="col-6 col-md-3">
            <div class="dash-stat">
                <div class="icon primary"><i class="bi bi-bag-check"></i></div>
                <div class="stat-value"><?= $customer['total_orders'] ?? 0 ?></div>
                <div class="stat-label">Total Orders</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="dash-stat">
                <div class="icon success"><i class="bi bi-currency-dollar"></i></div>
                <div class="stat-value"><?= format_price($customer['total_spent'] ?? 0) ?></div>
                <div class="stat-label">Total Spent</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="dash-stat">
                <div class="icon warning"><i class="bi bi-heart"></i></div>
                <div class="stat-value"><?= $customer['wishlist_count'] ?? 0 ?></div>
                <div class="stat-label">Wishlist Items</div>
            </div>
        </div>
        <div class="col-6 col-md-3">
            <div class="dash-stat">
                <div class="icon info"><i class="bi bi-calendar"></i></div>
                <div class="stat-value" style="font-size:1rem;"><?= format_date_short($customer['created_at']) ?></div>
                <div class="stat-label">Member Since</div>
            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-md-3 mb-3">
            <div class="list-group shadow-sm">
                <a href="#profile" class="list-group-item list-group-item-action active" data-bs-toggle="tab" data-bs-target="#profile"><i class="bi bi-person"></i> Profile</a>
                <a href="#orders" class="list-group-item list-group-item-action" data-bs-toggle="tab" data-bs-target="#orders"><i class="bi bi-box"></i> Orders</a>
                <a href="#addresses" class="list-group-item list-group-item-action" data-bs-toggle="tab" data-bs-target="#addresses"><i class="bi bi-geo-alt"></i> Addresses</a>
                <a href="<?= base_url('wishlist') ?>" class="list-group-item list-group-item-action"><i class="bi bi-heart"></i> Wishlist</a>
                <hr class="my-1">
                <a href="<?= base_url('logout') ?>" class="list-group-item list-group-item-action text-danger"><i class="bi bi-box-arrow-right"></i> Logout</a>
            </div>
        </div>
        <div class="col-md-9">
            <div class="tab-content">
                <div class="tab-pane fade show active" id="profile">
                    <div class="card shadow-sm">
                        <div class="card-header"><h6 class="mb-0"><i class="bi bi-info-circle"></i> Profile Information</h6></div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-2"><strong>Name:</strong><br><?= $customer['name'] ?></div>
                                <div class="col-md-6 mb-2"><strong>Email:</strong><br><?= $customer['email'] ?></div>
                                <div class="col-md-6 mb-2"><strong>Phone:</strong><br><?= $customer['phone'] ?? '-' ?></div>
                                <div class="col-md-6 mb-2"><strong>Member Since:</strong><br><?= format_date_short($customer['created_at']) ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="orders">
                    <div class="card shadow-sm">
                        <div class="card-header"><h6 class="mb-0"><i class="bi bi-box"></i> My Orders</h6></div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0">
                                    <thead class="table-light">
                                        <tr><th>Order #</th><th>Total</th><th>Status</th><th>Payment</th><th>Date</th></tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($customer['orders'] as $o): ?>
                                        <tr>
                                            <td class="fw-medium">#<?= $o['order_number'] ?></td>
                                            <td><?= format_price($o['total']) ?></td>
                                            <td><?= get_status_badge($o['order_status']) ?></td>
                                            <td><?= get_status_badge($o['payment_status']) ?></td>
                                            <td class="text-muted small"><?= format_date_short($o['created_at']) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane fade" id="addresses">
                    <div class="card shadow-sm">
                        <div class="card-header"><h6 class="mb-0"><i class="bi bi-geo-alt"></i> Saved Addresses</h6></div>
                        <div class="card-body">
                            <?php if (empty($customer['addresses'])): ?>
                                <p class="text-muted">No addresses saved yet.</p>
                            <?php else: ?>
                                <?php foreach ($customer['addresses'] as $addr): ?>
                                <div class="border-bottom mb-2 pb-2 d-flex align-items-center gap-2">
                                    <i class="bi bi-geo-alt-fill text-primary"></i>
                                    <span><?= $addr['address_line1'] ?>, <?= $addr['city'] ?>, <?= $addr['country'] ?></span>
                                </div>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>