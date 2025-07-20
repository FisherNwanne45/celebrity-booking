<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';
require_once 'header.php';
$celebrities = getCelebrities($pdo);

?>


<!-- Success Message -->
<div class="container py-5 text-center" style="margin-top: 100px; min-height: 60vh;">
    <div class="card border-0 shadow-lg mx-auto" style="max-width: 600px;">
        <div class="card-body p-5">
            <div class="mb-4">
                <div class="bg-success text-white d-inline-flex align-items-center justify-content-center rounded-circle"
                    style="width: 100px; height: 100px;">
                    <i class="bi bi-check2" style="font-size: 3rem;"></i>
                </div>
            </div>
            <h1 class="mb-3">Booking Successful!</h1>
            <p class="lead mb-4">Thank you for your booking request. We've sent a confirmation email with
                further instructions.</p>

            <div class="card mb-4">
                <div class="card-body">
                    <h5 class="card-title">Next Steps</h5>
                    <ol class="text-start">
                        <li class="mb-2">Our team will review your request within 24 hours</li>
                        <li class="mb-2">We'll contact you to confirm availability and details</li>
                        <li>Payment instructions will be provided after confirmation</li>
                    </ol>
                </div>
            </div>

            <div class="d-flex justify-content-center gap-3">
                <a href="index.php" class="btn btn-primary btn-lg">
                    <i class="bi bi-house me-2"></i>Return Home
                </a>
                <a href="#" class="btn btn-outline-primary btn-lg">
                    <i class="bi bi-calendar me-2"></i>View My Bookings
                </a>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>