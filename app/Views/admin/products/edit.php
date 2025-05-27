<?= $this->extend('admin/layout/main') ?>

<?= $this->section('breadcrumb') ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/products') ?>">Products</a></li>
        <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-edit me-2"></i>Edit Product
        </li>
    </ol>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Edit Product</h1>
        <p class="text-muted mb-0">Update product information</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('product/' . $product['slug']) ?>" class="btn btn-outline-info" target="_blank">
            <i class="fas fa-eye me-2"></i>View Product
        </a>
        <a href="<?= base_url('admin/products') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Products
        </a>
    </div>
</div>

<!-- Product Form -->
<form action="<?= base_url('admin/products/' . $product['id']) ?>" method="POST" enctype="multipart/form-data" id="productForm">
    <?= csrf_field() ?>

    <div class="row">
        <!-- Main Product Information -->
        <div class="col-lg-8">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Product Information</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-8 mb-3">
                            <label for="name" class="form-label">Product Name <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="<?= old('name', $product['name']) ?>" required>
                            <?php if (isset($errors['name'])): ?>
                                <div class="text-danger small"><?= $errors['name'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sku" name="sku"
                                value="<?= old('sku', $product['sku']) ?>" required>
                            <?php if (isset($errors['sku'])): ?>
                                <div class="text-danger small"><?= $errors['sku'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Product Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug"
                            value="<?= old('slug', $product['slug']) ?>">
                        <small class="text-muted">URL-friendly version of the name (auto-generated, but editable)</small>
                    </div>

                    <div class="mb-3">
                        <label for="short_description" class="form-label">Short Description</label>
                        <textarea class="form-control" id="short_description" name="short_description"
                            rows="2" placeholder="Brief product description..."><?= old('short_description', $product['short_description']) ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Full Description</label>
                        <textarea class="form-control" id="description" name="description"
                            rows="6" placeholder="Detailed product description..."><?= old('description', $product['description']) ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="weight" class="form-label">Weight (kg)</label>
                            <input type="number" class="form-control" id="weight" name="weight"
                                step="0.01" value="<?= old('weight', $product['weight']) ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="dimensions" class="form-label">Dimensions (L x W x H)</label>
                            <input type="text" class="form-control" id="dimensions" name="dimensions"
                                value="<?= old('dimensions', $product['dimensions']) ?>" placeholder="e.g., 10 x 5 x 3 cm">
                        </div>
                    </div>
                </div>
            </div>

            <!-- Pricing -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Pricing & Inventory</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4 mb-3">
                            <label for="price" class="form-label">Regular Price <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" class="form-control" id="price" name="price"
                                    step="0.01" value="<?= old('price', $product['price']) ?>" required>
                            </div>
                            <?php if (isset($errors['price'])): ?>
                                <div class="text-danger small"><?= $errors['price'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="sale_price" class="form-label">Sale Price</label>
                            <div class="input-group">
                                <span class="input-group-text">₹</span>
                                <input type="number" class="form-control" id="sale_price" name="sale_price"
                                    step="0.01" value="<?= old('sale_price', $product['sale_price']) ?>">
                            </div>
                            <small class="text-muted">Leave empty if no discount</small>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity"
                                value="<?= old('stock_quantity', $product['stock_quantity']) ?>" required>
                            <?php if (isset($errors['stock_quantity'])): ?>
                                <div class="text-danger small"><?= $errors['stock_quantity'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- SEO -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">SEO Settings</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="meta_title" class="form-label">Meta Title</label>
                        <input type="text" class="form-control" id="meta_title" name="meta_title"
                            value="<?= old('meta_title', $product['meta_title']) ?>" maxlength="60">
                        <small class="text-muted">Recommended: 50-60 characters</small>
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control" id="meta_description" name="meta_description"
                            rows="3" maxlength="160"><?= old('meta_description', $product['meta_description']) ?></textarea>
                        <small class="text-muted">Recommended: 150-160 characters</small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="col-lg-4">
            <!-- Product Status -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Product Status</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="category_id" class="form-label">Category <span class="text-danger">*</span></label>
                        <select class="form-select select2" id="category_id" name="category_id" required>
                            <option value="">Select Category</option>
                            <?php foreach ($categories as $category): ?>
                                <option value="<?= $category['id'] ?>"
                                    <?= old('category_id', $product['category_id']) == $category['id'] ? 'selected' : '' ?>>
                                    <?= esc($category['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                        <?php if (isset($errors['category_id'])): ?>
                            <div class="text-danger small"><?= $errors['category_id'] ?></div>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                value="1" <?= old('is_active', $product['is_active']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">
                                Active Product
                            </label>
                        </div>
                        <small class="text-muted">Product will be visible on the website</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                value="1" <?= old('is_featured', $product['is_featured']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_featured">
                                Featured Product
                            </label>
                        </div>
                        <small class="text-muted">Product will appear in featured sections</small>
                    </div>
                </div>
            </div>

            <!-- Product Image -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Product Image</h5>
                </div>
                <div class="card-body">
                    <?php if (!empty($product['image'])): ?>
                        <div class="mb-3 text-center">
                            <img src="<?= base_url('uploads/products/' . $product['image']) ?>"
                                alt="<?= esc($product['name']) ?>"
                                class="img-thumbnail" style="max-width: 200px;">
                            <div class="mt-2">
                                <small class="text-muted">Current image</small>
                            </div>
                        </div>
                    <?php endif; ?>

                    <div class="mb-3">
                        <label for="image" class="form-label">
                            <?= !empty($product['image']) ? 'Replace Image' : 'Upload Image' ?>
                        </label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <small class="text-muted">Recommended: 800x800px, max 2MB</small>
                    </div>

                    <div id="image-preview" class="text-center" style="display: none;">
                        <img id="preview-img" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                        <div class="mt-2">
                            <small class="text-muted">New image preview</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Product Stats -->
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Product Statistics</h5>
                </div>
                <div class="card-body">
                    <div class="row text-center">
                        <div class="col-6">
                            <div class="border-end">
                                <h6 class="text-muted mb-1">Created</h6>
                                <small><?= date('M j, Y', strtotime($product['created_at'])) ?></small>
                            </div>
                        </div>
                        <div class="col-6">
                            <h6 class="text-muted mb-1">Updated</h6>
                            <small><?= date('M j, Y', strtotime($product['updated_at'])) ?></small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Product
                        </button>
                        <a href="<?= base_url('admin/products') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Products
                        </a>
                        <button type="button" class="btn btn-outline-danger"
                            onclick="deleteProduct(<?= $product['id'] ?>, '<?= esc($product['name']) ?>')">
                            <i class="fas fa-trash me-2"></i>Delete Product
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Auto-generate slug from product name
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;
        const slug = name.toLowerCase()
            .replace(/[^a-z0-9\s-]/g, '') // Remove special characters
            .replace(/\s+/g, '-') // Replace spaces with hyphens
            .replace(/-+/g, '-') // Replace multiple hyphens with single
            .trim('-'); // Remove leading/trailing hyphens

        document.getElementById('slug').value = slug;
    });

    // Image preview
    document.getElementById('image').addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                document.getElementById('preview-img').src = e.target.result;
                document.getElementById('image-preview').style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('image-preview').style.display = 'none';
        }
    });

    // Form validation
    document.getElementById('productForm').addEventListener('submit', function(e) {
        const salePrice = parseFloat(document.getElementById('sale_price').value) || 0;
        const regularPrice = parseFloat(document.getElementById('price').value) || 0;

        if (salePrice > 0 && salePrice >= regularPrice) {
            e.preventDefault();
            alert('Sale price must be less than regular price');
            return false;
        }
    });

    // Delete product
    function deleteProduct(id, name) {
        if (confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
            fetch(`<?= base_url('admin/products/') ?>${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '<?= base_url('admin/products') ?>';
                    } else {
                        alert('Failed to delete product');
                    }
                })
                .catch(error => {
                    alert('An error occurred');
                });
        }
    }
</script>
<?= $this->endSection() ?>