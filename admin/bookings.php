<?php
$pageTitle = "Manage Bookings";
require_once '../includes/functions.php';

// Handle status update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $id = $_POST['id'];
    $status = $_POST['status'];
    if (updateBookingStatus($pdo, $id, $status)) {
        $_SESSION['success'] = "Booking status updated successfully!";
    } else {
        $_SESSION['error'] = "Error updating booking status";
    }
    header("Location: bookings.php");
    exit;
}

// Handle delete booking
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_booking'])) {
    $id = (int)$_POST['id'];
    $stmt = $pdo->prepare("DELETE FROM bookings WHERE id = ?");
    if ($stmt->execute([$id])) {
        $_SESSION['success'] = "Booking deleted successfully";
    } else {
        $_SESSION['error'] = "Error deleting booking.";
    }
    header("Location: bookings.php");
    exit;
}

require_once 'includes/admin-header.php';
$bookings = getBookings($pdo);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Manage Bookings</h4>
    </div>
</div>

<?php if (isset($_SESSION['success'])): ?>
<div class="alert alert-success"><?= $_SESSION['success'] ?></div>
<?php unset($_SESSION['success']); ?>
<?php endif; ?>

<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Celebrity</th>
                        <th>Client</th>
                        <th>Event Date</th>
                        <th>Fan Card</th>
                        <th>Merchandise</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($bookings as $booking):
                        $merchandise = json_decode($booking['merchandise'] ?? '[]', true);
                        $merchDisplay = [];
                        foreach ($merchandise as $item => $quantity) {
                            if ($quantity > 0) {
                                $merchDisplay[] = "$item: $quantity";
                            }
                        }
                    ?>
                    <tr>
                        <td><?= htmlspecialchars($booking['celebrity_name']) ?></td>
                        <td>
                            <div><?= htmlspecialchars($booking['user_name']) ?></div>
                            <small><?= htmlspecialchars($booking['user_email']) ?></small>
                        </td>
                        <td><?= date('M d, Y', strtotime($booking['event_date'])) ?></td>
                        <td>
                            <div><?= htmlspecialchars($booking['fan_card']) ?></div>
                            <small><?= htmlspecialchars($booking['pay']) ?></small>
                        </td>
                        <td>
                            <?php if (!empty($merchDisplay)): ?>
                            <ul class="list-unstyled mb-0">
                                <?php foreach ($merchDisplay as $item): ?>
                                <li><?= htmlspecialchars($item) ?></li>
                                <?php endforeach; ?>
                            </ul>
                            <?php else: ?>
                            <span class="text-muted">None</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <span class="badge bg-<?=
                                                        $booking['status'] === 'confirmed' ? 'success' : ($booking['status'] === 'pending' ? 'warning' : 'danger')
                                                        ?>">
                                <?= ucfirst($booking['status']) ?>
                            </span>
                        </td>
                        <style>
                        .btn.custom {
                            font-size: 0.75rem;
                            /* smaller font size */
                            padding: 0.2rem 0.4rem;
                        }

                        .btn-sm {
                            font-size: 0.75rem;
                            /* smaller font size */
                            padding: 0.2rem 0.4rem;
                        }
                        </style>
                        <td>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-info" data-bs-toggle="modal"
                                    data-bs-target="#viewBookingModal<?= $booking['id'] ?>">
                                    <i class="bi bi-eye"></i> View
                                </button>

                                <button class="btn btn-sm btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#editBookingModal<?= $booking['id'] ?>">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>

                                <form method="POST" class="btn btn-sm btn-outline-danger"
                                    onsubmit="return confirm('Are you sure you want to delete this booking?');">
                                    <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                                    <button type="submit" name="delete_booking" class="btn custom">
                                        <i class="bi bi-trash"></i> Delete
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                    <!-- Edit Booking Modal -->
                    <div class="modal fade" id="editBookingModal<?= $booking['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Update Booking Status</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <form method="POST">
                                    <div class="modal-body">
                                        <div class="mb-3">
                                            <label class="form-label">Booking ID: <?= $booking['id'] ?></label>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Client:
                                                <?= htmlspecialchars($booking['user_name']) ?></label>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Fan Card Type:
                                                <?= htmlspecialchars($booking['fan_card']) ?></label>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Payment Method:
                                                <?= htmlspecialchars($booking['pay']) ?></label>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Event Date:
                                                <?= date('M d, Y', strtotime($booking['event_date'])) ?></label>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Merchandise:</label>
                                            <?php if (!empty($merchDisplay)): ?>
                                            <ul>
                                                <?php foreach ($merchDisplay as $item): ?>
                                                <li><?= htmlspecialchars($item) ?></li>
                                                <?php endforeach; ?>
                                            </ul>
                                            <?php else: ?>
                                            <span class="text-muted">None</span>
                                            <?php endif; ?>
                                        </div>
                                        <div class="mb-3">
                                            <label class="form-label">Status</label>
                                            <select class="form-select" name="status">
                                                <option value="pending"
                                                    <?= $booking['status'] === 'pending' ? 'selected' : '' ?>>Pending
                                                </option>
                                                <option value="confirmed"
                                                    <?= $booking['status'] === 'confirmed' ? 'selected' : '' ?>>
                                                    Confirmed</option>
                                                <option value="cancelled"
                                                    <?= $booking['status'] === 'cancelled' ? 'selected' : '' ?>>
                                                    Cancelled</option>
                                            </select>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <input type="hidden" name="id" value="<?= $booking['id'] ?>">
                                        <button type="button" class="btn btn-secondary"
                                            data-bs-dismiss="modal">Cancel</button>
                                        <button type="submit" name="update_status" class="btn btn-primary">Update
                                            Status</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- View Booking Modal -->
                    <div class="modal fade" id="viewBookingModal<?= $booking['id'] ?>" tabindex="-1">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title">Booking Details</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <strong>Booking ID:</strong> <?= $booking['id'] ?>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Celebrity:</strong> <?= htmlspecialchars($booking['celebrity_name']) ?>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Client:</strong> <?= htmlspecialchars($booking['user_name']) ?>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Email:</strong> <?= htmlspecialchars($booking['user_email']) ?>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Phone:</strong> <?= htmlspecialchars($booking['user_phone'] ?? 'N/A') ?>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Fan Card Type:</strong> <?= htmlspecialchars($booking['fan_card']) ?>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Payment Method:</strong> <?= htmlspecialchars($booking['pay']) ?>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Event Date:</strong>
                                        <?= date('M d, Y', strtotime($booking['event_date'])) ?>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Merchandise:</strong>
                                        <?php if (!empty($merchDisplay)): ?>
                                        <ul>
                                            <?php foreach ($merchDisplay as $item): ?>
                                            <li><?= htmlspecialchars($item) ?></li>
                                            <?php endforeach; ?>
                                        </ul>
                                        <?php else: ?>
                                        <span class="text-muted">None</span>
                                        <?php endif; ?>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Details:</strong> <?= htmlspecialchars($booking['event_details']) ?>
                                    </div>
                                    <div class="mb-3">
                                        <strong>Status:</strong>
                                        <span class="badge bg-<?=
                                                                    $booking['status'] === 'confirmed' ? 'success' : ($booking['status'] === 'pending' ? 'warning' : 'danger')
                                                                    ?>">
                                            <?= ucfirst($booking['status']) ?>
                                        </span>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary"
                                        data-bs-dismiss="modal">Close</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>