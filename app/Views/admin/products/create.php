<?= $this->extend('admin/layout/main') ?>

<?= $this->section('breadcrumb') ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/products') ?>">Products</a></li>
        <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-plus me-2"></i>Add Product
        </li>
    </ol>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Add New Product</h1>
        <p class="text-muted mb-0">Create a new product for your catalog</p>
    </div>
    <div>
        <a href="<?= base_url('admin/products') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Products
        </a>
    </div>
</div>

<!-- Product Form -->
<form action="<?= base_url('admin/products') ?>" method="POST" enctype="multipart/form-data" id="productForm">
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
                                value="<?= old('name') ?>" required>
                            <?php if (isset($errors['name'])): ?>
                                <div class="text-danger small"><?= $errors['name'] ?></div>
                            <?php endif; ?>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="sku" class="form-label">SKU <span class="text-danger">*</span></label>
                            <input type="text" class="form-control" id="sku" name="sku"
                                value="<?= old('sku') ?>" required>
                            <?php if (isset($errors['sku'])): ?>
                                <div class="text-danger small"><?= $errors['sku'] ?></div>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Product Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug"
                            value="<?= old('slug') ?>">
                        <small class="text-muted">URL-friendly version of the name (auto-generated, but editable)</small>
                    </div>

                    <div class="mb-3">
                        <label for="short_description" class="form-label">Short Description</label>
                        <textarea class="form-control" id="short_description" name="short_description"
                            rows="2" placeholder="Brief product description..."><?= old('short_description') ?></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Full Description</label>
                        <textarea class="form-control" id="description" name="description"
                            rows="6" placeholder="Detailed product description..."><?= old('description') ?></textarea>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="weight" class="form-label">Weight (kg)</label>
                            <input type="number" class="form-control" id="weight" name="weight"
                                step="0.01" value="<?= old('weight') ?>">
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="dimensions" class="form-label">Dimensions (L x W x H)</label>
                            <input type="text" class="form-control" id="dimensions" name="dimensions"
                                value="<?= old('dimensions') ?>" placeholder="e.g., 10 x 5 x 3 cm">
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
                                    step="0.01" value="<?= old('price') ?>" required>
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
                                    step="0.01" value="<?= old('sale_price') ?>">
                            </div>
                            <small class="text-muted">Leave empty if no discount</small>
                        </div>

                        <div class="col-md-4 mb-3">
                            <label for="stock_quantity" class="form-label">Stock Quantity <span class="text-danger">*</span></label>
                            <input type="number" class="form-control" id="stock_quantity" name="stock_quantity"
                                value="<?= old('stock_quantity') ?>" required>
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
                            value="<?= old('meta_title') ?>" maxlength="60">
                        <small class="text-muted">Recommended: 50-60 characters</small>
                    </div>

                    <div class="mb-3">
                        <label for="meta_description" class="form-label">Meta Description</label>
                        <textarea class="form-control" id="meta_description" name="meta_description"
                            rows="3" maxlength="160"><?= old('meta_description') ?></textarea>
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
                                <option value="<?= $category['id'] ?>" <?= old('category_id') == $category['id'] ? 'selected' : '' ?>>
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
                                value="1" <?= old('is_active') ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">
                                Active Product
                            </label>
                        </div>
                        <small class="text-muted">Product will be visible on the website</small>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_featured" name="is_featured"
                                value="1" <?= old('is_featured') ? 'checked' : '' ?>>
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
                    <div class="mb-3">
                        <label for="image" class="form-label">Main Image</label>
                        <input type="file" class="form-control" id="image" name="image" accept="image/*">
                        <small class="text-muted">Recommended: 800x800px, max 2MB</small>
                    </div>

                    <div id="image-preview" class="text-center" style="display: none;">
                        <img id="preview-img" src="" alt="Preview" class="img-thumbnail" style="max-width: 200px;">
                    </div>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="card">
                <div class="card-body">
                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Product
                        </button>
                        <button type="button" class="btn btn-outline-secondary" onclick="saveDraft()">
                            <i class="fas fa-file-alt me-2"></i>Save as Draft
                        </button>
                        <a href="<?= base_url('admin/products') ?>" class="btn btn-outline-danger">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Auto-generate SKU and slug from product name
    document.getElementById('name').addEventListener('input', function() {
        const name = this.value;

        // Generate SKU
        const sku = name.toUpperCase()
            .replace(/[^A-Z0-9]/g, '')
            .substring(0, 10) + '-' + Math.random().toString(36).substr(2, 4).toUpperCase();

        if (!document.getElementById('sku').value) {
            document.getElementById('sku').value = sku;
        }

        // Generate slug
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

    // Save as draft
    function saveDraft() {
        // Set is_active to 0 for draft
        document.getElementById('is_active').checked = false;

        // Submit form
        document.getElementById('productForm').submit();
    }

    // Character counters
    function updateCharCount(inputId, countId, maxLength) {
        const input = document.getElementById(inputId);
        const counter = document.getElementById(countId);

        input.addEventListener('input', function() {
            const remaining = maxLength - this.value.length;
            counter.textContent = `${this.value.length}/${maxLength} characters`;
            counter.className = remaining < 10 ? 'text-danger small' : 'text-muted small';
        });
    }

    // Initialize character counters
    updateCharCount('meta_title', 'meta-title-count', 60);
    updateCharCount('meta_description', 'meta-desc-count', 160);
</script>
<?= $this->endSection() ?>