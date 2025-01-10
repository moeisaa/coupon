<?php
include('db_connect.php');

// Check if user is logged in
session_start();
if (!isset($_SESSION['user_id'])) {
    header('Content-Type: application/json');
    die(json_encode(['success' => false, 'message' => 'Not authorized']));
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    header('Content-Type: application/json');
    
    $pageId = isset($_POST['page_id']) ? (int)$_POST['page_id'] : 0;
    $tag = isset($_POST['tag']) ? trim($_POST['tag']) : '';
    
    if ($pageId > 0) {
        // Prepare statement to prevent SQL injection
        $stmt = $conn->prepare("UPDATE pages SET tag = ? WHERE id = ?");
        if ($stmt) {
            $stmt->bind_param("si", $tag, $pageId);
            
            if ($stmt->execute()) {
                echo json_encode(['success' => true]);
            } else {
                echo json_encode(['success' => false, 'message' => 'Database error: ' . $stmt->error]);
            }
            $stmt->close();
        } else {
            echo json_encode(['success' => false, 'message' => 'Failed to prepare statement']);
        }
    } else {
        echo json_encode(['success' => false, 'message' => 'Invalid page ID']);
    }
} else {
    header('Content-Type: application/json');
    echo json_encode(['success' => false, 'message' => 'Invalid request method']);
}
$conn->close();
?>