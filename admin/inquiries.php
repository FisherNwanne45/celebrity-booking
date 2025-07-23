<?php
$pageTitle = "Contact Inquiries";
require_once '../includes/functions.php';

// Handle inquiry actions
if (isset($_GET['action']) && isset($_GET['id'])) {
    $id = (int)$_GET['id'];

    switch ($_GET['action']) {
        case 'view':
            // Mark as read when viewing
            $stmt = $pdo->prepare("UPDATE contact_inquiries SET is_read = 1 WHERE id = ?");
            $stmt->execute([$id]);
            break;

        case 'delete':
            $stmt = $pdo->prepare("DELETE FROM contact_inquiries WHERE id = ?");
            $stmt->execute([$id]);
            $_SESSION['success'] = "Inquiry deleted successfully";
            header("Location: inquiries.php");
            exit;
    }
}

require_once 'includes/admin-header.php';
// Get all inquiries
$inquiries = getContactInquiries($pdo);
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Contact Inquiries</h4>
        <p class="text-muted">Manage inquiries from the contact form</p>
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

                        <th>Name</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Event Date</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inquiries as $inquiry): ?>
                        <tr class="<?= $inquiry['is_read'] ? '' : 'table-warning' ?>">

                            <td><?= htmlspecialchars($inquiry['name']) ?></td>
                            <td><a
                                    href="mailto:<?= htmlspecialchars($inquiry['email']) ?>"><?= htmlspecialchars($inquiry['email']) ?></a>
                            </td>
                            <td><?= htmlspecialchars($inquiry['phone']) ?></td>
                            <td><?= $inquiry['event_date'] ? date('M d, Y', strtotime($inquiry['event_date'])) : 'N/A' ?>
                            </td>
                            <td><?= date('M d, Y', strtotime($inquiry['created_at'])) ?></td>
                            <td>
                                <span class="badge bg-<?= $inquiry['is_read'] ? 'success' : 'warning' ?>">
                                    <?= $inquiry['is_read'] ? 'Read' : 'Unread' ?>
                                </span>
                            </td>
                            <td>
                                <div class="btn-group">
                                    <a href="inquiries-view.php?action=view&id=<?= $inquiry['id'] ?>"
                                        class="btn btn-sm btn-primary">
                                        <i class="bi bi-eye"></i> View
                                    </a>
                                    <a href="inquiries.php?action=delete&id=<?= $inquiry['id'] ?>"
                                        class="btn btn-sm btn-danger"
                                        onclick="return confirm('Are you sure you want to delete this inquiry?')">
                                        <i class="bi bi-trash"></i>
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

</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>