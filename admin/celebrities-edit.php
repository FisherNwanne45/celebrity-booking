<?php
$pageTitle = "Edit Celebrity";
require_once '../includes/functions.php';
require_once '../includes/upload.php';

// Get celebrity ID
$id = $_GET['id'] ?? 0;
if (!$id) {
    header("Location: celebrities.php");
    exit;
}

// Fetch celebrity data
$celebrity = getCelebrityById($pdo, $id);
if (!$celebrity) {
    header("Location: celebrities.php");
    exit;
}


// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'] ?? '';
    $category = $_POST['category'] ?? '';
    $description = $_POST['description'] ?? '';
    $profile = $_POST['profile'] ?? '';
    $fee = $_POST['fee'] ?? 0;

    // Handle file upload if a new picture was uploaded
    $picture = $celebrity['picture']; // Keep current picture by default
    if (isset($_FILES['picture']) && $_FILES['picture']['error'] === UPLOAD_ERR_OK) {
        $newPicture = handleFileUpload($_FILES['picture']);
        if ($newPicture) {
            $picture = $newPicture;
        }
    }

    // Update celebrity in database
    $sql = "UPDATE celebrities SET 
                name = ?,
                category = ?,
                description = ?,
                profile = ?,
                fee = ?,
                picture = ?
            WHERE id = ?";

    $stmt = $pdo->prepare($sql);
    $success = $stmt->execute([$name, $category, $description, $profile, $fee, $picture, $id]);

    if ($success) {
        $_SESSION['success'] = "Celebrity updated successfully!";
        header("Location: celebrities.php");
        exit;
    } else {
        $_SESSION['error'] = "Error updating celebrity: " . implode(" ", $stmt->errorInfo());
    }
}
require_once 'includes/admin-header.php';
?>

<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Edit Celebrity</h4>
    </div>
    <div>
        <a href="celebrities.php" class="btn btn-outline-primary">
            <i class="bi bi-arrow-left me-2"></i>Back to Celebrities
        </a>
    </div>
</div>

<?php if (isset($_SESSION['error'])): ?>
    <div class="alert alert-danger"><?= $_SESSION['error'] ?></div>
    <?php unset($_SESSION['error']); ?>
<?php endif; ?>

<div class="card shadow-sm">
    <div class="card-body">
        <form method="POST" enctype="multipart/form-data">
            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="name" class="form-label">Name</label>
                    <input type="text" class="form-control" id="name" name="name"
                        value="<?= htmlspecialchars($celebrity['name']) ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="category" class="form-label">Category</label>
                    <select class="form-select" id="category" name="category" required>
                        <option value="">-- Select Category --</option>
                        <option value="Entertainment"
                            <?= $celebrity['category'] === 'Entertainment' ? 'selected' : '' ?>>Entertainment</option>
                        <option value="Music" <?= $celebrity['category'] === 'Music' ? 'selected' : '' ?>>Music</option>
                        <option value="Sports" <?= $celebrity['category'] === 'Sports' ? 'selected' : '' ?>>Sports
                        </option>
                        <option value="Business" <?= $celebrity['category'] === 'Business' ? 'selected' : '' ?>>Business
                        </option>
                        <option value="Influencer" <?= $celebrity['category'] === 'Influencer' ? 'selected' : '' ?>>
                            Influencer</option>
                    </select>
                </div>
                <div class="mb-3">
                    <label for="description" class="form-label">Known for</label>
                    <input type="text" class="form-control" id="description" name="description"
                        value="<?= htmlspecialchars($celebrity['description']) ?>" required>
                </div>

            </div>

            <div class="mb-3">
                <label for="profile" class="form-label">Profile</label>
                <textarea class="form-control" id="profile" name="profile" rows="5"
                    required><?= htmlspecialchars($celebrity['profile']) ?></textarea>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label for="fee" class="form-label">Fee</label>
                    <input type="number" step="0.01" class="form-control" id="fee" name="fee"
                        value="<?= $celebrity['fee'] ?>" required>
                </div>
                <div class="col-md-6 mb-3">
                    <label for="picture" class="form-label">Photo</label>
                    <input type="file" class="form-control" id="picture" name="picture" accept="image/*">
                    <?php if ($celebrity['picture']): ?>
                        <div class="mt-2">
                            <img src="../stars/<?= $celebrity['picture'] ?>" alt="Current Photo" class="img-thumbnail"
                                width="150">
                            <small class="d-block mt-1">Current photo</small>
                        </div>
                    <?php endif; ?>
                </div>
            </div>

            <div class="d-grid mt-4">
                <button type="submit" class="btn btn-primary btn-lg">
                    <i class="bi bi-check-circle me-2"></i>Update Celebrity
                </button>
            </div>
        </form>
    </div>
</div>

</div>
</div>
</div>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>