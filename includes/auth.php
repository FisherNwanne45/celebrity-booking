<?php
// Check if admin is logged in
function isAdminLoggedIn()
{
    return isset($_SESSION['admin_logged_in']) && $_SESSION['admin_logged_in'] === true;
}

// Admin login
function adminLogin($pdo, $username, $password)
{
    $stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ?");
    $stmt->execute([$username]);
    $admin = $stmt->fetch();

    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin_logged_in'] = true;
        $_SESSION['admin_id'] = $admin['id'];
        return true;
    }
    return false;
}

// Admin logout
function adminLogout()
{
    session_unset();
    session_destroy();
}

// Redirect to login if not logged in
function requireAdminLogin()
{
    if (!isAdminLoggedIn()) {
        header("Location: login.php");
        exit;
    }
}
