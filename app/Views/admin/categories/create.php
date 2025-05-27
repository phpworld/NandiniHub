<?= $this->extend('admin/layout/main') ?>

<?= $this->section('breadcrumb') ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= base_url('admin/categories') ?>">Categories</a></li>
        <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-plus me-2"></i>Add Category
        </li>
    </ol>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Add New Category</h1>
        <p class="text-muted mb-0">Create a new product category</p>
    </div>
    <div>
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
                <form action="<?= base_url('admin/categories') ?>" method="POST" enctype="multipart/form-data" id="categoryForm">
                    <?= csrf_field() ?>

                    <div class="mb-3">
                        <label for="name" class="form-label">Category Name <span class="text-danger">*</span></label>
                        <input type="text" class="form-control" id="name" name="name"
                            value="<?= old('name') ?>" required>
                        <?php if (isset($errors['name'])): ?>
                            <div class="text-danger small"><?= $errors['name'] ?></div>
                        <?php endif; ?>
                        <small class="text-muted">The category name will be displayed on your website</small>
                    </div>

                    <div class="mb-3">
                        <label for="slug" class="form-label">Category Slug</label>
                        <input type="text" class="form-control" id="slug" name="slug"
                            value="<?= old('slug') ?>">
                        <small class="text-muted">URL-friendly version of the name (auto-generated, but editable)</small>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description"
                            rows="4" placeholder="Enter category description..."><?= old('description') ?></textarea>
                        <small class="text-muted">Brief description of what products this category contains</small>
                    </div>

                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="sort_order" class="form-label">Sort Order</label>
                            <input type="number" class="form-control" id="sort_order" name="sort_order"
                                value="<?= old('sort_order', 0) ?>" min="0">
                            <small class="text-muted">Lower numbers appear first (0 = highest priority)</small>
                        </div>

                        <div class="col-md-6 mb-3">
                            <label for="image" class="form-label">Category Image</label>
                            <input type="file" class="form-control" id="image" name="image" accept="image/*">
                            <small class="text-muted">Optional: Upload category image (max 2MB)</small>
                        </div>
                    </div>

                    <div class="mb-3">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active"
                                value="1" <?= old('is_active', 1) ? 'checked' : '' ?>>
                            <label class="form-check-label" for="is_active">
                                Active Category
                            </label>
                        </div>
                        <small class="text-muted">Active categories will be visible on the website</small>
                    </div>

                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-save me-2"></i>Save Category
                        </button>
                        <a href="<?= base_url('admin/categories') ?>" class="btn btn-outline-secondary">
                            <i class="fas fa-times me-2"></i>Cancel
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <div class="col-lg-4">
        <!-- Image Preview -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 class="mb-0">Image Preview</h5>
            </div>
            <div class="card-body">
                <div id="image-preview" class="text-center" style="display: none;">
                    <img id="preview-img" src="" alt="Preview" class="img-thumbnail" style="max-width: 100%;">
                </div>
                <div id="no-image" class="text-center py-4">
                    <i class="fas fa-image fa-3x text-muted mb-3"></i>
                    <p class="text-muted">No image selected</p>
                </div>
            </div>
        </div>

        <!-- Category Guidelines -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">Category Guidelines</h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled small">
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Use clear, descriptive names
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Keep descriptions concise
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Use sort order to organize display
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Images should be 400x400px or larger
                    </li>
                    <li class="mb-2">
                        <i class="fas fa-check text-success me-2"></i>
                        Activate only when ready to display
                    </li>
                </ul>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
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
                document.getElementById('no-image').style.display = 'none';
            };
            reader.readAsDataURL(file);
        } else {
            document.getElementById('image-preview').style.display = 'none';
            document.getElementById('no-image').style.display = 'block';
        }
    });

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
        submitBtn.innerHTML = '<i class="fas fa-spinner fa-spin me-2"></i>Saving...';
        submitBtn.disabled = true;

        // Re-enable button after 3 seconds (in case of error)
        setTimeout(() => {
            submitBtn.innerHTML = originalText;
            submitBtn.disabled = false;
        }, 3000);
    });

    // Character counter for description
    const descriptionField = document.getElementById('description');
    const maxLength = 500;

    // Create character counter
    const counterDiv = document.createElement('div');
    counterDiv.className = 'text-muted small mt-1';
    counterDiv.id = 'desc-counter';
    descriptionField.parentNode.appendChild(counterDiv);

    function updateCounter() {
        const remaining = maxLength - descriptionField.value.length;
        counterDiv.textContent = `${descriptionField.value.length}/${maxLength} characters`;
        counterDiv.className = remaining < 50 ? 'text-danger small mt-1' : 'text-muted small mt-1';
    }

    descriptionField.addEventListener('input', updateCounter);
    descriptionField.setAttribute('maxlength', maxLength);
    updateCounter();
</script>
<?= $this->endSection() ?>