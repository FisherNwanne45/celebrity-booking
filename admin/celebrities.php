<?php
$pageTitle = "Manage Celebrities";
require_once 'includes/admin-header.php';
require_once '../includes/functions.php';
require_once '../includes/upload.php';

// Handle form submissions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['add_celebrity'])) {
        $picture = handleFileUpload($_FILES['picture']);
        $data = [
            'name' => $_POST['name'],
            'category' => $_POST['category'],
            'profile' => $_POST['profile'],
            'fee' => $_POST['fee'],
            'picture' => $picture ? $picture : ''
        ];
        if (createCelebrity($pdo, $data)) {
            $_SESSION['success'] = "Celebrity added successfully!";
        } else {
            $_SESSION['error'] = "Error adding celebrity";
        }
    }
}

// Get all celebrities
$celebrities = getCelebrities($pdo);
?>

<!-- Add New Celebrity Button -->
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h4 class="mb-0">Manage Celebrities</h4>
    </div>
    <div>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCelebrityModal">
            <i class="bi bi-plus-circle me-2"></i>Add New Celebrity
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

<!-- Celebrities Table -->
<div class="card shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover">
                <thead>
                    <tr>
                        <th>Photo</th>
                        <th>Name</th>
                        <th>Category</th>
                        <th>Fee</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($celebrities as $celebrity): ?>
                    <tr>
                        <td>
                            <?php if ($celebrity['picture']): ?>
                            <img src="../stars/<?= $celebrity['picture'] ?>" alt="<?= $celebrity['name'] ?>"
                                class="rounded" width="60" height="60">
                            <?php else: ?>
                            <div class="bg-secondary rounded d-flex align-items-center justify-content-center"
                                style="width: 60px; height: 60px;">
                                <i class="bi bi-person text-light" style="font-size: 1.5rem;"></i>
                            </div>
                            <?php endif; ?>
                        </td>
                        <td><?= $celebrity['name'] ?></td>
                        <td><?= $celebrity['category'] ?></td>
                        <td>$<?= number_format($celebrity['fee']) ?></td>
                        <td>
                            <div class="btn-group">
                                <a href="celebrities-edit.php?id=<?= $celebrity['id'] ?>"
                                    class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-pencil"></i> Edit
                                </a>
                                <a href="celebrities-delete.php?id=<?= $celebrity['id'] ?>"
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

<!-- Add Celebrity Modal -->
<div class="modal fade" id="addCelebrityModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Celebrity</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST" enctype="multipart/form-data">
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="text" class="form-control" id="name" name="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="category" class="form-label">Category</label>
                            <select class="form-select" id="category" name="category" required>
                                <option value="">-- Select Category --</option>
                                <option value="Entertainment">Entertainment</option>
                                <option value="Music">Music</option>
                                <option value="Sports">Sports</option>
                                <option value="Business">Business</option>
                                <option value="Influencer">Influencer</option>
                            </select>
                        </div>

                    </div>
                    <div class="mb-3">
                        <label for="description" class="form-label">Known for</label>
                        <input type="text" class="form-control" placeholder="Short description about the celeb"
                            id="description" name="description" required>
                    </div>
                    <div class="mb-3">
                        <label for="profile" class="form-label">Profile</label>
                        <textarea class="form-control" id="profile" name="profile" rows="4" required></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label for="fee" class="form-label">Fee</label>
                            <input type="number" step="0.01" class="form-control" id="fee" name="fee" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label for="picture" class="form-label">Photo</label>
                            <input type="file" class="form-control" id="picture" name="picture" accept="image/*">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_celebrity" class="btn btn-primary">Add Celebrity</button>
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