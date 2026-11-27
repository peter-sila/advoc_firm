<?php


use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Admin notification recipients and sender details.
define('ADMIN_NOTIFICATION_EMAIL', '');
define('ADMIN_NOTIFICATION_FROM', '');
define('ADMIN_NOTIFICATION_NAME', 'WE GOT YOU Advocates');

// SMTP settings: replace these with your custom SMTP provider credentials.
define('SMTP_HOST', 'smtp.example.com');
define('SMTP_USERNAME', '');
define('SMTP_PASSWORD', '');
define('SMTP_PORT', 587);
define('SMTP_SECURE', 'tls');

function sendAdminNotificationEmail(string $subject, string $body, ?string $replyTo = null): bool
{
    $vendorAutoload = __DIR__ . '/../vendor/autoload.php';
    if (!file_exists($vendorAutoload)) {
        error_log('PHPMailer autoload missing: ' . $vendorAutoload);
        return false;
    }

    require_once $vendorAutoload;
    if (!class_exists(PHPMailer::class)) {
        error_log('PHPMailer class not found after autoload.');
        return false;
    }

    $replyTo = $replyTo && filter_var($replyTo, FILTER_VALIDATE_EMAIL) ? $replyTo : ADMIN_NOTIFICATION_FROM;

    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = SMTP_HOST;
        $mail->SMTPAuth = true;
        $mail->Username = SMTP_USERNAME;
        $mail->Password = SMTP_PASSWORD;
        $mail->SMTPSecure = SMTP_SECURE;
        $mail->Port = SMTP_PORT;

        $mail->setFrom(ADMIN_NOTIFICATION_FROM, ADMIN_NOTIFICATION_NAME);
        $mail->addAddress(ADMIN_NOTIFICATION_EMAIL, ADMIN_NOTIFICATION_NAME);
        $mail->addReplyTo($replyTo);

        $mail->isHTML(true);
        $mail->Subject = $subject;
        $mail->Body = $body;
        $mail->AltBody = strip_tags(str_replace(['<br>', '<br/>', '<br />'], "\n", $body));
        $mail->CharSet = 'UTF-8';

        return $mail->send();
    } catch (Exception $e) {
        error_log('Admin notification email failed: ' . $mail->ErrorInfo . ' | Exception: ' . $e->getMessage());
        return false;
    }
}

function buildAdminNotificationBody(string $title, array $fields): string
{
    $body = '<!DOCTYPE html><html><head><meta charset="UTF-8"><title>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</title></head><body>';
    $body .= '<h2>' . htmlspecialchars($title, ENT_QUOTES, 'UTF-8') . '</h2>';
    $body .= '<table cellpadding="6" cellspacing="0" border="0" style="border-collapse: collapse; font-family: Arial, sans-serif; width: 100%; max-width: 600px;">';
    foreach ($fields as $label => $value) {
        $body .= '<tr>';
        $body .= '<td style="font-weight: 700; vertical-align: top; width: 30%; color: #1a3d4d;">' . htmlspecialchars($label, ENT_QUOTES, 'UTF-8') . '</td>';
        $body .= '<td style="color: #333;">' . nl2br(htmlspecialchars($value, ENT_QUOTES, 'UTF-8')) . '</td>';
        $body .= '</tr>';
    }
    $body .= '</table>';
    $body .= '<p style="color: #666; font-size: 0.9rem;">This notification was generated automatically by the website.</p>';
    $body .= '</body></html>';
    return $body;
}
