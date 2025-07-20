<?php
require_once 'db.php';

// Get all celebrities
function getCelebrities($pdo)
{
    $stmt = $pdo->query("SELECT * FROM celebrities ORDER BY created_at DESC");
    return $stmt->fetchAll();
}

// Get celebrity by ID
function getCelebrityById($pdo, $id)
{
    $stmt = $pdo->prepare("SELECT * FROM celebrities WHERE id = ?");
    $stmt->execute([$id]);
    return $stmt->fetch();
}

// Get all bookings
function getBookings($pdo)
{
    $stmt = $pdo->query("SELECT bookings.*, celebrities.name AS celebrity_name 
                        FROM bookings 
                        JOIN celebrities ON bookings.celebrity_id = celebrities.id 
                        ORDER BY bookings.created_at DESC");
    return $stmt->fetchAll();
}

// Get active payment methods
function getPaymentMethods($pdo)
{
    $stmt = $pdo->query("SELECT * FROM payment_methods WHERE is_active = 1");
    return $stmt->fetchAll();
}

// Create a new booking
function createBooking($pdo, $data)
{
    $sql = "INSERT INTO bookings (celebrity_id, user_name, user_email, user_phone, event_date, event_details) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['celebrity_id'],
        $data['user_name'],
        $data['user_email'],
        $data['user_phone'],
        $data['event_date'],
        $data['event_details']
    ]);
}

// Create a new celebrity
function createCelebrity($pdo, $data)
{
    $sql = "INSERT INTO celebrities (name, category, description, profile, fee, picture) 
            VALUES (?, ?, ?, ?,? , ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['name'],
        $data['category'],
        $data['description'],
        $data['profile'],
        $data['fee'],
        $data['picture']
    ]);
}

// Create a new payment method
function createPaymentMethod($pdo, $data)
{
    $sql = "INSERT INTO payment_methods (name, details, instructions) 
            VALUES (?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['name'],
        $data['details'],
        $data['instructions']
    ]);
}

// Update booking status
function updateBookingStatus($pdo, $id, $status)
{
    $sql = "UPDATE bookings SET status = ? WHERE id = ?";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([$status, $id]);
}
