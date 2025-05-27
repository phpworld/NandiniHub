<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Manage Banners</h1>
                <a href="<?= base_url('admin/banners/create') ?>" class="btn btn-primary">
                    <i class="fas fa-plus"></i> Add New Banner
                </a>
            </div>

            <?php if (session()->getFlashdata('success')): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('success') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (session()->getFlashdata('error')): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <?= session()->getFlashdata('error') ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-body">
                    <?php if (empty($banners)): ?>
                        <div class="text-center py-5">
                            <i class="fas fa-image fa-4x text-muted mb-3"></i>
                            <h4>No Banners Found</h4>
                            <p class="text-muted">Create your first banner to get started.</p>
                            <a href="<?= base_url('admin/banners/create') ?>" class="btn btn-primary">Add New Banner</a>
                        </div>
                    <?php else: ?>
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Title</th>
                                        <th>Status</th>
                                        <th>Sort Order</th>
                                        <th>Created</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($banners as $banner): ?>
                                        <tr>
                                            <td><?= $banner['id'] ?></td>
                                            <td>
                                                <strong><?= esc($banner['title']) ?></strong>
                                                <?php if ($banner['subtitle']): ?>
                                                    <br><small class="text-muted"><?= esc(substr($banner['subtitle'], 0, 100)) ?>...</small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input status-toggle" type="checkbox"
                                                           data-id="<?= $banner['id'] ?>"
                                                           <?= $banner['is_active'] ? 'checked' : '' ?>>
                                                    <label class="form-check-label">
                                                        <?= $banner['is_active'] ? 'Active' : 'Inactive' ?>
                                                    </label>
                                                </div>
                                            </td>
                                            <td><?= $banner['sort_order'] ?></td>
                                            <td><?= date('M j, Y', strtotime($banner['created_at'])) ?></td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="<?= base_url('admin/banners/' . $banner['id'] . '/edit') ?>"
                                                       class="btn btn-sm btn-outline-primary">
                                                        <i class="fas fa-edit"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger delete-banner"
                                                            data-id="<?= $banner['id'] ?>" data-title="<?= esc($banner['title']) ?>">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Status toggle functionality
    document.querySelectorAll('.status-toggle').forEach(function(toggle) {
        toggle.addEventListener('change', function() {
            const bannerId = this.dataset.id;
            const isActive = this.checked ? 1 : 0;

            fetch(`<?= base_url('admin/banners/') ?>${bannerId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                },
                body: JSON.stringify({ is_active: isActive })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Show success message
                    showAlert('success', 'Banner status updated successfully');
                } else {
                    this.checked = !this.checked; // Revert toggle
                    showAlert('error', 'Failed to update banner status');
                }
            })
            .catch(error => {
                this.checked = !this.checked; // Revert toggle
                showAlert('error', 'Error updating banner status');
            });
        });
    });

    // Delete functionality
    document.querySelectorAll('.delete-banner').forEach(function(button) {
        button.addEventListener('click', function() {
            const bannerId = this.dataset.id;
            const bannerTitle = this.dataset.title;

            if (confirm(`Are you sure you want to delete the banner "${bannerTitle}"?`)) {
                // Disable button during request
                this.disabled = true;
                const originalText = this.innerHTML;
                this.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

                fetch(`<?= base_url('admin/banners/') ?>${bannerId}`, {
                    method: 'DELETE',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': '<?= csrf_hash() ?>'
                    }
                })
                .then(response => {
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        showAlert('success', 'Banner deleted successfully');
                        // Remove the row from table
                        this.closest('tr').remove();
                    } else {
                        showAlert('error', 'Failed to delete banner: ' + (data.message || 'Unknown error'));
                        // Re-enable button
                        this.disabled = false;
                        this.innerHTML = originalText;
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    showAlert('error', 'Error deleting banner');
                    // Re-enable button
                    this.disabled = false;
                    this.innerHTML = originalText;
                });
            }
        });
    });
});
</script>

<?= $this->endSection() ?>
