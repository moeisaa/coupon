<?php
include('db_connect.php');

// Function to safely delete files with same name but different extensions
function deleteRelatedFiles($filename, $directory) {
    // Get base name without extension
    $baseName = pathinfo($filename, PATHINFO_FILENAME);
    
    // Get all files in directory
    $files = scandir($directory);
    
    foreach ($files as $file) {
        // Skip . and .. and php files
        if ($file === '.' || $file === '..' || pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            continue;
        }
        
        // If file starts with the base name, delete it
        if (strpos($file, $baseName) === 0) {
            $fullPath = $directory . '/' . $file;
            if (is_file($fullPath)) {
                @unlink($fullPath);
            }
        }
    }
}

// Delete orphaned coupons (coupons without a valid page)
$orphaned_deleted = false;
$delete_orphaned_query = "DELETE c FROM coupons c 
                         LEFT JOIN pages p ON c.page_id = p.id 
                         WHERE p.id IS NULL";
$orphaned_stmt = $conn->prepare($delete_orphaned_query);
if ($orphaned_stmt->execute()) {
    $orphaned_count = $orphaned_stmt->affected_rows;
    if ($orphaned_count > 0) {
        $orphaned_deleted = true;
    }
}
$orphaned_stmt->close();

// Safely delete HTML files and related files in static_pages directory
$static_pages_dir = 'static_pages';
$files_deleted = false;

// Check if directory exists
if (!is_dir($static_pages_dir)) {
    $error_message = "Static pages directory not found";
} else {
    $files = scandir($static_pages_dir);

    foreach ($files as $file) {
        // Skip . and .. and php files
        if ($file === '.' || $file === '..' || pathinfo($file, PATHINFO_EXTENSION) === 'php') {
            continue;
        }
        
        // If it's an HTML file
        if (pathinfo($file, PATHINFO_EXTENSION) === 'html') {
            // Delete the HTML file
            if (@unlink($static_pages_dir . '/' . $file)) {
                $files_deleted = true;
            }
            
            // Delete related files
            deleteRelatedFiles(pathinfo($file, PATHINFO_FILENAME), $static_pages_dir);
        }
    }
}

// Continue with coupon extension code...
$extension_days = 30;
$current_date = date('Y-m-d');

$query = "SELECT id, expire_date FROM coupons WHERE expire_date < ? AND expire_date IS NOT NULL";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $current_date);
$stmt->execute();
$result = $stmt->get_result();

$coupons_extended = false;
$error_message = "";

if ($result->num_rows > 0) {
    while ($coupon = $result->fetch_assoc()) {
        $coupon_id = $coupon['id'];
        $new_expire_date = date('Y-m-d', strtotime($current_date . " + $extension_days days"));

        $update_query = "UPDATE coupons SET expire_date = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param('si', $new_expire_date, $coupon_id);

        if ($update_stmt->execute()) {
            $coupons_extended = true;
        } else {
            $error_message .= "Error updating Coupon ID $coupon_id: " . $update_stmt->error . "<br>";
        }

        $update_stmt->close();
    }
} else {
    $error_message = "No expired coupons found.";
}

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Extend Coupons</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --success-color: #28a745;
            --danger-color: #dc3545;
            --text-color: #2c3e50;
            --bg-color: #f5f7fb;
        }

        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: var(--bg-color);
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .confirmation-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.08);
            padding: 2.5rem;
            max-width: 500px;
            width: 90%;
            text-align: center;
        }

        .status-icon {
            font-size: 3rem;
            margin-bottom: 1.5rem;
        }

        .success-icon {
            color: var(--success-color);
            animation: scaleIn 0.5s ease-out;
        }

        .error-icon {
            color: var(--danger-color);
            animation: shake 0.5s ease-in-out;
        }

        .status-message {
            font-size: 1.25rem;
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 1.5rem;
        }

        .loading-spinner {
            width: 3rem;
            height: 3rem;
            border: 0.25rem solid var(--primary-color);
            border-right-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 0 auto 1.5rem;
        }

        .timer-container {
            margin-bottom: 1.5rem;
            color: var(--text-color);
            font-size: 1.1rem;
        }

        #timer {
            font-weight: 600;
            color: var(--primary-color);
        }

        .return-btn {
            background-color: var(--primary-color);
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            font-weight: 500;
            text-decoration: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 0.5rem;
        }

        .return-btn:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(74, 144, 226, 0.25);
            color: white;
        }

        @keyframes scaleIn {
            0% { transform: scale(0); }
            60% { transform: scale(1.1); }
            100% { transform: scale(1); }
        }

        @keyframes shake {
            0%, 100% { transform: translateX(0); }
            25% { transform: translateX(-5px); }
            75% { transform: translateX(5px); }
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @media (max-width: 576px) {
            .confirmation-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .status-icon {
                font-size: 2.5rem;
            }

            .status-message {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <div class="confirmation-container">
        <?php if ($coupons_extended || $orphaned_deleted || $files_deleted): ?>
            <div class="status-icon success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="status-message text-success">
                <?php 
                $messages = [];
                if ($coupons_extended) $messages[] = "Coupons have been successfully extended";
                if ($orphaned_deleted) $messages[] = "Orphaned coupons have been removed";
                if ($files_deleted) $messages[] = "Files cleaned up";
                echo implode(", ", $messages) . "!";
                ?>
            </div>
        <?php elseif (!empty($error_message)): ?>
            <div class="status-icon error-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="status-message text-danger">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <div class="loading-spinner"></div>
        
        <div class="timer-container">
            Redirecting in <span id="timer">5</span> seconds...
        </div>

        <a href="admin.php" class="return-btn">
            <i class="fas fa-home"></i>
            Return to Home
        </a>
    </div>

    <script>
        // Enhanced countdown timer with smooth transition
        const timerElement = document.getElementById('timer');
        let countdown = 5;
        
        const interval = setInterval(() => {
            countdown--;
            timerElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(interval);
                // Add fade-out effect before redirect
                document.body.style.opacity = '0';
                document.body.style.transition = 'opacity 0.5s ease';
                setTimeout(() => {
                    window.location.href = 'admin.php';
                }, 500);
            }
        }, 1000);
    </script>
</body>
</html>