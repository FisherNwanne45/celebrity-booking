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
    $sql = "INSERT INTO bookings (celebrity_id, user_name, user_email, user_phone, event_date, fan_card, pay, event_details, merchandise) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['celebrity_id'],
        $data['user_name'],
        $data['user_email'],
        $data['user_phone'],
        $data['event_date'],
        $data['fan_card'],
        $data['pay'],
        $data['event_details'],
        $data['merchandise'] // Added merchandise column
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


// Get site setting
function getSiteSetting($pdo, $key)
{
    $stmt = $pdo->prepare("SELECT setting_value FROM site_settings WHERE setting_key = ?");
    $stmt->execute([$key]);
    $result = $stmt->fetch();
    return $result ? $result['setting_value'] : '';
}

// Get all site settings
// Get all site settings
function getAllSiteSettings($pdo)
{
    $stmt = $pdo->query("SELECT setting_key, setting_value FROM site_settings");
    return $stmt->fetchAll(PDO::FETCH_KEY_PAIR);
}

// Update site settings
function updateSiteSettings($pdo, $settings)
{
    foreach ($settings as $key => $value) {
        $stmt = $pdo->prepare("UPDATE site_settings SET setting_value = ? WHERE setting_key = ?");
        $stmt->execute([$value, $key]);
    }
    return true;
}

// Get SMTP settings
function getSmtpSettings($pdo)
{
    $stmt = $pdo->query("SELECT * FROM smtp_settings ORDER BY id DESC LIMIT 1");
    return $stmt->fetch(PDO::FETCH_ASSOC);
}

// Update SMTP settings
function updateSmtpSettings($pdo, $settings)
{
    // Check if settings exist
    $current = getSmtpSettings($pdo);

    if ($current) {
        // Update existing
        $sql = "UPDATE smtp_settings SET 
                host = :host,
                port = :port,
                username = :username,
                password = :password,
                encryption = :encryption,
                from_email = :from_email,
                from_name = :from_name
            WHERE id = :id";
    } else {
        // Insert new
        $sql = "INSERT INTO smtp_settings 
                (host, port, username, password, encryption, from_email, from_name)
            VALUES 
                (:host, :port, :username, :password, :encryption, :from_email, :from_name)";
    }

    $stmt = $pdo->prepare($sql);

    // Bind parameters
    $params = [
        ':host' => $settings['host'],
        ':port' => $settings['port'],
        ':username' => $settings['username'],
        ':password' => $settings['password'],
        ':encryption' => $settings['encryption'],
        ':from_email' => $settings['from_email'],
        ':from_name' => $settings['from_name']
    ];

    if ($current) {
        $params[':id'] = $current['id'];
    }

    return $stmt->execute($params);
}

// Create contact inquiry
function createContactInquiry($pdo, $data)
{
    $sql = "INSERT INTO contact_inquiries 
            (name, email, phone, event_date, details, created_at)
            VALUES (?, ?, ?, ?, ?, NOW())";

    $stmt = $pdo->prepare($sql);
    return $stmt->execute([
        $data['name'],
        $data['email'],
        $data['phone'],
        $data['event_date'],
        $data['details']
    ]);
}

// Get site settings for contact page
function getContactSettings($pdo)
{
    $settings = getAllSiteSettings($pdo);
    return [
        'site_name' => $settings['site_name'] ?? 'Celebrity Booking',
        'contact_email' => $settings['email'] ?? 'contact@example.com',
        'phone_number' => $settings['phone_number'] ?? '',
        'address' => $settings['address'] ?? ''
    ];
}

// Get contact inquiries
function getContactInquiries($pdo, $limit = null)
{
    $sql = "SELECT * FROM contact_inquiries ORDER BY created_at DESC";
    if ($limit) {
        $sql .= " LIMIT " . (int)$limit;
    }
    $stmt = $pdo->query($sql);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Get total contact inquiries
function getTotalContactInquiries($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) FROM contact_inquiries");
    return $stmt->fetchColumn();
}

// Get recent unread contact inquiries
function getUnreadContactInquiries($pdo)
{
    $stmt = $pdo->query("SELECT COUNT(*) FROM contact_inquiries WHERE is_read = 0");
    return $stmt->fetchColumn();
}