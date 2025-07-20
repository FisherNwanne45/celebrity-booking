<?php
$pageTitle = "Dashboard";
require_once 'includes/admin-header.php';
require_once '../includes/functions.php';

// Get data for dashboard
$celebrities = getCelebrities($pdo);
$bookings = getBookings($pdo);
$paymentMethods = getPaymentMethods($pdo);
$contactInquiries = getContactInquiries($pdo, 5);
$totalInquiries = getTotalContactInquiries($pdo);
$unreadInquiries = getUnreadContactInquiries($pdo);

// Count pending bookings
$pendingBookings = array_filter($bookings, function ($booking) {
    return $booking['status'] === 'pending';
});
?>
<style>
.btn-sm.custom-small {
    font-size: 0.75rem;
    /* smaller font size */
    padding: 0.25rem 0.5rem;
}

/* adjust padding if needed */
.badge.custom {
    font-size: 0.75rem;
}
</style>
<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-2">
        <div class="stat-card primary">
            <div class="d-flex justify-content-between">
                <div>
                    <h6>Total Celebs</h6>
                    <h2 class="mb-0"><?= count($celebrities) ?></h2>
                </div>
                <div>
                    <i class="bi bi-people" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card success">
            <div class="d-flex justify-content-between">
                <div>
                    <h6>Total Bookings</h6>
                    <h2 class="mb-0"><?= count($bookings) ?></h2>
                </div>
                <div>
                    <i class="bi bi-calendar-check" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card warning">
            <div class="d-flex justify-content-between">
                <div>
                    <h6>Pending Bookings</h6>
                    <h2 class="mb-0"><?= count($pendingBookings) ?></h2>
                </div>
                <div>
                    <i class="bi bi-clock-history" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card info">
            <div class="d-flex justify-content-between">
                <div>
                    <h6>Payment Methods</h6>
                    <h2 class="mb-0"><?= count($paymentMethods) ?></h2>
                </div>
                <div>
                    <i class="bi bi-credit-card" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card secondary">
            <div class="d-flex justify-content-between">
                <div>
                    <h6>Total Inquiries</h6>
                    <h2 class="mb-0"><?= $totalInquiries ?></h2>
                </div>
                <div>
                    <i class="bi bi-envelope" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-2">
        <div class="stat-card <?= $unreadInquiries > 0 ? 'danger' : 'light' ?>">
            <div class="d-flex justify-content-between">
                <div>
                    <h6>Unread Inquiries</h6>
                    <h2 class="mb-0"><?= $unreadInquiries ?></h2>
                </div>
                <div>
                    <i class="bi bi-envelope-exclamation" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Bookings -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Bookings</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>

                                <th>Celebrity</th>
                                <th>Client</th>
                                <th>Event Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($bookings, 0, 5) as $booking): ?>
                            <tr>

                                <td><?= $booking['celebrity_name'] ?></td>
                                <td><?= $booking['user_name'] ?></td>
                                <td><?= date('M d, Y', strtotime($booking['event_date'])) ?></td>
                                <td>
                                    <span class="badge custom bg-<?=
                                                                        $booking['status'] === 'confirmed' ? 'success' : ($booking['status'] === 'pending' ? 'warning' : 'danger')
                                                                        ?>">
                                        <?= ucfirst($booking['status']) ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="bookings.php?action=view&id=<?= $booking['id'] ?>"
                                        class="btn btn-sm btn-primary custom-small">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-end">
                <a href="bookings.php" class="btn btn-sm btn-outline-primary custom-small">
                    View All Bookings
                </a>
            </div>
        </div>
    </div>

    <!-- Recent Contact Inquiries -->
    <div class="col-md-6 mb-4">
        <div class="card shadow-sm h-100">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">Recent Contact Inquiries</h5>
                    <?php if ($unreadInquiries > 0): ?>
                    <span class="badge bg-danger"><?= $unreadInquiries ?> unread</span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>

                                <th>Email</th>
                                <th>Date</th>
                                <th>Status</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($contactInquiries as $inquiry): ?>
                            <tr class="<?= $inquiry['is_read'] ? '' : 'table-warning' ?>">

                                <td><?= htmlspecialchars($inquiry['email']) ?></td>
                                <td><?= date('M d', strtotime($inquiry['created_at'])) ?></td>
                                <td>
                                    <span class="badge bg-<?= $inquiry['is_read'] ? 'success' : 'warning' ?>">
                                        <?= $inquiry['is_read'] ? 'Read' : 'Unread' ?>
                                    </span>
                                </td>
                                <td>
                                    <a href="inquiries-view.php?action=view&id=<?= $inquiry['id'] ?>"
                                        class="btn btn-sm btn-primary custom-small">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-end">
                <a href="inquiries.php" class="btn btn-sm btn-outline-primary custom-small">
                    View All Inquiries
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <!-- Recent Celebrities -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Recent Celebrities</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-hover">
                        <thead>
                            <tr>
                                <th>Name</th>
                                <th>Category</th>
                                <th>Fee</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach (array_slice($celebrities, 0, 5) as $celebrity): ?>
                            <tr>
                                <td>
                                    <div class="d-flex align-items-center">
                                        <?php if ($celebrity['picture']): ?>
                                        <img src="../stars/<?= $celebrity['picture'] ?>" alt="<?= $celebrity['name'] ?>"
                                            class="rounded-circle me-3" width="40" height="40">
                                        <?php else: ?>
                                        <div class="bg-secondary rounded-circle d-flex align-items-center justify-content-center me-3"
                                            style="width: 40px; height: 40px;">
                                            <i class="bi bi-person text-light"></i>
                                        </div>
                                        <?php endif; ?>
                                        <span><?= $celebrity['name'] ?></span>
                                    </div>
                                </td>
                                <td><?= $celebrity['category'] ?></td>
                                <td>$<?= number_format($celebrity['fee']) ?></td>
                                <td>
                                    <a href="celebrities.php?action=edit&id=<?= $celebrity['id'] ?>"
                                        class="btn btn-sm btn-outline-primary custom-small">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="card-footer bg-white text-end">
                <a href="celebrities.php" class="btn btn-sm btn-outline-primary custom-small">
                    View All Celebrities
                </a>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="col-md-6">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Quick Actions</h5>
            </div>
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <a href="celebrities.php?action=add"
                            class="card quick-action-card text-center text-decoration-none">
                            <div class="card-body">
                                <i class="bi bi-person-plus fs-1 text-primary"></i>
                                <h6 class="mt-2 mb-0">Add Celebrity</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="payments.php" class="card quick-action-card text-center text-decoration-none">
                            <div class="card-body">
                                <i class="bi bi-credit-card fs-1 text-success"></i>
                                <h6 class="mt-2 mb-0">Manage Payments</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="inquiries.php" class="card quick-action-card text-center text-decoration-none">
                            <div class="card-body">
                                <i class="bi bi-envelope fs-1 text-info"></i>
                                <h6 class="mt-2 mb-0">View Inquiries</h6>
                            </div>
                        </a>
                    </div>
                    <div class="col-md-6">
                        <a href="smtp-settings.php" class="card quick-action-card text-center text-decoration-none">
                            <div class="card-body">
                                <i class="bi bi-gear fs-1 text-warning"></i>
                                <h6 class="mt-2 mb-0">Email Settings</h6>
                            </div>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>