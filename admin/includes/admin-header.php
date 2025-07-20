<?php
require_once '../includes/auth.php';
require_once '../includes/db.php';  // Include database connection

// Handle logout request
if (isset($_GET['logout'])) {
    adminLogout();
    header("Location: login.php");
    exit;
}
// This function doesn't need any parameters
requireAdminLogin();

?>

<!DOCTYPE html>
<html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Admin Dashboard - Celebrity Booking System</title>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
        <link rel="stylesheet" href="../stars/assets/css/custom.css">
        <link rel="icon" type="image/png" sizes="32x32" href="../stars/assets/favicon.png">
        <style>
        .admin-sidebar {
            background: #343a40;
            color: #fff;
            min-height: 100vh;
            padding-top: 20px;
        }

        .admin-sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 10px 20px;
            margin: 5px 0;
            border-radius: 5px;
        }

        .admin-sidebar .nav-link:hover,
        .admin-sidebar .nav-link.active {
            background: #495057;
            color: white;
        }

        .admin-sidebar .nav-link i {
            margin-right: 10px;
            width: 20px;
            text-align: center;
        }

        .admin-content {
            padding: 20px;
        }

        .admin-header {
            background: white;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            padding: 15px 20px;
            margin-bottom: 20px;
            border-radius: 8px;
        }

        .stat-card {
            border-radius: 10px;
            overflow: hidden;
            margin-bottom: 20px;
            color: white;
            padding: 20px;
            box-shadow: 0 4px 15px rgba(0, 0, 0, 0.1);
        }

        .stat-card.primary {
            background: linear-gradient(to right, #6a11cb, #2575fc);
        }

        .stat-card.success {
            background: linear-gradient(to right, #00b09b, #96c93d);
        }

        .stat-card.warning {
            background: linear-gradient(to right, #ff8c00, #ffd700);
        }

        .stat-card.danger {
            background: linear-gradient(to right, #ff416c, #ff4b2b);
        }

        .stat-card.info {
            background: linear-gradient(to right, #17a2b8, #63e0e8);
            /* Teal/Cyan tones */
        }

        .stat-card.secondary {
            background: linear-gradient(to right, #6c757d, #adb5bd);
            /* Gray tones */
        }

        .stat-card.light {
            background: linear-gradient(to right, #f8f9fa, #e9ecef);
            /* Very light neutral tones */
        }
        </style>
    </head>

    <body>
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-2 col-lg-2 d-md-block bg-dark admin-sidebar collapse">
                    <div class="text-center mb-4">
                        <h4 class="text-white">Admin Panel</h4>
                    </div>

                    <ul class="nav flex-column">
                        <li class="nav-item">
                            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'index.php' ? 'active' : '' ?>"
                                href="index.php">
                                <i class="bi bi-speedometer2"></i>Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'celebrities.php' ? 'active' : '' ?>"
                                href="celebrities.php">
                                <i class="bi bi-people"></i>Celebrities
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'bookings.php' ? 'active' : '' ?>"
                                href="bookings.php">
                                <i class="bi bi-calendar-check"></i>Bookings
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'payments.php' ? 'active' : '' ?>"
                                href="payments.php">
                                <i class="bi bi-credit-card"></i>Payment Methods
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link <?= basename($_SERVER['PHP_SELF']) == 'settings.php' ? 'active' : '' ?>"
                                href="settings.php">
                                <i class="bi bi-gear"></i>Site Settings
                            </a>
                        </li>
                        <li class="nav-item mt-4">
                            <a class="nav-link" href="?logout">
                                <i class="bi bi-box-arrow-right"></i>Logout
                            </a>
                        </li>
                    </ul>
                </div>

                <!-- Main Content -->
                <div class="col-md-10 ms-sm-auto col-lg-10 px-md-4 admin-content">
                    <div class="admin-header d-flex justify-content-between align-items-center">
                        <div>
                            <h3 class="mb-0"><?= $pageTitle ?? 'Dashboard' ?></h3>
                        </div>
                        <div>
                            <span class="me-3">Welcome, Admin</span>
                            <div class="btn-group">
                                <button type="button" class="btn btn-sm btn-primary dropdown-toggle"
                                    data-bs-toggle="dropdown">
                                    <i class="bi bi-person-circle"></i>
                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item" href="profile.php">
                                            <i class="bi bi-person-circle me-2"></i> Profile
                                        </a>
                                    </li>

                                    <li>
                                        <a class="dropdown-item" href="settings.php">
                                            <i class="bi bi-gear me-2"></i> Settings
                                        </a>
                                    </li>

                                    <li><a class="dropdown-item" href="smtp-settings.php">
                                            <i class="bi bi-envelope me-2"></i> SMTP Settings
                                        </a>
                                    </li>
                                    <li>
                                        <hr class="dropdown-divider">
                                    </li>
                                    <li><a class="dropdown-item" href="?logout">Logout</a></li>
                                </ul>
                            </div>
                        </div>
                    </div>