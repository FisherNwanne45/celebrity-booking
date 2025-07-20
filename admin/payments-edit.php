<?php
$pageTitle = "Edit Payment Method";
require_once '../includes/functions.php';

// Get Payment ID
$id = $_GET['id'] ?? 0;
if (!$id) {
    header("Location: payments.php");
    exit;
}

// Fetch specific payment method data
$sql = "SELECT * FROM payment_methods WHERE id = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$id]);
$paymentMethod = $stmt->fetch(PDO::FETCH_ASSOC);

if (!$paymentMethod) {
    $_SESSION['error'] = "Payment method not found";
    header("Location: payments.php");
    exit;
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $details = $_POST['details'] ?? '';
    $instructions = $_POST['instructions'] ?? '';

    // Update payment method in database
    $sql = "UPDATE payment_methods SET 
                name = ?,
                details = ?,
                instructions = ? 
            WHERE id = ?";

    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([$name, $details, $instructions, $id]);

    if ($success) {
        $_SESSION['success'] = "Payment method updated successfully!";
        header("Location: payments.php");
        exit;
    } else {
        $_SESSION['error'] = "Error updating payment method: " . implode(" ", $stmt->errorInfo());
    }
}
require_once 'includes/admin-header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Edit Payment Method</h4>
    </div>
    <div>
        <a href="payments.php" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-2"></i>Back to Payment Methods
        </a>
    </div>
</div>

<?php if (isset($_SESSION['error'])): ?>
<div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
<?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Payment Method Name</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="<?= htmlspecialchars($paymentMethod['name']) ?>" required>
                </div>
            </div>

            <div class="mb-3">
                <label for="details" class="form-label">Payment Details</label>
                <textarea class="form-control" id="details" name="details" rows="3"
                    required><?= htmlspecialchars($paymentMethod['details']) ?></textarea>
                <small class="form-text text-muted">Bank account details, PayPal email, etc.</small>
            </div>
            <div class="mb-3">
                <label for="instructions" class="form-label">Payment Instructions</label>
                <textarea class="form-control" id="instructions" name="instructions" rows="3"
                    required><?= htmlspecialchars($paymentMethod['instructions']) ?></textarea>
                <small class="form-text text-muted">Instructions for clients on how to make payment</small>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle me-2"></i>Update Payment Method
                </button>
            </div>
        </form>
    </div>
</div>