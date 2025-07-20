<?php
$pageTitle = "Manage Payment Methods";
require_once 'includes/admin-header.php';
require_once '../includes/functions.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_payment_method'])) {
        $data = [
            'name' => $_POST['name'],
            'details' => $_POST['details'],
            'instructions' => $_POST['instructions']
        ];
        if (createPaymentMethod($pdo, $data)) {
            $_SESSION['success'] = "Payment method added successfully!";
        } else {
            $_SESSION['error'] = "Error adding payment method";
        }
    }
}

// Get all payment methods
$paymentMethods = getPaymentMethods($pdo);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Manage Payment Methods</h4>
    </div>
    <div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addPaymentModal">
            <i class="bi bi-plus-circle me-2"></i>Add Payment Method
        </button>
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

<!-- Payment Methods Table -->
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Details</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($paymentMethods as $method): ?>
                        <tr>
                            <td><?= $method['name'] ?></td>
                            <td><?= nl2br($method['details']) ?></td>
                            <td>
                                <span class="badge bg-success">Active</span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="payments-edit.php?id=<?= $method['id'] ?>"
                                        class="btn btn-sm btn-outline-primary">
                                        <i class="bi bi-pencil"></i> Edit
                                    </a>
                                    <a href="payments-delete.php?id=<?= $method['id'] ?>"
                                        class="btn btn-sm btn-outline-danger">
                                        <i class="bi bi-trash"></i> Delete
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Payment Method Modal -->
<div class="modal fade" id="addPaymentModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Payment Method</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">Payment Method Name</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    <div class="mb-3">
                        <label for="details" class="form-label">Payment Details</label>
                        <textarea class="form-control" id="details" name="details" rows="3" required></textarea>
                        <small class="form-text text-muted">Bank account details, PayPal email, etc.</small>
                    </div>
                    <div class="mb-3">
                        <label for="instructions" class="form-label">Payment Instructions</label>
                        <textarea class="form-control" id="instructions" name="instructions" rows="3"></textarea>
                        <small class="form-text text-muted">Instructions for clients on how to make payment</small>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_payment_method" class="btn btn-primary">Add Payment Method</button>
                </div>
            </form>
        </div>
    </div>
</div>
</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>