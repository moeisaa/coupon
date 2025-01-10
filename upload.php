<?php
$target_dir = "uploads/";
if (!file_exists($target_dir)) {
    mkdir($target_dir, 0777, true);
}

$response = array();
try {
    if (isset($_FILES['file']['name'])) {
        $file_name = $_FILES['file']['name'];
        $file_tmp = $_FILES['file']['tmp_name'];
        $file_type = $_FILES['file']['type'];
        $file_ext = strtolower(pathinfo($file_name, PATHINFO_EXTENSION));
        
        // Allow certain file formats
        $allowed = array("jpg", "jpeg", "png", "gif");
        if (!in_array($file_ext, $allowed)) {
            throw new Exception('Only JPG, JPEG, PNG & GIF files are allowed.');
        }

        // Generate unique filename
        $new_file_name = uniqid() . '.' . $file_ext;
        $target_file = $target_dir . $new_file_name;

        if (move_uploaded_file($file_tmp, $target_file)) {
            $response['location'] = $target_file; // URL to the uploaded file
            echo json_encode($response);
        } else {
            throw new Exception('Failed to upload file.');
        }
    }
} catch (Exception $e) {
    $response['error'] = $e->getMessage();
    echo json_encode($response);
}
?>