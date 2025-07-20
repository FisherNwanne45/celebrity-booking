<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

// Generate CSRF token
if (empty($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}
$csrf_token = $_SESSION['csrf_token'];

$id = $_GET['id'] ?? 0;
$celebrity = getCelebrityById($pdo, $id);

if (!$celebrity) {
    header("Location: index.php");
    exit;
}

$paymentMethods = getPaymentMethods($pdo);

// Initialize variables
$error = '';
$formData = [
    'name' => '',
    'email' => '',
    'phone' => '',
    'date' => '',
    'details' => ''
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || $_POST['csrf_token'] !== $_SESSION['csrf_token']) {
        $error = "Invalid form submission. Please try again.";
    } else {
        // Basic validation
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $phone = trim($_POST['phone'] ?? '');
        $date = trim($_POST['date'] ?? '');
        $details = trim($_POST['details'] ?? '');

        $errors = [];

        if (empty($name)) $errors[] = "Name is required";
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = "Valid email is required";
        if (empty($phone)) $errors[] = "Phone number is required";
        if (empty($date) || strtotime($date) < time()) $errors[] = "Valid future date is required";
        if (empty($details)) $errors[] = "Event details are required";

        if (empty($errors)) {
            $data = [
                'celebrity_id' => $id,
                'user_name' => htmlspecialchars($name),
                'user_email' => htmlspecialchars($email),
                'user_phone' => htmlspecialchars($phone),
                'event_date' => htmlspecialchars($date),
                'event_details' => htmlspecialchars($details)
            ];

            if (createBooking($pdo, $data)) {
                // Send email notification
                require_once '../includes/mailer.php';
                if (sendBookingConfirmation($pdo, $data, $celebrity)) {
                    header("Location: booking-success.php");
                    exit;
                } else {
                    $error = "Booking created, but failed to send confirmation email.";
                }
            } else {
                $error = "Failed to create booking. Please try again.";
            }
        } else {
            $error = implode("<br>", $errors);
            // Keep form data for repopulation
            $formData = [
                'name' => htmlspecialchars($name),
                'email' => htmlspecialchars($email),
                'phone' => htmlspecialchars($phone),
                'date' => htmlspecialchars($date),
                'details' => htmlspecialchars($details)
            ];
        }
    }
}
require_once 'header.php';
?>

<!-- Booking Form -->
<div class="container py-5" style="margin-top: 80px;">
    <div class="row">
        <div class="col-md-7 mb-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Book <?= htmlspecialchars($celebrity['name']) ?></h4>
                </div>
                <div class="card-body">
                    <?php if ($error): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <input type="hidden" name="csrf_token" value="<?= $csrf_token ?>">

                        <div class="mb-3">
                            <label for="name" class="form-label">Your Full Name</label>
                            <input type="text" class="form-control" id="name" name="name"
                                value="<?= $formData['name'] ?>" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email"
                                    value="<?= $formData['email'] ?>" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone"
                                    value="<?= $formData['phone'] ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Event Date</label>
                            <input type="date" class="form-control" id="date" name="date"
                                value="<?= $formData['date'] ?>" required
                                min="<?= date('Y-m-d', strtotime('+1 day')) ?>">
                        </div>

                        <div class="mb-3">
                            <label for="details" class="form-label">Event Details</label>
                            <textarea class="form-control" id="details" name="details" rows="6" required
                                placeholder="Tell us about your event including your budget, location, nearest airport and any relevant information..."><?= $formData['details'] ?></textarea>
                        </div>

                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="bi bi-check-circle me-2"></i>Submit Booking Request
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-md-5">
            <div class="card shadow sticky-top" style="top: 90px;">
                <div class="card-header bg-info text-white">
                    <h4 class="mb-0">Booking Summary</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-shrink-0">
                            <?php if ($celebrity['picture']): ?>
                            <img src="<?= htmlspecialchars($celebrity['picture']) ?>"
                                alt="<?= htmlspecialchars($celebrity['name']) ?>" class="rounded" width="80">
                            <?php else: ?>
                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-person text-light" style="font-size: 2rem;"></i>
                            </div>
                            <?php endif; ?>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5><?= htmlspecialchars($celebrity['name']) ?></h5>
                            <span class="badge bg-primary"><?= htmlspecialchars($celebrity['category']) ?></span>
                        </div>
                    </div>

                    <div class="mb-4">
                        <h5>Fee Details</h5>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Meet and Greet Fee:</span>
                                <span class="fw-bold">$<?= number_format($celebrity['fee'], 2) ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Service Fee:</span>
                                <span>$<?= number_format($celebrity['fee'] * 0.10, 2) ?></span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between">
                                <span>Estimated Total:</span>
                                <span class="fw-bold">$<?= number_format($celebrity['fee'] * 1.10, 2) ?></span>
                            </li>
                        </ul>
                    </div>

                    <div class="mb-4">
                        <h5>Payment Options</h5>
                        <p>After booking, we'll contact you with payment instructions using one of these methods:</p>
                        <ul class="list-group">
                            <li class="list-group-item">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod" id="None" checked>
                                    <label class="form-check-label" for="None">
                                        No meet and greet
                                    </label>
                                </div>
                            </li>
                            <?php foreach ($paymentMethods as $method): ?>
                            <li class="list-group-item">
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="paymentMethod"
                                        id="method<?= $method['id'] ?>">
                                    <label class="form-check-label" for="method<?= $method['id'] ?>">
                                        <?= htmlspecialchars($method['name']) ?>
                                    </label>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>