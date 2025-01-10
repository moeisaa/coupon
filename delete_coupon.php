<?php
include('db_connect.php');

// Check if the ID is provided in the URL
if (isset($_GET['id'])) {
    $id = intval($_GET['id']);

    // Fetch the coupon's page_id before deleting
    $stmt = $conn->prepare("SELECT page_id FROM coupons WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $stmt->bind_result($page_id);
    $stmt->fetch();
    $stmt->close();

    // Delete the coupon from the database
    $stmt = $conn->prepare("DELETE FROM coupons WHERE id = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        // Redirect to the dashboard page
        header("Location: dashboard.php?page_id=" . htmlspecialchars($page_id));
        exit;
    } else {
        echo "<div class='alert alert-danger text-center' role='alert'>Error deleting coupon: " . $stmt->error . "</div>";
    }

    $stmt->close();
    $conn->close();
} else {
    echo "<div class='alert alert-danger text-center' role='alert'>No coupon ID provided.</div>";
}
?>
