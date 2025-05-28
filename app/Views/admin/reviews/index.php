<?= $this->extend('admin/layout/main') ?>

<?= $this->section('content') ?>

<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Manage Reviews</h1>
                <div>
                    <span class="badge bg-warning me-2"><?= $pendingCount ?> Pending</span>
                    <span class="badge bg-success"><?= $allReviewsCount ?> Total</span>
                </div>
            </div>

            <!-- Filter Tabs -->
            <div class="card mb-4">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-md-8">
                            <ul class="nav nav-pills">
                                <li class="nav-item">
                                    <a class="nav-link <?= empty($currentStatus) || $currentStatus === 'all' ? 'active' : '' ?>"
                                        href="<?= base_url('admin/reviews?status=all') ?>">
                                        All Reviews (<?= $allReviewsCount ?>)
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= $currentStatus === 'pending' ? 'active' : '' ?>"
                                        href="<?= base_url('admin/reviews?status=pending') ?>">
                                        Pending (<?= $pendingCount ?>)
                                    </a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link <?= $currentStatus === 'approved' ? 'active' : '' ?>"
                                        href="<?= base_url('admin/reviews?status=approved') ?>">
                                        Approved (<?= $approvedCount ?>)
                                    </a>
                                </li>
                            </ul>
                        </div>
                        <div class="col-md-4">
                            <form method="get" action="<?= base_url('admin/reviews') ?>" class="d-flex">
                                <?php if ($currentStatus): ?>
                                    <input type="hidden" name="status" value="<?= esc($currentStatus) ?>">
                                <?php endif; ?>
                                <input type="text"
                                    class="form-control form-control-sm me-2"
                                    name="search"
                                    placeholder="Search reviews..."
                                    value="<?= esc($currentSearch ?? '') ?>">
                                <button type="submit" class="btn btn-outline-primary btn-sm">
                                    <i class="fas fa-search"></i>
                                </button>
                                <?php if ($currentSearch): ?>
                                    <a href="<?= base_url('admin/reviews' . ($currentStatus ? '?status=' . $currentStatus : '')) ?>"
                                        class="btn btn-outline-secondary btn-sm ms-1"
                                        title="Clear search">
                                        <i class="fas fa-times"></i>
                                    </a>
                                <?php endif; ?>
                            </form>
                        </div>
                    </div>

                    <?php if ($currentSearch): ?>
                        <div class="mt-3">
                            <small class="text-muted">
                                <i class="fas fa-search"></i> Search results for: <strong>"<?= esc($currentSearch) ?>"</strong>
                                (<?= $totalReviews ?> found)
                            </small>
                        </div>
                    <?php endif; ?>
                </div>
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

            <!-- Pending Reviews Section (only show when not filtering or when filtering pending) -->
            <?php if (!empty($pendingReviews) && (empty($currentStatus) || $currentStatus === 'all' || $currentStatus === 'pending')): ?>
                <div class="card mb-4">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="fas fa-clock text-warning"></i> Pending Reviews
                        </h5>
                    </div>
                    <div class="card-body">
                        <?php foreach ($pendingReviews as $review): ?>
                            <div class="border-bottom pb-3 mb-3">
                                <div class="row">
                                    <div class="col-md-8">
                                        <h6 class="mb-1"><?= esc($review['product_name']) ?></h6>
                                        <div class="text-warning mb-2">
                                            <?php for ($i = 1; $i <= 5; $i++): ?>
                                                <?php if ($i <= $review['rating']): ?>
                                                    <i class="fas fa-star"></i>
                                                <?php else: ?>
                                                    <i class="far fa-star"></i>
                                                <?php endif; ?>
                                            <?php endfor; ?>
                                            <span class="ms-1">(<?= $review['rating'] ?>/5)</span>
                                        </div>
                                        <?php if ($review['title']): ?>
                                            <h6 class="mb-2"><?= esc($review['title']) ?></h6>
                                        <?php endif; ?>
                                        <?php if ($review['review']): ?>
                                            <p class="mb-2"><?= esc($review['review']) ?></p>
                                        <?php endif; ?>
                                        <small class="text-muted">
                                            By <?= esc($review['first_name'] . ' ' . $review['last_name']) ?>
                                            on <?= date('M j, Y', strtotime($review['created_at'])) ?>
                                        </small>
                                    </div>
                                    <div class="col-md-4 text-end">
                                        <form method="post" action="<?= base_url('admin/reviews/' . $review['id'] . '/approve') ?>" class="d-inline">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                <i class="fas fa-check"></i> Approve
                                            </button>
                                        </form>
                                        <form method="post" action="<?= base_url('admin/reviews/' . $review['id'] . '/reject') ?>" class="d-inline">
                                            <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Are you sure you want to reject and delete this review? This action cannot be undone.')">
                                                <i class="fas fa-times"></i> Reject & Delete
                                            </button>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- All Reviews Section -->
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5 class="card-title mb-0">
                        <i class="fas fa-star text-success"></i>
                        <?php if ($currentStatus === 'pending'): ?>
                            Pending Reviews
                        <?php elseif ($currentStatus === 'approved'): ?>
                            Approved Reviews
                        <?php else: ?>
                            All Reviews
                        <?php endif; ?>
                    </h5>
                    <div class="text-muted">
                        Showing <?= count($reviews) ?> of <?= $totalReviews ?> reviews
                    </div>
                </div>
                <div class="card-body">
                    <?php if (!empty($reviews)): ?>
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead>
                                    <tr>
                                        <th>Product</th>
                                        <th>Customer</th>
                                        <th>Rating</th>
                                        <th>Review</th>
                                        <th>Date</th>
                                        <th>Status</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($reviews as $review): ?>
                                        <tr>
                                            <td>
                                                <strong><?= esc($review['product_name']) ?></strong>
                                            </td>
                                            <td>
                                                <?= esc($review['first_name'] . ' ' . $review['last_name']) ?>
                                            </td>
                                            <td>
                                                <div class="text-warning">
                                                    <?php for ($i = 1; $i <= 5; $i++): ?>
                                                        <?php if ($i <= $review['rating']): ?>
                                                            <i class="fas fa-star"></i>
                                                        <?php else: ?>
                                                            <i class="far fa-star"></i>
                                                        <?php endif; ?>
                                                    <?php endfor; ?>
                                                </div>
                                                <small>(<?= $review['rating'] ?>/5)</small>
                                            </td>
                                            <td>
                                                <?php if ($review['title']): ?>
                                                    <strong><?= esc($review['title']) ?></strong><br>
                                                <?php endif; ?>
                                                <?php if ($review['review']): ?>
                                                    <small><?= esc(substr($review['review'], 0, 100)) ?><?= strlen($review['review']) > 100 ? '...' : '' ?></small>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <small><?= date('M j, Y', strtotime($review['created_at'])) ?></small>
                                            </td>
                                            <td>
                                                <?php if ($review['is_approved']): ?>
                                                    <span class="badge bg-success">Approved</span>
                                                <?php else: ?>
                                                    <span class="badge bg-warning">Pending</span>
                                                <?php endif; ?>
                                                <?php if ($review['is_verified']): ?>
                                                    <span class="badge bg-info">Verified</span>
                                                <?php endif; ?>
                                            </td>
                                            <td>
                                                <?php if (!$review['is_approved']): ?>
                                                    <form method="post" action="<?= base_url('admin/reviews/' . $review['id'] . '/approve') ?>" class="d-inline">
                                                        <button type="submit" class="btn btn-success btn-xs" title="Approve Review">
                                                            <i class="fas fa-check"></i>
                                                        </button>
                                                    </form>
                                                <?php endif; ?>
                                                <button type="button" class="btn btn-danger btn-xs" onclick="deleteReview(<?= $review['id'] ?>)" title="Delete Review">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>

                        <!-- Pagination -->
                        <?php if ($pager->getPageCount() > 1): ?>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <div class="text-muted">
                                    Showing <?= ($currentPage - 1) * $perPage + 1 ?> to <?= min($currentPage * $perPage, $totalReviews) ?> of <?= $totalReviews ?> reviews
                                </div>
                                <div>
                                    <?= $pager->links() ?>
                                </div>
                            </div>
                        <?php endif; ?>
                    <?php else: ?>
                        <div class="text-center py-4">
                            <i class="fas fa-star fa-3x text-muted mb-3"></i>
                            <h5 class="text-muted">
                                <?php if ($currentStatus === 'pending'): ?>
                                    No pending reviews
                                <?php elseif ($currentStatus === 'approved'): ?>
                                    No approved reviews
                                <?php else: ?>
                                    No reviews yet
                                <?php endif; ?>
                            </h5>
                            <p class="text-muted">
                                <?php if ($currentStatus === 'pending'): ?>
                                    All reviews have been processed.
                                <?php elseif ($currentStatus === 'approved'): ?>
                                    No reviews have been approved yet.
                                <?php else: ?>
                                    Reviews will appear here once customers start reviewing products.
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function deleteReview(reviewId) {
        if (confirm('Are you sure you want to delete this review? This action cannot be undone.')) {
            fetch(`<?= base_url('admin/reviews/') ?>${reviewId}`, {
                    method: 'DELETE',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the page to show updated list
                        location.reload();
                    } else {
                        alert('Failed to delete review: ' + (data.message || 'Unknown error'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while deleting the review.');
                });
        }
    }
</script>

<?= $this->endSection() ?>