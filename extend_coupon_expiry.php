<?php
include('db_connect.php');

// Define the number of days to extend the expiration date
$extension_days = 30;

// Get the current date
$current_date = date('Y-m-d');

// Get the page_id from the query parameter
$page_id = isset($_GET['page_id']) ? intval($_GET['page_id']) : 0;

// Query to select coupons where the expiration date is in the past
$query = "SELECT id, expire_date FROM coupons WHERE expire_date < ? AND expire_date IS NOT NULL";
$stmt = $conn->prepare($query);
$stmt->bind_param('s', $current_date);
$stmt->execute();
$result = $stmt->get_result();

$extended = false;
$error_message = "";

if ($result->num_rows > 0) {
    while ($coupon = $result->fetch_assoc()) {
        $coupon_id = $coupon['id'];

        // Calculate the new expiration date relative to the current date
        $new_expire_date = date('Y-m-d', strtotime($current_date . " + $extension_days days"));

        // Update the expiration date
        $update_query = "UPDATE coupons SET expire_date = ? WHERE id = ?";
        $update_stmt = $conn->prepare($update_query);
        $update_stmt->bind_param('si', $new_expire_date, $coupon_id);

        if ($update_stmt->execute()) {
            $extended = true;
        } else {
            $error_message = "Error updating Coupon ID $coupon_id: " . $update_stmt->error;
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
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        :root {
            --primary-color: #4a90e2;
            --success-color: #2ecc71;
            --danger-color: #e74c3c;
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

        .processing-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 8px 30px rgba(0, 0, 0, 0.08);
            padding: 2.5rem;
            max-width: 500px;
            width: 90%;
            text-align: center;
            position: relative;
            overflow: hidden;
        }

        .status-icon {
            font-size: 4rem;
            margin-bottom: 1.5rem;
            animation: fadeInDown 0.5s ease-out;
        }

        .success-icon {
            color: var(--success-color);
        }

        .error-icon {
            color: var(--danger-color);
        }

        .status-message {
            font-size: 1.25rem;
            font-weight: 500;
            color: var(--text-color);
            margin-bottom: 2rem;
            animation: fadeIn 0.5s ease-out;
            line-height: 1.5;
        }

        .loading-spinner {
            width: 60px;
            height: 60px;
            border: 4px solid var(--primary-color);
            border-right-color: transparent;
            border-radius: 50%;
            animation: spin 1s linear infinite;
            margin: 1.5rem auto;
        }

        .redirect-message {
            color: var(--text-color);
            opacity: 0.8;
            font-size: 1rem;
            margin-top: 1.5rem;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
        }

        .timer {
            background: var(--primary-color);
            color: white;
            border-radius: 50%;
            width: 30px;
            height: 30px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-weight: 600;
            font-size: 0.9rem;
            animation: pulse 1s infinite;
        }

        /* Progress bar at the bottom */
        .progress-bar {
            position: absolute;
            bottom: 0;
            left: 0;
            height: 4px;
            background: var(--primary-color);
            width: 100%;
            transform-origin: left;
            animation: progress 5s linear;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        @keyframes fadeInDown {
            from {
                opacity: 0;
                transform: translateY(-20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }

        @keyframes progress {
            from { transform: scaleX(1); }
            to { transform: scaleX(0); }
        }

        @media (max-width: 576px) {
            .processing-container {
                padding: 2rem 1.5rem;
                margin: 1rem;
            }

            .status-icon {
                font-size: 3rem;
            }

            .status-message {
                font-size: 1.1rem;
            }
        }
    </style>
</head>
<body>
    <div class="processing-container">
        <?php if ($extended): ?>
            <div class="status-icon success-icon">
                <i class="fas fa-check-circle"></i>
            </div>
            <div class="status-message">
                Your coupons have been successfully extended by <?php echo $extension_days; ?> days!
            </div>
        <?php else: ?>
            <div class="status-icon error-icon">
                <i class="fas fa-exclamation-circle"></i>
            </div>
            <div class="status-message">
                <?php echo htmlspecialchars($error_message); ?>
            </div>
        <?php endif; ?>

        <div class="loading-spinner"></div>
        
        <div class="redirect-message">
            <i class="fas fa-sync-alt"></i>
            Redirecting in <span class="timer" id="timer">5</span>
        </div>

        <div class="progress-bar"></div>
    </div>

    <script>
        const pageId = <?php echo $page_id; ?>;
        let countdown = 5;
        const timerElement = document.getElementById('timer');
        
        const interval = setInterval(() => {
            countdown--;
            timerElement.textContent = countdown;
            
            if (countdown <= 0) {
                clearInterval(interval);
                // Add fade-out effect before redirect
                document.body.style.opacity = '0';
                document.body.style.transition = 'opacity 0.5s ease';
                
                setTimeout(() => {
                    if (pageId > 0) {
                        window.location.href = 'dashboard.php?page_id=' + pageId;
                    } else {
                        window.location.href = 'dashboard.php';
                    }
                }, 500);
            }
        }, 1000);
    </script>
</body>
</html>