<?php
// Create a new file named track_usage.php
// This handles the AJAX request when someone clicks the coupon button

header('Content-Type: application/json');
$env = parse_ini_file(__DIR__ . '/.env');

// Database connection details
$servername = $env['DB_HOST'];
$username = $env['DB_USERNAME'];
$password = $env['DB_PASSWORD'];
$dbname = $env['DB_NAME'];


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $coupon_id = $_POST['coupon_id'] ?? 0;
    
    if ($coupon_id > 0) {
        // First, check if we need to reset the daily count
        $sql = "UPDATE coupons 
                SET daily_uses = fake_initial_uses,
                    last_reset_date = CURRENT_DATE 
                WHERE id = ? 
                AND DATE(last_reset_date) < CURRENT_DATE";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $coupon_id);
        $stmt->execute();
        
        // Now increment the daily uses
        $sql = "UPDATE coupons 
                SET daily_uses = daily_uses + 1 
                WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param('i', $coupon_id);
        
        if ($stmt->execute()) {
            // Get the updated count
            $sql = "SELECT daily_uses FROM coupons WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param('i', $coupon_id);
            $stmt->execute();
            $result = $stmt->get_result();
            $count = $result->fetch_assoc()['daily_uses'];
            
            echo json_encode(['success' => true, 'count' => $count]);
        } else {
            echo json_encode(['success' => false, 'error' => 'Failed to update count']);
        }
    } else {
        echo json_encode(['success' => false, 'error' => 'Invalid coupon ID']);
    }
} else {
    echo json_encode(['success' => false, 'error' => 'Invalid request method']);
}
?>