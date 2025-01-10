<?php
include('db_connect.php'); // Include your database connection file

// Check if the 'id' parameter is set
if (isset($_GET['id'])) {
    $pageId = intval($_GET['id']); // Ensure the ID is an integer

    // Prepare and execute the SQL statement to get the route based on the ID
    $sql = "SELECT route FROM pages WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("i", $pageId);
        $stmt->execute();
        $stmt->bind_result($route);
        $stmt->fetch();
        $stmt->close();

        if ($route) {
            // Prepare and execute the SQL statement to delete the page
            $sql = "DELETE FROM pages WHERE id = ?";
            $stmt = $conn->prepare($sql);

            if ($stmt) {
                $stmt->bind_param("i", $pageId);
                $stmt->execute();

                if ($stmt->affected_rows > 0) {
                    // Optional: Delete the QR code file if it exists
                    $qrCodeDir = 'qrcodes/';
                    $qrCodeFile = $qrCodeDir . $pageId . '.png';
                    
                    if (is_file($qrCodeFile)) {
                        unlink($qrCodeFile); // Delete the QR code file
                    }

                    // Delete associated HTML file if it exists
                    $htmlFileDir = 'static_pages/';
                    $htmlFile = $htmlFileDir . $route . '.html';
                    
                    if (is_file($htmlFile)) {
                        unlink($htmlFile); // Delete the HTML file
                    }

                    // Delete associated photos if they exist
                    $photoDir = 'static_pages/';
                    $photoFiles = [
                        $photoDir . $logo . '.jpg',
                        $photoDir . $logo . '.png'
                        // Add other extensions if necessary
                    ];

                    foreach ($photoFiles as $photoFile) {
                        if (is_file($photoFile)) {
                            unlink($photoFile); // Delete the photo
                        }
                    }

                    // Redirect to a confirmation page or back to the list of pages
                    header("Location: admin.php?msg=Page deleted successfully");
                    exit;
                } else {
                    echo "Error: Page could not be deleted.";
                }

                $stmt->close();
            } else {
                echo "Error: Could not prepare statement.";
            }
        } else {
            echo "Error: No route found for the given ID.";
        }
    } else {
        echo "Error: Could not prepare statement to fetch route.";
    }
} else {
    echo "Error: No page ID specified.";
}

$conn->close(); // Close the database connection
?>
