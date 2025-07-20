<?php
// Database configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'celebrity_booking');

// Base URL for the project
define('BASE_URL', 'http://localhost/celebrity-booking/stars/');

// Admin base URL
define('ADMIN_URL', 'http://localhost/celebrity-booking/admin/');

// Upload directory
define('UPLOAD_DIR', __DIR__ . '/../stars/assets/uploads/');

// Set timezone
date_default_timezone_set('America/New_York');

// Start session
session_start();
