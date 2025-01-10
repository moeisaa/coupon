<?php
include('db_connect.php');

if (isset($_GET['route'])) {
    $route = $_GET['route'];
    $sql = "SELECT * FROM pages WHERE route = '$route'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        

        echo "Route is unavailable";
    } else {
        echo "Route is available";
    }
}
?>
