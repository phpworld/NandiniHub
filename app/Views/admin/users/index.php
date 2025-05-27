<?= $this->extend('admin/layout/main') ?>

<?= $this->section('breadcrumb') ?>
<nav aria-label="breadcrumb">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?= base_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item active" aria-current="page">
            <i class="fas fa-users me-2"></i>Users
        </li>
    </ol>
</nav>
<?= $this->endSection() ?>

<?= $this->section('content') ?>
<!-- Page Header -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="h3 mb-0">Users Management</h1>
        <p class="text-muted mb-0">Manage registered users and customers</p>
    </div>
    <div>
        <div class="dropdown">
            <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                <i class="fas fa-download me-2"></i>Export
            </button>
            <ul class="dropdown-menu">
                <li><a class="dropdown-item" href="#" onclick="exportUsers('csv')">Export as CSV</a></li>
                <li><a class="dropdown-item" href="#" onclick="exportUsers('excel')">Export as Excel</a></li>
            </ul>
        </div>
    </div>
</div>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-2">Total Users</h5>
                        <h2 class="text-primary mb-0"><?= count($users) ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-users fa-2x text-primary"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-2">Active Users</h5>
                        <h2 class="text-success mb-0"><?= count(array_filter($users, fn($u) => $u['is_active'])) ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-check fa-2x text-success"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-2">Customers</h5>
                        <h2 class="text-info mb-0"><?= count(array_filter($users, fn($u) => $u['role'] === 'customer')) ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-shopping-cart fa-2x text-info"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stats-card">
            <div class="card-body">
                <div class="d-flex justify-content-between">
                    <div>
                        <h5 class="card-title text-muted mb-2">Admins</h5>
                        <h2 class="text-warning mb-0"><?= count(array_filter($users, fn($u) => $u['role'] === 'admin')) ?></h2>
                    </div>
                    <div class="align-self-center">
                        <i class="fas fa-user-shield fa-2x text-warning"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Users Table -->
<div class="card">
    <div class="card-header d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Users List</h5>
        <div class="d-flex gap-2">
            <input type="text" class="form-control form-control-sm" placeholder="Search users..." 
                   id="userSearch" style="width: 200px;">
        </div>
    </div>
    <div class="card-body">
        <?php if (empty($users)): ?>
            <div class="text-center py-5">
                <i class="fas fa-users fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No users found</h5>
                <p class="text-muted">No registered users yet.</p>
            </div>
        <?php else: ?>
            <div class="table-responsive">
                <table class="table table-hover data-table" id="usersTable">
                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Role</th>
                            <th>Status</th>
                            <th>Joined</th>
                            <th width="120">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($users as $user): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <div class="avatar-circle me-3">
                                            <?= strtoupper(substr($user['first_name'], 0, 1) . substr($user['last_name'], 0, 1)) ?>
                                        </div>
                                        <div>
                                            <h6 class="mb-0"><?= esc($user['first_name'] . ' ' . $user['last_name']) ?></h6>
                                            <small class="text-muted">ID: <?= $user['id'] ?></small>
                                        </div>
                                    </div>
                                </td>
                                <td>
                                    <a href="mailto:<?= esc($user['email']) ?>" class="text-decoration-none">
                                        <?= esc($user['email']) ?>
                                    </a>
                                </td>
                                <td>
                                    <?php if (!empty($user['phone'])): ?>
                                        <a href="tel:<?= esc($user['phone']) ?>" class="text-decoration-none">
                                            <?= esc($user['phone']) ?>
                                        </a>
                                    <?php else: ?>
                                        <span class="text-muted">Not provided</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="badge bg-<?= $user['role'] === 'admin' ? 'danger' : 'primary' ?>">
                                        <?= ucfirst($user['role']) ?>
                                    </span>
                                </td>
                                <td>
                                    <?php if ($user['role'] !== 'admin'): ?>
                                        <div class="form-check form-switch">
                                            <input class="form-check-input status-toggle" type="checkbox" 
                                                   data-id="<?= $user['id'] ?>" 
                                                   <?= $user['is_active'] ? 'checked' : '' ?>>
                                        </div>
                                    <?php else: ?>
                                        <span class="badge bg-success">Active</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?= date('M j, Y', strtotime($user['created_at'])) ?>
                                </td>
                                <td>
                                    <div class="btn-group btn-group-sm">
                                        <a href="<?= base_url('admin/users/' . $user['id']) ?>" 
                                           class="btn btn-outline-primary" title="View Details">
                                            <i class="fas fa-eye"></i>
                                        </a>
                                        <?php if ($user['role'] !== 'admin'): ?>
                                            <button class="btn btn-outline-info" 
                                                    onclick="sendEmail(<?= $user['id'] ?>, '<?= esc($user['email']) ?>')" 
                                                    title="Send Email">
                                                <i class="fas fa-envelope"></i>
                                            </button>
                                        <?php endif; ?>
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

<?= $this->endSection() ?>

<?= $this->section('styles') ?>
<style>
    .avatar-circle {
        width: 40px;
        height: 40px;
        border-radius: 50%;
        background: var(--primary-color);
        color: white;
        display: flex;
        align-items: center;
        justify-content: center;
        font-weight: bold;
        font-size: 0.8rem;
    }
</style>
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    // Status toggle
    document.querySelectorAll('.status-toggle').forEach(toggle => {
        toggle.addEventListener('change', function() {
            const userId = this.dataset.id;
            const isActive = this.checked ? 1 : 0;
            
            fetch(`<?= base_url('admin/users/') ?>${userId}/toggle-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest'
                },
                body: JSON.stringify({ is_active: isActive })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    showAlert('success', 'User status updated successfully');
                } else {
                    // Revert toggle
                    this.checked = !this.checked;
                    showAlert('error', 'Failed to update user status');
                }
            })
            .catch(error => {
                // Revert toggle
                this.checked = !this.checked;
                showAlert('error', 'An error occurred');
            });
        });
    });

    // Search functionality
    document.getElementById('userSearch').addEventListener('input', function() {
        const searchTerm = this.value.toLowerCase();
        const table = document.getElementById('usersTable');
        const rows = table.getElementsByTagName('tbody')[0].getElementsByTagName('tr');
        
        for (let row of rows) {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(searchTerm) ? '' : 'none';
        }
    });

    // Send email
    function sendEmail(userId, email) {
        const subject = prompt('Enter email subject:');
        if (subject) {
            const message = prompt('Enter email message:');
            if (message) {
                // Here you would typically send an AJAX request to send the email
                showAlert('info', `Email would be sent to ${email}`);
            }
        }
    }

    // Export users
    function exportUsers(format) {
        window.open(`<?= base_url('admin/users/export') ?>?format=${format}`, '_blank');
    }

    // Show alert function
    function showAlert(type, message) {
        const alertDiv = document.createElement('div');
        alertDiv.className = `alert alert-${type} alert-dismissible fade show`;
        alertDiv.innerHTML = `
            ${message}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        `;
        
        const container = document.querySelector('.content-wrapper');
        container.insertBefore(alertDiv, container.firstChild);
        
        setTimeout(() => {
            alertDiv.remove();
        }, 5000);
    }
</script>
<?= $this->endSection() ?>
