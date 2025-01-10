<?php
$qrCodeDir = 'qrcodes/';

// Ensure the directory exists
if (is_dir($qrCodeDir)) {
    $files = glob($qrCodeDir . '*'); // Get all files in the directory

    // Delete each file
    foreach ($files as $file) {
        if (is_file($file)) {
            unlink($file); // Delete file
        }
    }
}
?>
