<?php
include('db_connect.php');

// Get page_id from query parameter
$pageId = isset($_GET['page_id']) ? intval($_GET['page_id']) : 0;
if ($pageId <= 0) {
    die("Invalid Page ID.");
}

// Fetch coupons associated with the page
$stmt = $conn->prepare("SELECT * FROM coupons WHERE page_id = ?");
$stmt->bind_param("i", $pageId);
$stmt->execute();
$coupons = $stmt->get_result();

if ($coupons->num_rows > 0) {
    echo "<h1>Coupons for Page ID: $pageId</h1>";
    while ($coupon = $coupons->fetch_assoc()) {
        $coupon_title = htmlspecialchars($coupon['title']);
        $coupon_description = htmlspecialchars($coupon['description']);
        $coupon_details = htmlspecialchars($coupon['details']);
        $coupon_expire_date = htmlspecialchars($coupon['expire_date']);
        $coupon_photo = htmlspecialchars($coupon['photo']);
        
        echo "<div class='coupon'>
                <h3>$coupon_title</h3>
                <p><strong>Description:</strong> $coupon_description</p>
                <p><strong>Details:</strong> $coupon_details</p>
                <p><strong>Expire Date:</strong> $coupon_expire_date</p>";
        
        if ($coupon_photo) {
            echo "<img src='/path/to/photos/{$coupon_photo}' alt='Coupon Photo'>";
        }

        echo "</div>";
    }
} else {
    echo "<p>No coupons found for this page.</p>";
}

$stmt->close();
$conn->close();
?>
