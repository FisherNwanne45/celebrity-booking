<?php
require_once '../includes/db.php';
require_once '../includes/auth.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    if (adminLogin($pdo, $username, $password)) {
        header("Location: index.php");
        exit;
    } else {
        $error = "Invalid username or password";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login - Celebrity Booking System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.0/font/bootstrap-icons.css">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            height: 100vh;
            display: flex;
            align-items: center;
        }

        .login-card {
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            overflow: hidden;
        }

        .login-header {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }

        .login-form {
            padding: 30px;
            background: white;
        }

        .form-control {
            border-radius: 8px;
            padding: 12px 15px;
        }

        .btn-login {
            background: linear-gradient(to right, #6a11cb, #2575fc);
            border: none;
            border-radius: 8px;
            padding: 12px;
            font-weight: 600;
            transition: all 0.3s ease;
        }

        .btn-login:hover {
            background: linear-gradient(to right, #5a0cb0, #1c65e0);
            transform: translateY(-2px);
        }
    </style>
</head>

<body>
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card login-card">
                    <div class="login-header">
                        <h1><i class="bi bi-star-fill me-2"></i>StarBookings</h1>
                        <p class="mb-0">Admin Dashboard</p>
                    </div>
                    <div class="login-form">
                        <h3 class="mb-4">Sign In</h3>

                        <?php if (isset($error)): ?>
                            <div class="alert alert-danger"><?= $error ?></div>
                        <?php endif; ?>

                        <form method="POST">
                            <div class="mb-3">
                                <label for="username" class="form-label">Username</label>
                                <input type="text" class="form-control" id="username" name="username" required>
                            </div>
                            <div class="mb-4">
                                <label for="password" class="form-label">Password</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="d-grid">
                                <button type="submit" class="btn btn-login btn-lg text-white">Login</button>
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