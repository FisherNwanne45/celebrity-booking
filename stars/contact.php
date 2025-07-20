<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $data = [
        'user_name' => $_POST['name'],
        'user_email' => $_POST['email'],
        'user_phone' => $_POST['phone'],
        'event_date' => $_POST['date'],
        'event_details' => $_POST['details']
    ];

    if (createBooking($pdo, $data)) {
        // Send email notification
        require_once '../includes/mailer.php';
        sendBookingConfirmation($data, $celebrity);

        header("Location: booking-success.php");
        exit;
    } else {
        $error = "Failed to create booking. Please try again.";
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
                    <h4 class="mb-0">Contact Us</h4>
                </div>
                <div class="card-body">
                    <?php if (isset($error)): ?>
                    <div class="alert alert-danger"><?= $error ?></div>
                    <?php endif; ?>

                    <form method="POST">
                        <div class="mb-3">
                            <label for="name" class="form-label">Your Full Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>

                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="date" class="form-label">Event Date</label>
                            <input type="date" class="form-control" id="date" name="date" required
                                min="<?= date('Y-m-d') ?>">
                        </div>

                        <div class="mb-3">
                            <label for="details" class="form-label">Event Details</label>
                            <textarea class="form-control" id="details" name="details" rows="4" required
                                placeholder="Tell us about your event and the artist you wish to book..."></textarea>
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
                    <h4 class="mb-0">Contact Information</h4>
                </div>
                <div class="card-body">
                    <div class="d-flex align-items-center mb-4">
                        <div class="flex-shrink-0">



                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                style="width: 80px; height: 80px;">
                                <i class="bi bi-mic-fill me-2 text-light" style="font-size: 2rem;"></i>
                            </div>

                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h5><?= $siteName ?></h5>
                            <span class="badge bg-primary"><i class="bi bi-envelope me-2"></i><?= $email ?>
                                </li>
                            </span>
                        </div>
                    </div>
                    <div class="mb-4">
                        <h5 class="mb-3">Frequently Asked Questions</h5>
                        <div class="accordion" id="faqAccordion">
                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqHeadingOne">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faqCollapseOne" aria-expanded="false"
                                        aria-controls="faqCollapseOne">
                                        How do I book a celebrity?
                                    </button>
                                </h2>
                                <div id="faqCollapseOne" class="accordion-collapse collapse"
                                    aria-labelledby="faqHeadingOne" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Simply click the “Book Now” button on the celebrity’s profile and complete the
                                        form. Our team will contact you shortly.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqHeadingTwo">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faqCollapseTwo" aria-expanded="false"
                                        aria-controls="faqCollapseTwo">
                                        Can I request a celebrity not listed?
                                    </button>
                                </h2>
                                <div id="faqCollapseTwo" class="accordion-collapse collapse"
                                    aria-labelledby="faqHeadingTwo" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Yes! Use the "Special Request" form and we’ll reach out to them on your behalf.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqHeadingThree">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faqCollapseThree" aria-expanded="false"
                                        aria-controls="faqCollapseThree">
                                        What does the booking fee cover?
                                    </button>
                                </h2>
                                <div id="faqCollapseThree" class="accordion-collapse collapse"
                                    aria-labelledby="faqHeadingThree" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        The fee covers the celebrity’s time, appearance, and any specific requirements
                                        discussed in the agreement.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqHeadingFour">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faqCollapseFour" aria-expanded="false"
                                        aria-controls="faqCollapseFour">
                                        Is international travel included?
                                    </button>
                                </h2>
                                <div id="faqCollapseFour" class="accordion-collapse collapse"
                                    aria-labelledby="faqHeadingFour" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        Most celebrities are available for international travel, but additional travel
                                        and accommodation fees may apply.
                                    </div>
                                </div>
                            </div>

                            <div class="accordion-item">
                                <h2 class="accordion-header" id="faqHeadingFive">
                                    <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                        data-bs-target="#faqCollapseFive" aria-expanded="false"
                                        aria-controls="faqCollapseFive">
                                        How far in advance should I book?
                                    </button>
                                </h2>
                                <div id="faqCollapseFive" class="accordion-collapse collapse"
                                    aria-labelledby="faqHeadingFive" data-bs-parent="#faqAccordion">
                                    <div class="accordion-body">
                                        We recommend booking at least 2–4 weeks in advance to secure availability and
                                        make arrangements.
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>