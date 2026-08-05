<?php
ini_set('display_errors', '0');
ini_set('log_errors', '1');
error_reporting(E_ALL);
header('Content-Type: application/json; charset=UTF-8');

$response = ['success' => false, 'message' => 'Unable to process contact request.'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode($response);
    exit;
}

require_once __DIR__ . '/db_config.php';
require_once __DIR__ . '/mail_helper.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$subject = trim($_POST['subject'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$subject || !$message) {
    $response['message'] = 'Please complete all required fields.';
    echo json_encode($response);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = 'Please provide a valid email address.';
    echo json_encode($response);
    exit;
}

$stmt = $conn->prepare('INSERT INTO contacts (name, email, phone, subject, message, created_at) VALUES (?, ?, ?, ?, ?, NOW())');
$stmt->bind_param('sssss', $name, $email, $phone, $subject, $message);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Your message has been received. We will get back to you shortly.';

    $emailSubject = 'New Contact Message from ' . $name;
    $emailBody = buildAdminNotificationBody('New Contact Form Submission', [
        'Name' => $name,
        'Email' => $email,
        'Phone' => $phone ?: 'N/A',
        'Subject' => $subject,
        'Message' => $message,
    ]);

    $mailSent = sendAdminNotificationEmail($emailSubject, $emailBody, $email);
    if (!$mailSent) {
        error_log('Contact: notification email failed for ' . $email);
        // keep success = true (data stored) but inform user that notification failed
        $response['message'] = 'Your message has been received, but we could not send a notification email. We will follow up manually.';
    }
} else {
    $response['message'] = 'Unable to save your message at this time. Please try again later.';
}

$stmt->close();
$conn->close();

echo json_encode($response);
