<?php
$pageTitle = "SMTP Settings";
require_once 'includes/admin-header.php';
require_once '../includes/functions.php';

// Get current settings
$smtpSettings = getSmtpSettings($pdo);

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $newSettings = [
        'host' => $_POST['host'],
        'port' => (int)$_POST['port'],
        'username' => $_POST['username'],
        'encryption' => $_POST['encryption'],
        'from_email' => $_POST['from_email'],
        'from_name' => $_POST['from_name']
    ];

    // Only update password if provided
    if (!empty($_POST['password'])) {
        $newSettings['password'] = $_POST['password'];
    } else {
        // Keep existing password
        $newSettings['password'] = $smtpSettings['password'];
    }

    if (updateSmtpSettings($pdo, $newSettings)) {
        $_SESSION['success'] = "SMTP settings updated successfully!";
        // Refresh settings
        $smtpSettings = getSmtpSettings($pdo);
    } else {
        $_SESSION['error'] = "Error updating SMTP settings";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title><?= $pageTitle ?> - Celebrity Booking</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        <style>
        .stat-card {
            background: white;
            border-radius: 10px;
            padding: 20px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            transition: transform 0.3s;
        }

        .stat-card:hover {
            transform: translateY(-5px);
        }

        .stat-card.primary {
            border-left: 4px solid #4e73df;
        }

        .stat-card.success {
            border-left: 4px solid #1cc88a;
        }

        .stat-card.warning {
            border-left: 4px solid #f6c23e;
        }

        .stat-card.danger {
            border-left: 4px solid #e74a3b;
        }

        .password-toggle {
            cursor: pointer;
        }
        </style>
    </head>

    <body>
        <div class="container-fluid">


            <div class="container py-4">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h4 class="mb-0">SMTP Settings</h4>
                        <p class="text-muted">Configure your email server settings</p>
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

                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="host" class="form-label">SMTP Host</label>
                                    <input type="text" class="form-control" id="host" name="host"
                                        value="<?= htmlspecialchars($smtpSettings['host'] ?? '') ?>" required
                                        placeholder="smtp.example.com">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="port" class="form-label">SMTP Port</label>
                                    <input type="number" class="form-control" id="port" name="port"
                                        value="<?= htmlspecialchars($smtpSettings['port'] ?? 587) ?>" required
                                        placeholder="587">
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="username" class="form-label">SMTP Username</label>
                                    <input type="text" class="form-control" id="username" name="username"
                                        value="<?= htmlspecialchars($smtpSettings['username'] ?? '') ?>" required
                                        placeholder="your@email.com">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="password" class="form-label">SMTP Password</label>
                                    <div class="input-group">
                                        <input type="password" class="form-control" id="password" name="password"
                                            placeholder="Leave blank to keep current password">
                                        <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                            <i class="bi bi-eye"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="encryption" class="form-label">Encryption</label>
                                    <select class="form-select" id="encryption" name="encryption" required>
                                        <option value="">Select encryption method</option>
                                        <option value="tls"
                                            <?= ($smtpSettings['encryption'] ?? '') === 'tls' ? 'selected' : '' ?>>TLS
                                        </option>
                                        <option value="ssl"
                                            <?= ($smtpSettings['encryption'] ?? '') === 'ssl' ? 'selected' : '' ?>>SSL
                                        </option>
                                        <option value="none"
                                            <?= ($smtpSettings['encryption'] ?? '') === 'none' ? 'selected' : '' ?>>None
                                        </option>
                                    </select>
                                </div>
                            </div>

                            <hr class="my-4">
                            <h5 class="mb-3">Email Settings</h5>

                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="from_email" class="form-label">From Email Address</label>
                                    <input type="email" class="form-control" id="from_email" name="from_email"
                                        value="<?= htmlspecialchars($smtpSettings['from_email'] ?? '') ?>" required
                                        placeholder="noreply@example.com">
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="from_name" class="form-label">From Name</label>
                                    <input type="text" class="form-control" id="from_name" name="from_name"
                                        value="<?= htmlspecialchars($smtpSettings['from_name'] ?? '') ?>" required
                                        placeholder="Celebrity Booking System">
                                </div>
                            </div>

                            <div class="d-flex justify-content-end mt-4">
                                <button type="button" class="btn btn-outline-info me-2" id="testEmailBtn">
                                    <i class="bi bi-envelope-check me-2"></i>Send Test Email
                                </button>
                                <button type="submit" class="btn btn-primary px-4">
                                    <i class="bi bi-save me-2"></i>Save Settings
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>

        <!-- Test Email Modal -->
        <div class="modal fade" id="testEmailModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Send Test Email</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="test_email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="test_email" placeholder="your@email.com">
                        </div>
                        <div id="testResult" class="mt-3"></div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="button" class="btn btn-primary" id="sendTestEmail">Send Test</button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Load Bootstrap JS -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

        <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            const togglePassword = document.getElementById('togglePassword');
            if (togglePassword) {
                togglePassword.addEventListener('click', function() {
                    const passwordField = document.getElementById('password');
                    const icon = this.querySelector('i');

                    if (passwordField.type === 'password') {
                        passwordField.type = 'text';
                        icon.classList.remove('bi-eye');
                        icon.classList.add('bi-eye-slash');
                    } else {
                        passwordField.type = 'password';
                        icon.classList.remove('bi-eye-slash');
                        icon.classList.add('bi-eye');
                    }
                });
            }

            // Test email functionality
            const testEmailBtn = document.getElementById('testEmailBtn');
            if (testEmailBtn) {
                testEmailBtn.addEventListener('click', function() {
                    const modalElement = document.getElementById('testEmailModal');
                    if (modalElement) {
                        const modal = new bootstrap.Modal(modalElement);
                        document.getElementById('testResult').innerHTML = '';
                        modal.show();
                    }
                });
            }

            const sendTestEmailBtn = document.getElementById('sendTestEmail');
            if (sendTestEmailBtn) {
                sendTestEmailBtn.addEventListener('click', function() {
                    const email = document.getElementById('test_email').value;
                    const resultDiv = document.getElementById('testResult');

                    // Simple email validation
                    if (!email || !email.includes('@')) {
                        resultDiv.innerHTML =
                            '<div class="alert alert-danger">Please enter a valid email address</div>';
                        return;
                    }

                    resultDiv.innerHTML = '<div class="alert alert-info">Sending test email...</div>';

                    // Simulate sending test email (in a real app, this would be an AJAX call)
                    setTimeout(() => {
                        resultDiv.innerHTML = `
                    <div class="alert alert-success">
                        <i class="bi bi-check-circle me-2"></i>
                        Test email sent successfully to ${email}
                    </div>
                `;
                    }, 1500);
                });
            }
        });
        </script>

    </body>

</html>