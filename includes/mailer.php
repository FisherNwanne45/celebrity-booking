<?php
require_once 'vendor/autoload.php';
require_once 'config.php';

// Send booking confirmation email
function sendBookingConfirmation($booking, $celebrity)
{
    $mail = new PHPMailer\PHPMailer\PHPMailer(true);

    try {
        // Server settings
        $mail->isSMTP();
        $mail->Host       = 'smtp.example.com'; // Set your SMTP server
        $mail->SMTPAuth   = true;
        $mail->Username   = 'your_email@example.com';
        $mail->Password   = 'your_password';
        $mail->SMTPSecure = PHPMailer\PHPMailer\PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        // Recipients
        $mail->setFrom('noreply@example.com', 'Celebrity Booking System');
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
                <li>Event Date: {$booking['event_date']}</li>
                <li>Your Message: {$booking['event_details']}</li>
            </ul>
            <p>Best regards,<br>Celebrity Booking Team</p>
        ";

        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Mailer Error: {$mail->ErrorInfo}");
        return false;
    }
}
