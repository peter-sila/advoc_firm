<?php
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Unable to process contact request.'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode($response);
    exit;
}

require_once __DIR__ . '/db_config.php';

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
} else {
    $response['message'] = 'Unable to save your message at this time. Please try again later.';
}

$stmt->close();
$conn->close();

echo json_encode($response);
