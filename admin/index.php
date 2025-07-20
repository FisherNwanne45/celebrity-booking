<?php
$pageTitle = "Dashboard";
require_once 'includes/admin-header.php';
require_once '../includes/functions.php';

// Get data for dashboard
$celebrities = getCelebrities($pdo);
$bookings = getBookings($pdo);
$paymentMethods = getPaymentMethods($pdo);

// Count pending bookings
$pendingBookings = array_filter($bookings, function ($booking) {
    return $booking['status'] === 'pending';
});
?>

<!-- Stats Cards -->
<div class="row mb-4">
    <div class="col-md-3">
        <div class="stat-card primary">
            <div class="d-flex justify-content-between">
                <div>
                    <h5>Celebrities</h5>
                    <h2 class="mb-0"><?= count($celebrities) ?></h2>
                </div>
                <div>
                    <i class="bi bi-people" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card success">
            <div class="d-flex justify-content-between">
                <div>
                    <h5>Total Bookings</h5>
                    <h2 class="mb-0"><?= count($bookings) ?></h2>
                </div>
                <div>
                    <i class="bi bi-calendar-check" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card warning">
            <div class="d-flex justify-content-between">
                <div>
                    <h5>Pending Bookings</h5>
                    <h2 class="mb-0"><?= count($pendingBookings) ?></h2>
                </div>
                <div>
                    <i class="bi bi-clock-history" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="stat-card danger">
            <div class="d-flex justify-content-between">
                <div>
                    <h5>Payment Methods</h5>
                    <h2 class="mb-0"><?= count($paymentMethods) ?></h2>
                </div>
                <div>
                    <i class="bi bi-credit-card" style="font-size: 2.5rem;"></i>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Recent Bookings -->
<div class="card shadow-sm mb-4">
    <div class="card-header bg-white">
        <h5 class="mb-0">Recent Bookings</h5>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>ID</th>
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
                            <td><?= $booking['id'] ?></td>
                            <td><?= $booking['celebrity_name'] ?></td>
                            <td><?= $booking['user_name'] ?></td>
                            <td><?= date('M d, Y', strtotime($booking['event_date'])) ?></td>
                            <td>
                                <span class="badge bg-<?=
                                                        $booking['status'] === 'confirmed' ? 'success' : ($booking['status'] === 'pending' ? 'warning' : 'danger')
                                                        ?>">
                                    <?= ucfirst($booking['status']) ?>
                                </span>
                            </td>
                            <td>
                                <a href="bookings.php?action=view&id=<?= $booking['id'] ?>" class="btn btn-sm btn-primary">
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
        <a href="bookings.php" class="btn btn-sm btn-outline-primary">
            View All Bookings
        </a>
    </div>
</div>

<!-- Recent Celebrities -->
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
                                    class="btn btn-sm btn-outline-primary">
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
        <a href="celebrities.php" class="btn btn-sm btn-outline-primary">
            View All Celebrities
        </a>
    </div>
</div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>