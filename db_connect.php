<?php
session_start(); // Start the session

error_reporting(E_ALL);
ini_set('display_errors', 1);
// Check if user is logged in
if (!isset($_SESSION['user_id'])) {
    // Redirect to login page if not logged in
    header("Location: login.php");
    exit;
}

// Read and parse .env file
$env = parse_ini_file(__DIR__ . '/.env');

// Database connection details
$servername = $env['DB_HOST'];
$username = $env['DB_USERNAME'];
$password = $env['DB_PASSWORD'];
$dbname = $env['DB_NAME'];


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Set the character set to UTF-8
$conn->set_charset("utf8");
?>
