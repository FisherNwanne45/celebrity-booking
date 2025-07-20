<?php
$pageTitle = "View Inquiry";
require_once 'includes/admin-header.php';
require_once '../includes/functions.php';

$id = $_GET['id'] ?? 0;
if (!$id) {
    header("Location: inquiries.php");
    exit;
}

// Get inquiry details
$stmt = $pdo->prepare("SELECT * FROM contact_inquiries WHERE id = ?");
$stmt->execute([$id]);
$inquiry = $stmt->fetch();

if (!$inquiry) {
    header("Location: inquiries.php");
    exit;
}

// Mark as read
if (!$inquiry['is_read']) {
    $stmt = $pdo->prepare("UPDATE contact_inquiries SET is_read = 1 WHERE id = ?");
    $stmt->execute([$id]);
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Contact Inquiry</h4>
        <p class="text-muted">Details from contact form submission</p>
    </div>
    <div>
        <a href="inquiries.php" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-2"></i>Back to Inquiries
        </a>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Inquiry Details</h5>
            </div>
            <div class="card-body">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Name</label>
                        <p class="fs-5"><?= htmlspecialchars($inquiry['name']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Email</label>
                        <p class="fs-5">
                            <a
                                href="mailto:<?= htmlspecialchars($inquiry['email']) ?>"><?= htmlspecialchars($inquiry['email']) ?></a>
                        </p>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label text-muted">Phone</label>
                        <p class="fs-5"><?= htmlspecialchars($inquiry['phone']) ?></p>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label text-muted">Event Date</label>
                        <p class="fs-5">
                            <?= $inquiry['event_date'] ? date('M d, Y', strtotime($inquiry['event_date'])) : 'Not specified' ?>
                        </p>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Submitted On</label>
                    <p class="fs-5"><?= date('M d, Y h:i A', strtotime($inquiry['created_at'])) ?></p>
                </div>

                <div class="mb-3">
                    <label class="form-label text-muted">Event Details</label>
                    <div class="border p-3 rounded bg-light">
                        <?= nl2br(htmlspecialchars($inquiry['details'])) ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-md-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <h5 class="mb-0">Inquiry Actions</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="mailto:<?= htmlspecialchars($inquiry['email']) ?>?subject=Re: Your Inquiry"
                        class="btn btn-primary btn-lg">
                        <i class="bi bi-reply me-2"></i>Reply to Inquiry
                    </a>

                    <a href="inquiries.php?action=delete&id=<?= $inquiry['id'] ?>" class="btn btn-danger btn-lg"
                        onclick="return confirm('Are you sure you want to delete this inquiry?')">
                        <i class="bi bi-trash me-2"></i>Delete Inquiry
                    </a>

                    <a href="inquiries.php" class="btn btn-outline-secondary btn-lg">
                        <i class="bi bi-arrow-left me-2"></i>Back to List
                    </a>
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