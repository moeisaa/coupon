<?php
include('../db_connect.php');

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    
    // First update any pages using this footer to use null
    $conn->query("UPDATE pages SET footer_id = NULL WHERE footer_id = $id");
    
    // Then delete the footer
    if ($conn->query("DELETE FROM footers WHERE id = $id")) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode(['success' => false, 'error' => $conn->error]);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'No ID provided']);
}