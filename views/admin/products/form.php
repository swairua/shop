<div class="d-flex justify-content-between align-items-center mb-3">
    <h4 class="mb-0"><?= isset($product) ? 'Edit Product' : 'New Product' ?></h4>
    <a href="<?= base_url('admin/products') ?>" class="btn btn-outline-secondary"><i class="bi bi-arrow-left"></i> Back</a>
</div>

<form method="POST" enctype="multipart/form-data" id="productForm">
    <?= csrf_field() ?>
    <div class="row">
        <div class="col-md-8">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Basic Information</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Product Name <span class="text-danger">*</span></label>
                        <input type="text" name="name" class="form-control" value="<?= $product['name'] ?? '' ?>" required>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="form-label">Slug</label>
                            <input type="text" name="slug" class="form-control" value="<?= $product['slug'] ?? '' ?>">
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Status</label>
                            <select name="status" class="form-select">
                                <option value="draft" <?= (isset($product) && $product['status'] == 'draft') ? 'selected' : '' ?>>Draft</option>
                                <option value="active" <?= (isset($product) && $product['status'] == 'active') ? 'selected' : '' ?>>Active</option>
                                <option value="inactive" <?= (isset($product) && $product['status'] == 'inactive') ? 'selected' : '' ?>>Inactive</option>
                            </select>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Short Description</label>
                        <textarea name="short_description" class="form-control" rows="2"><?= $product['short_description'] ?? '' ?></textarea>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Full Description</label>
                        <textarea name="description" class="form-control" rows="6" id="descriptionEditor"><?= $product['description'] ?? '' ?></textarea>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Pricing</h6></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Cost Price</label>
                            <input type="number" step="0.01" name="cost_price" class="form-control" value="<?= $product['cost_price'] ?? 0 ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Selling Price <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" name="selling_price" class="form-control" value="<?= $product['selling_price'] ?? 0 ?>" required>
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Discount Price</label>
                            <input type="number" step="0.01" name="discount_price" class="form-control" value="<?= $product['discount_price'] ?? '' ?>">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Tax Class</label>
                            <select name="tax_class_id" class="form-select">
                                <option value="">No Tax</option>
                                <?php foreach ($taxClasses as $t): ?>
                                    <option value="<?= $t['id'] ?>" <?= (isset($product) && $product['tax_class_id'] == $t['id']) ? 'selected' : '' ?>><?= $t['name'] ?> (<?= $t['rate'] ?>%)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Inventory</h6></div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Quantity</label>
                            <input type="number" name="quantity" class="form-control" value="<?= $product['quantity'] ?? 0 ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Reorder Level</label>
                            <input type="number" name="reorder_level" class="form-control" value="<?= $product['reorder_level'] ?? 5 ?>">
                        </div>
                        <div class="col-md-4 mb-3">
                            <label class="form-label">Product Type</label>
                            <select name="type" class="form-select">
                                <option value="physical" <?= (isset($product) && $product['type'] == 'physical') ? 'selected' : '' ?>>Physical</option>
                                <option value="digital" <?= (isset($product) && $product['type'] == 'digital') ? 'selected' : '' ?>>Digital</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Weight (kg)</label>
                            <input type="number" step="0.01" name="weight" class="form-control" value="<?= $product['weight'] ?? '' ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Length (cm)</label>
                            <input type="number" step="0.01" name="length" class="form-control" value="<?= $product['length'] ?? '' ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Width (cm)</label>
                            <input type="number" step="0.01" name="width" class="form-control" value="<?= $product['width'] ?? '' ?>">
                        </div>
                        <div class="col-md-3 mb-3">
                            <label class="form-label">Height (cm)</label>
                            <input type="number" step="0.01" name="height" class="form-control" value="<?= $product['height'] ?? '' ?>">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Organization</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Category</label>
                        <select name="category_id" class="form-select">
                            <option value="">No Category</option>
                            <?php
                            $cat = new Category();
                            echo $cat->getParentOptions($product['category_id'] ?? null);
                            ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Brand</label>
                        <select name="brand_id" class="form-select">
                            <option value="">No Brand</option>
                            <?php foreach ($brands as $b): ?>
                                <option value="<?= $b['id'] ?>" <?= (isset($product) && $product['brand_id'] == $b['id']) ? 'selected' : '' ?>><?= $b['name'] ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">SKU</label>
                        <input type="text" name="sku" class="form-control" value="<?= $product['sku'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Barcode</label>
                        <input type="text" name="barcode" class="form-control" value="<?= $product['barcode'] ?? '' ?>">
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Flags</h6></div>
                <div class="card-body">
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_featured" class="form-check-input" id="is_featured" value="1" <?= (isset($product) && $product['is_featured']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_featured">Featured Product</label>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_new_arrival" class="form-check-input" id="is_new_arrival" value="1" <?= (isset($product) && $product['is_new_arrival']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_new_arrival">New Arrival</label>
                    </div>
                    <div class="form-check mb-2">
                        <input type="checkbox" name="is_best_seller" class="form-check-input" id="is_best_seller" value="1" <?= (isset($product) && $product['is_best_seller']) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="is_best_seller">Best Seller</label>
                    </div>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">Product Images</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Upload Images</label>
                        <input type="file" name="images[]" class="form-control" multiple accept="image/*">
                        <small class="text-muted">First image will be set as featured</small>
                    </div>
                    <?php if (isset($productImages) && !empty($productImages)): ?>
                    <div class="row g-2">
                        <?php foreach ($productImages as $img): ?>
                        <div class="col-4">
                            <img src="<?= product_image($img['image']) ?>" class="img-thumbnail" style="width:100%;height:80px;object-fit:cover;">
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="card mb-3">
                <div class="card-header"><h6 class="mb-0">SEO</h6></div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Meta Title</label>
                        <input type="text" name="meta_title" class="form-control" value="<?= $product['meta_title'] ?? '' ?>">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Meta Description</label>
                        <textarea name="meta_description" class="form-control" rows="3"><?= $product['meta_description'] ?? '' ?></textarea>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <button type="submit" class="btn btn-primary btn-lg"><?= isset($product) ? 'Update' : 'Create' ?> Product</button>
</form>
