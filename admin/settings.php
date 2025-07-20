<?php
$pageTitle = "Site Settings";
require_once 'includes/admin-header.php';
require_once '../includes/functions.php';

// Get current settings
$settings = getAllSiteSettings($pdo);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newSettings = [
        'site_name' => $_POST['site_name'],
        'phone_number' => $_POST['phone_number'],
        'address' => $_POST['address'],
        'email' => $_POST['email'],
        'facebook' => $_POST['facebook'],
        'twitter' => $_POST['twitter'],
        'instagram' => $_POST['instagram'],
        'linkedin' => $_POST['linkedin']
    ];

    if (updateSiteSettings($pdo, $newSettings)) {
        $_SESSION['success'] = "Site settings updated successfully!";
        // Refresh settings
        $settings = getAllSiteSettings($pdo);
    } else {
        $_SESSION['error'] = "Error updating site settings";
    }
}
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Site Settings</h4>
        <p class="text-muted">Update your website information and social media links</p>
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
        <form method="POST">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="site_name" class="form-label">Site Name</label>
                    <input type="text" class="form-control" id="site_name" name="site_name"
                        value="<?= htmlspecialchars($settings['site_name']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="phone_number" class="form-label">Phone Number</label>
                    <input type="text" class="form-control" id="phone_number" name="phone_number"
                        value="<?= htmlspecialchars($settings['phone_number']) ?>">
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="email" class="form-label">Email Address</label>
                    <input type="email" class="form-control" id="email" name="email"
                        value="<?= htmlspecialchars($settings['email']) ?>">
                </div>
                <div class="col-md-6 mb-3">
                    <label for="address" class="form-label">Address</label>
                    <input type="text" class="form-control" id="address" name="address"
                        value="<?= htmlspecialchars($settings['address']) ?>">
                </div>
            </div>

            <hr class="my-4">
            <h5 class="mb-3">Social Media</h5>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="facebook" class="form-label">Facebook URL</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-facebook"></i></span>
                        <input type="url" class="form-control" id="facebook" name="facebook"
                            value="<?= htmlspecialchars($settings['facebook']) ?>"
                            placeholder="https://facebook.com/yourpage">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="twitter" class="form-label">Twitter URL</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-twitter"></i></span>
                        <input type="url" class="form-control" id="twitter" name="twitter"
                            value="<?= htmlspecialchars($settings['twitter']) ?>"
                            placeholder="https://twitter.com/yourhandle">
                    </div>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="instagram" class="form-label">Instagram URL</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-instagram"></i></span>
                        <input type="url" class="form-control" id="instagram" name="instagram"
                            value="<?= htmlspecialchars($settings['instagram']) ?>"
                            placeholder="https://instagram.com/yourprofile">
                    </div>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="linkedin" class="form-label">LinkedIn URL</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-linkedin"></i></span>
                        <input type="url" class="form-control" id="linkedin" name="linkedin"
                            value="<?= htmlspecialchars($settings['linkedin']) ?>"
                            placeholder="https://linkedin.com/company/yourcompany">
                    </div>
                </div>
            </div>

            <div class="d-flex justify-content-end mt-4">
                <button type="submit" class="btn btn-primary px-4">
                    <i class="bi bi-save me-2"></i>Save Settings
                </button>
            </div>
        </form>
    </div>
</div>

</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>