<?php
session_start();
require_once 'config.php';
require_once 'db.php';
require_once 'functions.php';
require_once 'mailer.php';

header('Content-Type: application/json');

// Only allow admins to send test emails
if (!isAdminLoggedIn()) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized access']);
    exit;
}

// Get test email address
$testEmail = $_POST['email'] ?? '';

if (empty($testEmail) || !filter_var($testEmail, FILTER_VALIDATE_EMAIL)) {
    echo json_encode(['success' => false, 'message' => 'Invalid email address']);
    exit;
}

// Create test booking data
$testBooking = [
    'user_name' => 'Test User',
    'user_email' => $testEmail,
    'event_date' => date('Y-m-d'),
    'event_details' => 'This is a test email to verify SMTP configuration'
];

$testCelebrity = [
    'name' => 'Test Celebrity'
];

// Try to send test email
if (sendBookingConfirmation($pdo, $testBooking, $testCelebrity)) {
    echo json_encode(['success' => true, 'message' => 'Test email sent successfully']);
} else {
    // Get last error if available
    $error = error_get_last();
    $errorMsg = $error ? $error['message'] : 'Unknown error';

    echo json_encode([
        'success' => false,
        'message' => 'Failed to send test email: ' . $errorMsg
    ]);
}