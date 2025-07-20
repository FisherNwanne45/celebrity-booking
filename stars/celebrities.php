<?php
require_once '../includes/db.php';
require_once '../includes/functions.php';

$id = $_GET['id'] ?? 0;
$celebrity = getCelebrityById($pdo, $id);

if (!$celebrity) {
    header("Location: index.php");
    exit;
}
require_once 'header.php';
?>


<!-- Celebrity Details -->
<div class="container py-5" style="margin-top: 80px;">
    <div class="row">
        <div class="col-md-6 mb-4">
            <?php if ($celebrity['picture']): ?>
            <img src="<?= $celebrity['picture'] ?>" class="img-fluid rounded shadow" alt="<?= $celebrity['name'] ?>">
            <?php else: ?>
            <div class="bg-secondary d-flex align-items-center justify-content-center rounded" style="height: 500px;">
                <i class="bi bi-person-circle text-light" style="font-size: 5rem;"></i>
            </div>
            <?php endif; ?>
        </div>
        <div class="col-md-6">
            <div class="d-flex justify-content-between align-items-start mb-3">
                <div>
                    <h1 class="fw-bold"><?= $celebrity['name'] ?></h1>
                    <span class="badge bg-primary fs-6"><?= $celebrity['category'] ?></span>
                </div>
                <div class="position-relative d-inline-block">
                    <span class="position-absolute text-danger fw-semibold"
                        style="top: -1.2rem; left: 0.2rem; font-size: 0.75rem;">
                        Meet and Greet Fee
                    </span>
                    <h2 class="text-primary fw-bold mb-0 ps-3" style="font-size: 2.2rem;">
                        $<?= number_format($celebrity['fee']) ?>
                    </h2>
                </div>


            </div>

            <div class="mb-4">
                <h4 class="border-bottom pb-2">Profile</h4>
                <p class="lead"><?= nl2br($celebrity['description']) ?></p>
                <p class="text-muted ">
                    <?= htmlspecialchars(substr($celebrity['profile'], 0, 100)) ?>...<a href="#" data-bs-toggle="modal"
                        data-bs-target="#profileModal"><b>read more</b></a>

                </p>

            </div>

            <!-- Profile Modal -->
            <div class="modal fade" id="profileModal" tabindex="-1" aria-labelledby="profileModalLabel"
                aria-hidden="true">
                <div class="modal-dialog modal-lg modal-dialog-centered">
                    <div class="modal-content">
                        <div class="modal-header bg-primary text-white">
                            <h5 class="modal-title" id="profileModalLabel"><?= htmlspecialchars($celebrity['name']) ?> -
                                Full Profile</h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Close"></button>
                        </div>
                        <div class="modal-body">
                            <?= nl2br($celebrity['profile']) ?>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Booking Information</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Availability:</span>
                            <span class="text-success">Accepting Bookings</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Minimum Booking:</span>
                            <span>1 Day</span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Travel:</span>
                            <span>Worldwide</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="d-grid">
                <a href="booking.php?id=<?= $celebrity['id'] ?>" class="btn btn-primary btn-lg">
                    <i class="bi bi-calendar-plus me-2"></i>Book Now
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Similar Celebrities -->
<section class="py-5 bg-light">
    <div class="container">
        <h3 class="mb-4">Similar Celebrities</h3>
        <div class="row">
            <?php
            $celebrities = getCelebrities($pdo);
            $similar = array_filter($celebrities, function ($c) use ($celebrity) {
                return $c['id'] != $celebrity['id'] && $c['category'] == $celebrity['category'];
            });

            // Limit to 3 similar celebrities
            $similar = array_slice($similar, 0, 3);

            foreach ($similar as $sim): ?>
            <div class="col-md-4 mb-4">
                <div class="card h-100">
                    <img src="<?= $sim['picture'] ?>" class="card-img-top" alt="<?= $sim['name'] ?>"
                        style="height: 200px; object-fit: cover;">
                    <div class="card-body">
                        <h5 class="card-title"><?= $sim['name'] ?></h5>
                        <span class="badge bg-primary"><?= $sim['category'] ?></span>
                        <p class="card-text mt-2"><?= substr($sim['profile'], 0, 80) ?>...</p>
                    </div>
                    <div class="card-footer bg-white d-flex justify-content-between">
                        <a href="celebrities.php?id=<?= $sim['id'] ?>" class="btn btn-sm btn-outline-primary">
                            View Details
                        </a>
                        <a href="booking.php?id=<?= $sim['id'] ?>" class="btn btn-sm btn-primary">
                            Book Now
                        </a>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<?php include 'footer.php'; ?>