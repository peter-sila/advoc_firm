<?php
header('Content-Type: application/json');

$response = ['success' => false, 'message' => 'Unable to process appointment request.'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode($response);
    exit;
}

require_once __DIR__ . '/db_config.php';

$name = trim($_POST['name'] ?? '');
$email = trim($_POST['email'] ?? '');
$phone = trim($_POST['phone'] ?? '');
$location = trim($_POST['location'] ?? '');
$expertise = trim($_POST['expertise'] ?? '');
$meeting = trim($_POST['meeting'] ?? '');
$date = trim($_POST['date'] ?? '');
$hour = trim($_POST['hour'] ?? '');
$minute = trim($_POST['minute'] ?? '');
$am_pm = trim($_POST['ampm'] ?? '');
$message = trim($_POST['message'] ?? '');

if (!$name || !$email || !$phone || !$expertise || !$meeting || !$date || !$hour || !$minute || !$am_pm || !$message) {
    $response['message'] = 'Please complete all required fields.';
    echo json_encode($response);
    exit;
}

if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
    $response['message'] = 'Please provide a valid email address.';
    echo json_encode($response);
    exit;
}

$allowedFileTypes = ['application/pdf', 'image/jpeg', 'image/png'];
$documentPath = null;
if (!empty($_FILES['documents']['name'])) {
    $file = $_FILES['documents'];
    if ($file['error'] === UPLOAD_ERR_OK) {
        if ($file['size'] <= 5 * 1024 * 1024 && in_array($file['type'], $allowedFileTypes, true)) {
            $uploadDir = __DIR__ . '/../uploads';
            if (!is_dir($uploadDir)) {
                mkdir($uploadDir, 0755, true);
            }
            $fileName = basename($file['name']);
            $targetPath = $uploadDir . '/' . time() . '_' . preg_replace('/[^a-zA-Z0-9_\.-]/', '_', $fileName);
            if (move_uploaded_file($file['tmp_name'], $targetPath)) {
                $documentPath = basename($targetPath);
            }
        } else {
            $response['message'] = 'Uploaded document must be a PDF, JPG, or PNG and under 5MB.';
            echo json_encode($response);
            exit;
        }
    }
}

$time = sprintf('%02d:%02d %s', (int)$hour, (int)$minute, htmlspecialchars($am_pm, ENT_QUOTES));

$stmt = $conn->prepare('INSERT INTO appointments (name, email, phone, location, expertise, meeting_type, appointment_date, appointment_time, document_path, message, created_at) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW())');
$stmt->bind_param('ssssssssss', $name, $email, $phone, $location, $expertise, $meeting, $date, $time, $documentPath, $message);

if ($stmt->execute()) {
    $response['success'] = true;
    $response['message'] = 'Appointment request submitted successfully.';
} else {
    $response['message'] = 'Unable to save appointment. Please try again later.';
}

$stmt->close();
$conn->close();

echo json_encode($response);
