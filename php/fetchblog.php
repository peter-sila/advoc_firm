<?php
header('Content-Type: application/json');
require_once __DIR__ . '/db_config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $offset = isset($_POST['offset']) ? (int) $_POST['offset'] : 0;
    $limit = isset($_POST['limit']) ? (int) $_POST['limit'] : 4;

    $stmt = $conn->prepare('SELECT id, title, content, image FROM blogs ORDER BY created_at DESC LIMIT ?, ?');
    $stmt->bind_param('ii', $offset, $limit);
    $stmt->execute();
    $result = $stmt->get_result();
    $posts = [];

    while ($row = $result->fetch_assoc()) {
        $row['excerpt'] = mb_substr(strip_tags($row['content']), 0, 120) . '...';
        $posts[] = $row;
    }

    echo json_encode(['success' => true, 'posts' => $posts]);
    $stmt->close();
    $conn->close();
    exit;
}

if (isset($_GET['id'])) {
    $blog_id = (int) $_GET['id'];
    $stmt = $conn->prepare('SELECT * FROM blogs WHERE id = ?');
    $stmt->bind_param('i', $blog_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $blog = $result->fetch_assoc();
    echo json_encode(['success' => true, 'blog' => $blog]);
    $stmt->close();
    $conn->close();
    exit;
}

echo json_encode(['success' => false, 'message' => 'Invalid request.']);
$conn->close();
