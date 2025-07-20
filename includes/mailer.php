<?php
require_once '../vendor/autoload.php';
require_once 'config.php';
require_once 'functions.php';

// Send booking confirmation email
function sendBookingConfirmation($pdo, $booking, $celebrity)
{
    // Get SMTP settings
    $smtpSettings = getSmtpSettings($pdo);

    if (!$smtpSettings) {
        error_log("Mailer Error: SMTP settings not configured");
        return false;
    }

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = $smtpSettings['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpSettings['username'];
        $mail->Password   = $smtpSettings['password'];

        // Set encryption
        $encryption = $smtpSettings['encryption'];
        if ($encryption === 'tls') {
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        } elseif ($encryption === 'ssl') {
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        }

        $mail->Port       = $smtpSettings['port'];

        // Recipients
        $mail->setFrom(
            $smtpSettings['from_email'],
            $smtpSettings['from_name']
        );
        $mail->addAddress($booking['user_email'], $booking['user_name']);

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Booking Confirmation';

        $mail->Body = "
            <h1>Booking Confirmation</h1>
            <p>Dear {$booking['user_name']},</p>
            <p>Thank you for booking {$celebrity['name']} for your event on {$booking['event_date']}.</p>
            <p>We will contact you shortly to confirm the details and provide payment instructions.</p>
            <p>Booking Details:</p>
            <ul>
                <li>Celebrity: {$celebrity['name']}</li>
                <li>Event Date: " . date('F j, Y', strtotime($booking['event_date'])) . "</li>
                <li>Your Message: {$booking['event_details']}</li>
            </ul>
            <p>Best regards,<br>{$smtpSettings['from_name']}</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}

// Send contact inquiry notification
function sendContactNotification($pdo, $data)
{
    // Get SMTP settings
    $smtpSettings = getSmtpSettings($pdo);

    if (!$smtpSettings) {
        error_log("Mailer Error: SMTP settings not configured");
        return false;
    }

    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = $smtpSettings['host'];
        $mail->SMTPAuth   = true;
        $mail->Username   = $smtpSettings['username'];
        $mail->Password   = $smtpSettings['password'];

        // Set encryption
        $encryption = $smtpSettings['encryption'];
        if ($encryption === 'tls') {
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        } elseif ($encryption === 'ssl') {
            $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_SMTPS;
        }

        $mail->Port       = $smtpSettings['port'];

        // Recipients
        $mail->setFrom(
            $smtpSettings['from_email'],
            $smtpSettings['from_name']
        );
        $mail->addAddress($smtpSettings['from_email']); // Send to admin
        $mail->addReplyTo($data['email'], $data['name']); // Set reply-to as user

        // Content
        $mail->isHTML(true);
        $mail->Subject = 'New Contact Inquiry from ' . $data['name'];

        $mail->Body = "
            <h1>New Contact Inquiry</h1>
            <p>You have received a new contact inquiry:</p>
            
            <table style='width:100%; border-collapse: collapse;'>
                <tr style='background-color: #f8f9fa;'>
                    <th style='padding: 8px; border: 1px solid #dee2e6; text-align: left;'>Name</th>
                    <td style='padding: 8px; border: 1px solid #dee2e6;'>{$data['name']}</td>
                </tr>
                <tr>
                    <th style='padding: 8px; border: 1px solid #dee2e6; text-align: left;'>Email</th>
                    <td style='padding: 8px; border: 1px solid #dee2e6;'>{$data['email']}</td>
                </tr>
                <tr style='background-color: #f8f9fa;'>
                    <th style='padding: 8px; border: 1px solid #dee2e6; text-align: left;'>Phone</th>
                    <td style='padding: 8px; border: 1px solid #dee2e6;'>{$data['phone']}</td>
                </tr>
                <tr>
                    <th style='padding: 8px; border: 1px solid #dee2e6; text-align: left;'>Event Date</th>
                    <td style='padding: 8px; border: 1px solid #dee2e6;'>{$data['event_date']}</td>
                </tr>
                <tr style='background-color: #f8f9fa;'>
                    <th style='padding: 8px; border: 1px solid #dee2e6; text-align: left;'>Details</th>
                    <td style='padding: 8px; border: 1px solid #dee2e6;'>{$data['details']}</td>
                </tr>
            </table>
            
            <p style='margin-top: 20px;'><strong>Submitted at:</strong> " . date('Y-m-d H:i:s') . "</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}