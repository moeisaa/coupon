<?php
include('../db_connect.php');

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_footer'])) {
    $name = $conn->real_escape_string($_POST['footer_name']);
    
    // Handle empty or malformed footer_links array
    if (isset($_POST['footer_links']) && is_array($_POST['footer_links'])) {
        $footer_links = array_filter($_POST['footer_links'], function($link) {
            return !empty($link['text']) && !empty($link['url']);
        });
        $footer_links = json_encode(array_values($footer_links));
    } else {
        $footer_links = json_encode([]);
    }
    
    $footer_note = $conn->real_escape_string($_POST['footer_note'] ?? '');
    $copyright_text = $conn->real_escape_string($_POST['copyright_text'] ?? '');
    $background_color = $conn->real_escape_string($_POST['background_color'] ?? '#FFFFFF');
    $is_default = isset($_POST['is_default']) ? 1 : 0;
    
    try {
        // Start transaction
        $conn->begin_transaction();
        
        if ($is_default) {
            // Reset all other footers to non-default
            $conn->query("UPDATE footers SET is_default = 0");
        }
        
        if (isset($_POST['footer_id']) && !empty($_POST['footer_id'])) {
            // Update existing footer
            $id = intval($_POST['footer_id']);
            $sql = "UPDATE footers SET 
                    name = ?, 
                    footer_links = ?,
                    footer_note = ?,
                    copyright_text = ?,
                    background_color = ?,
                    is_default = ?
                    WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssii", $name, $footer_links, $footer_note, $copyright_text, $background_color, $is_default, $id);
        } else {
            // Create new footer
            $sql = "INSERT INTO footers (name, footer_links, footer_note, copyright_text, background_color, is_default) 
                    VALUES (?, ?, ?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssi", $name, $footer_links, $footer_note, $copyright_text, $background_color, $is_default);
        }
        
        if ($stmt->execute()) {
            $conn->commit();
            header('Location: ../settings.php?success=1');
        } else {
            throw new Exception("Failed to execute statement");
        }
        
    } catch (Exception $e) {
        $conn->rollback();
        header('Location: ../settings.php?error=1&message=' . urlencode($e->getMessage()));
    }
    
    exit();
} else {
    header('Location: ../settings.php?error=1&message=Invalid request');
    exit();
}
?>