<?php

declare(strict_types=1);

use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/helpers.php';

function configured_mailer(): PHPMailer
{
    $mail = new PHPMailer(true);
    $mail->isSMTP();
    $mail->Host = site_setting('smtp_host', 'smtp.example.com');
    $mail->Port = (int) site_setting('smtp_port', '587');
    $mail->SMTPAuth = true;
    $mail->Username = site_setting('smtp_username', '');
    $mail->Password = site_setting('smtp_password', '');

    $encryption = strtolower((string) site_setting('smtp_encryption', 'tls'));
    if ($encryption === 'ssl') {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_SMTPS;
    } else {
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
    }

    $fromEmail = site_setting('smtp_from_email', ADMIN_EMAIL_FALLBACK);
    $fromName = site_setting('site_title', 'Spin to Win');
    $mail->setFrom($fromEmail, $fromName);
    $mail->isHTML(true);

    return $mail;
}

function send_submission_email(array $submission): bool
{
    try {
        $mail = configured_mailer();
        $adminEmail = site_setting('admin_email', ADMIN_EMAIL_FALLBACK) ?? ADMIN_EMAIL_FALLBACK;
        $mail->addAddress($adminEmail);
        $mail->Subject = 'New Spin to Win Submission';

        $mail->Body = sprintf(
            '<h3>New winner submitted details</h3>
            <p><strong>Name:</strong> %s</p>
            <p><strong>Email:</strong> %s</p>
            <p><strong>Phone:</strong> %s</p>
            <p><strong>Prize:</strong> %s</p>
            <p><strong>Date/Time:</strong> %s</p>',
            e($submission['full_name']),
            e($submission['email']),
            e($submission['phone']),
            e($submission['prize_name']),
            e($submission['created_at'])
        );

        $mail->AltBody = "New winner submitted details\n"
            . "Name: {$submission['full_name']}\n"
            . "Email: {$submission['email']}\n"
            . "Phone: {$submission['phone']}\n"
            . "Prize: {$submission['prize_name']}\n"
            . "Date/Time: {$submission['created_at']}";

        return $mail->send();
    } catch (Exception $e) {
        error_log('Email send failed: ' . $e->getMessage());
        return false;
    }
}
