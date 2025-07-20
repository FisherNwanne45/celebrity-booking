<?php
require_once '../includes/functions.php';
require_once '../includes/auth.php';

// Redirect to login if not authenticated
requireAdminLogin();

// Get current admin details
$adminId = $_SESSION['admin_id'];
$currentUsername = '';
$error = '';
$success = '';

// Fetch current admin details
$stmt = $pdo->prepare("SELECT username FROM admin_users WHERE id = ?");
$stmt->execute([$adminId]);
$admin = $stmt->fetch();
if ($admin) {
    $currentUsername = $admin['username'];
}

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username'] ?? '');
    $currentPassword = trim($_POST['current_password'] ?? '');
    $newPassword = trim($_POST['new_password'] ?? '');
    $confirmPassword = trim($_POST['confirm_password'] ?? '');

    // Validate inputs
    $errors = [];

    // Validate username
    if (empty($username)) {
        $errors[] = "Username is required";
    } elseif (strlen($username) < 3) {
        $errors[] = "Username must be at least 3 characters";
    }

    // Check if password fields are filled
    $changingPassword = !empty($newPassword) || !empty($confirmPassword);

    // Validate current password if changing credentials
    if ($changingPassword || $username !== $currentUsername) {
        if (empty($currentPassword)) {
            $errors[] = "Current password is required to make changes";
        } else {
            // Verify current password
            $stmt = $pdo->prepare("SELECT password FROM admin_users WHERE id = ?");
            $stmt->execute([$adminId]);
            $admin = $stmt->fetch();

            if (!$admin || !password_verify($currentPassword, $admin['password'])) {
                $errors[] = "Current password is incorrect";
            }
        }
    }

    // Validate new password if provided
    if ($changingPassword) {
        if (empty($newPassword)) {
            $errors[] = "New password is required";
        } elseif (strlen($newPassword) < 8) {
            $errors[] = "Password must be at least 8 characters";
        } elseif ($newPassword !== $confirmPassword) {
            $errors[] = "New passwords do not match";
        }
    }

    // If no errors, update database
    if (empty($errors)) {
        try {
            // Start transaction
            $pdo->beginTransaction();

            // Update username if changed
            if ($username !== $currentUsername) {
                $updateStmt = $pdo->prepare("UPDATE admin_users SET username = ? WHERE id = ?");
                $updateStmt->execute([$username, $adminId]);
                $_SESSION['admin_username'] = $username; // Update session
                $currentUsername = $username;
            }

            // Update password if changed
            if ($changingPassword) {
                $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                $updateStmt = $pdo->prepare("UPDATE admin_users SET password = ? WHERE id = ?");
                $updateStmt->execute([$hashedPassword, $adminId]);
            }

            $pdo->commit();
            $success = "Profile updated successfully!";
        } catch (PDOException $e) {
            $pdo->rollBack();
            $errors[] = "Database error: " . $e->getMessage();
        }
    }

    // Set error message if any
    if (!empty($errors)) {
        $error = implode("<br>", $errors);
    }
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Profile - Celebrity Booking System</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        <style>
        body {
            background: linear-gradient(135deg, #f5f7fa 0%, #e4e7f1 100%);
            min-height: 100vh;
            padding-top: 20px;
        }

        .profile-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            border: none;
            overflow: hidden;
        }

        .profile-header {
            background: linear-gradient(120deg, #4b6cb7 0%, #182848 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .profile-pic {
            width: 120px;
            height: 120px;
            border-radius: 50%;
            border: 4px solid rgba(255, 255, 255, 0.3);
            background: #2c3e50;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 48px;
            color: white;
        }

        .form-control:focus {
            border-color: #4b6cb7;
            box-shadow: 0 0 0 0.25rem rgba(75, 108, 183, 0.25);
        }

        .password-toggle {
            cursor: pointer;
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #6c757d;
        }

        .password-container {
            position: relative;
        }

        .security-indicator {
            height: 5px;
            border-radius: 3px;
            margin-top: 5px;
            transition: all 0.3s;
        }

        .btn-primary {
            background: linear-gradient(120deg, #4b6cb7, #182848);
            border: none;
            padding: 10px 25px;
            transition: all 0.3s;
        }

        .btn-primary:hover {
            background: linear-gradient(120deg, #3a5ca5, #121f3d);
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(0, 0, 0, 0.1);
        }

        .password-requirements {
            font-size: 0.85rem;
            color: #6c757d;
            margin-top: 5px;
        }
        </style>
    </head>

    <body>
        <div class="container py-5">
            <div class="row justify-content-center">
                <div class="col-md-8 col-lg-6">
                    <div class="profile-card">
                        <div class="profile-header">
                            <div class="profile-pic">
                                <i class="bi bi-person-circle"></i>
                            </div>
                            <h3>Admin Profile</h3>
                            <p class="mb-0">Update your account details</p>
                        </div>

                        <div class="card-body p-4">
                            <?php if ($error): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                            <?php endif; ?>

                            <?php if ($success): ?>
                            <div class="alert alert-success"><?= $success ?></div>
                            <?php endif; ?>

                            <form method="POST">
                                <div class="mb-4">
                                    <label class="form-label fw-bold">Account Information</label>
                                    <div class="mb-3">
                                        <label for="username" class="form-label">Username</label>
                                        <input type="text" class="form-control form-control-lg" id="username"
                                            name="username" value="<?= htmlspecialchars($currentUsername) ?>" required>
                                    </div>

                                    <div class="mb-3">
                                        <label for="current_password" class="form-label">Current Password</label>
                                        <div class="password-container">
                                            <input type="password" class="form-control form-control-lg"
                                                id="current_password" name="current_password">
                                            <span class="password-toggle" onclick="togglePassword('current_password')">
                                                <i class="bi bi-eye"></i>
                                            </span>
                                        </div>
                                        <div class="form-text">Required to make any changes</div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label fw-bold">Change Password</label>
                                    <div class="mb-3">
                                        <label for="new_password" class="form-label">New Password</label>
                                        <div class="password-container">
                                            <input type="password" class="form-control form-control-lg"
                                                id="new_password" name="new_password" oninput="checkPasswordStrength()">
                                            <span class="password-toggle" onclick="togglePassword('new_password')">
                                                <i class="bi bi-eye"></i>
                                            </span>
                                        </div>
                                        <div id="password-strength" class="security-indicator"></div>
                                        <div class="password-requirements">
                                            Password must be at least 8 characters
                                        </div>
                                    </div>

                                    <div class="mb-3">
                                        <label for="confirm_password" class="form-label">Confirm New Password</label>
                                        <div class="password-container">
                                            <input type="password" class="form-control form-control-lg"
                                                id="confirm_password" name="confirm_password">
                                            <span class="password-toggle" onclick="togglePassword('confirm_password')">
                                                <i class="bi bi-eye"></i>
                                            </span>
                                        </div>
                                        <div id="password-match" class="form-text"></div>
                                    </div>
                                </div>

                                <div class="d-grid mt-4">
                                    <button type="submit" class="btn btn-primary btn-lg">
                                        <i class="bi bi-check-circle me-2"></i>Update Profile
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="text-center mt-4">
                        <a href="index.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <script>
        // Toggle password visibility
        function togglePassword(id) {
            const input = document.getElementById(id);
            const icon = input.nextElementSibling.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            }
        }

        // Check password strength
        function checkPasswordStrength() {
            const password = document.getElementById('new_password').value;
            const indicator = document.getElementById('password-strength');

            if (password.length === 0) {
                indicator.style.width = '0%';
                indicator.style.backgroundColor = '#e9ecef';
                return;
            }

            // Simple strength calculation
            let strength = 0;
            if (password.length >= 8) strength += 30;
            if (/[A-Z]/.test(password)) strength += 20;
            if (/[a-z]/.test(password)) strength += 20;
            if (/[0-9]/.test(password)) strength += 20;
            if (/[^A-Za-z0-9]/.test(password)) strength += 10;

            // Limit to 100%
            strength = Math.min(strength, 100);

            // Update indicator
            indicator.style.width = strength + '%';

            if (strength < 40) {
                indicator.style.backgroundColor = '#dc3545'; // Red
            } else if (strength < 70) {
                indicator.style.backgroundColor = '#ffc107'; // Yellow
            } else {
                indicator.style.backgroundColor = '#28a745'; // Green
            }
        }

        // Check password match
        document.getElementById('confirm_password').addEventListener('input', function() {
            const password = document.getElementById('new_password').value;
            const confirm = this.value;
            const matchText = document.getElementById('password-match');

            if (confirm.length === 0) {
                matchText.textContent = '';
                matchText.style.color = '';
            } else if (password === confirm) {
                matchText.textContent = 'Passwords match';
                matchText.style.color = '#28a745';
            } else {
                matchText.textContent = 'Passwords do not match';
                matchText.style.color = '#dc3545';
            }
        });

        // Initialize on page load
        window.onload = function() {
            checkPasswordStrength();
        };
        </script>
    </body>

</html>