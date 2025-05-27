<?= $this->extend('admin/layout/main') ?>

<?= $this->section('breadcrumb') ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/categories') ?>">Categories</a></li>
        <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-edit me-2"></i>Edit Category
        </li>
    </ol>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Edit Category</h1>
        <p class="text-muted mb-0">Update category information</p>
    </div>
    <div class="d-flex gap-2">
        <a href="<?= base_url('category/' . $category['slug']) ?>" class="btn btn-outline-info" target="_blank">
            <i class="fas fa-eye me-2"></i>View Category
        </a>
        <a href="<?= base_url('admin/categories') ?>" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Back to Categories
        </a>
    </div>
</div>

<!-- Category Form -->
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Category Information</h5>
            </div>
            <div class="card-body">
                <form action="<?= base_url('admin/categories/' . $category['id']) ?>" method="POST" enctype="multipart/form-data" id="categoryForm">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="<?= old('name', $category['name']) ?>" required>
                        <?php if (isset($errors['name'])): ?>
                            <div class="text-danger small"><?= $errors['name'] ?></div>
                        <?php endif; ?>
                        <small class="text-muted">The category name will be displayed on your website</small>
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Category Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug"
                            value="<?= old('slug', $category['slug']) ?>">
                        <small class="text-muted">URL-friendly version of the name (auto-generated, but editable)</small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description"
                            rows="4" placeholder="Enter category description..."><?= old('description', $category['description']) ?></textarea>
                        <small class="text-muted">Brief description of what products this category contains</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order"
                                value="<?= old('sort_order', $category['sort_order']) ?>" min="0">
                            <small class="text-muted">Lower numbers appear first (0 = highest priority)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">
                                <?= !empty($category['image']) ? 'Replace Image' : 'Category Image' ?>
                            </label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Optional: Upload category image (max 2MB)</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                value="1" <?= old('is_active', $category['is_active']) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">
                                Active Category
                            </label>
                        </div>
                        <small class="text-muted">Active categories will be visible on the website</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Update Category
                        </button>
                        <a href="<?= base_url('admin/categories') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left me-2"></i>Back to Categories
                        </a>
                        <button type="button" class="btn btn-outline-danger"
                            onclick="deleteCategory(<?= $category['id'] ?>, '<?= esc($category['name']) ?>')">
                            <i class="fas fa-trash me-2"></i>Delete Category
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Current/Preview Image -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Category Image</h5>
            </div>
            <div class="card-body">
                <?php if (!empty($category['image'])): ?>
                    <div class="mb-3 text-center">
                        <img src="<?= base_url('uploads/categories/' . $category['image']) ?>"
                            alt="<?= esc($category['name']) ?>"
                            class="img-thumbnail" style="max-width: 100%;" id="current-image">
                        <div class="mt-2">
                            <small class="text-muted">Current image</small>
                        </div>
                    </div>
                <?php endif; ?>

                <div id="image-preview" class="text-center" style="display: none;">
                    <img id="preview-img" src="" alt="Preview" class="img-thumbnail" style="max-width: 100%;">
                    <div class="mt-2">
                        <small class="text-muted">New image preview</small>
                    </div>
                </div>

                <?php if (empty($category['image'])): ?>
                    <div id="no-image" class="text-center py-4">
                        <i class="fas fa-image fa-3x text-muted mb-3"></i>
                        <p class="text-muted">No image uploaded</p>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Category Statistics -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Category Statistics</h5>
            </div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h6 class="text-muted mb-1">Products</h6>
                            <h4 class="text-primary mb-0" id="product-count">-</h4>
                        </div>
                    </div>
                    <div class="col-6">
                        <h6 class="text-muted mb-1">Created</h6>
                        <small><?= date('M j, Y', strtotime($category['created_at'])) ?></small>
                    </div>
                </div>
                <hr>
                <div class="row text-center">
                    <div class="col-6">
                        <div class="border-end">
                            <h6 class="text-muted mb-1">Status</h6>
                            <span class="badge bg-<?= $category['is_active'] ? 'success' : 'danger' ?>">
                                <?= $category['is_active'] ? 'Active' : 'Inactive' ?>
                            </span>
                        </div>
                    </div>
                    <div class="col-6">
                        <h6 class="text-muted mb-1">Updated</h6>
                        <small><?= date('M j, Y', strtotime($category['updated_at'])) ?></small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="<?= base_url('category/' . $category['slug']) ?>" class="btn btn-outline-info btn-sm" target="_blank">
                        <i class="fas fa-eye me-2"></i>View on Website
                    </a>
                    <a href="<?= base_url('admin/products?category=' . $category['id']) ?>" class="btn btn-outline-primary btn-sm">
                        <i class="fas fa-box me-2"></i>View Products
                    </a>
                    <button class="btn btn-outline-secondary btn-sm" onclick="duplicateCategory()">
                        <i class="fas fa-copy me-2"></i>Duplicate Category
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Load product count
    document.addEventListener('DOMContentLoaded', function() {
        loadProductCount();
    });

    function loadProductCount() {
        fetch(`<?= base_url('admin/categories/') ?><?= $category['id'] ?>/product-count`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('product-count').textContent = data.count || 0;
            })
            .catch(error => {
                document.getElementById('product-count').textContent = '0';
            });
    }

    // Auto-generate slug from category name
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
            // Check file size (2MB limit)
            if (file.size > 2 * 1024 * 1024) {
                alert('File size must be less than 2MB');
                this.value = '';
                return;
            }

            // Check file type
            if (!file.type.startsWith('image/')) {
                alert('Please select a valid image file');
                this.value = '';
                return;
            }

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

    // Delete category
    function deleteCategory(id, name) {
        if (confirm(`Are you sure you want to delete "${name}"? This action cannot be undone.`)) {
            fetch(`<?= base_url('admin/categories/') ?>${id}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        window.location.href = '<?= base_url('admin/categories') ?>';
                    } else {
                        alert(data.message || 'Failed to delete category');
                    }
                })
                .catch(error => {
                    alert('An error occurred');
                });
        }
    }

    // Duplicate category
    function duplicateCategory() {
        const name = document.getElementById('name').value;
        if (confirm(`Create a duplicate of "${name}"?`)) {
            // This would typically send an AJAX request to duplicate
            alert('Duplicate functionality would be implemented here');
        }
    }

    // Form validation
    document.getElementById('categoryForm').addEventListener('submit', function(e) {
        const name = document.getElementById('name').value.trim();

        if (name.length < 2) {
            e.preventDefault();
            alert('Category name must be at least 2 characters long');
            document.getElementById('name').focus();
            return false;
        }

        // Show loading state
        const submitBtn = this.querySelector('button[type="submit"]');
        const originalText = submitBtn.innerHTML;
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Updating...';
        submitBtn.disabled = true;

        // Re-enable button after 3 seconds (in case of error)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });
</script>
<?= $this->endSection() ?>