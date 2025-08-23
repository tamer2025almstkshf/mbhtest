<?php
// Load Composer's autoloader, which is essential for modern PHP and loading packages.
require_once __DIR__ . '/vendor/autoload.php';

// Load the .env file from the project root.
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Set the application's default timezone.
date_default_timezone_set('Asia/Dubai');

// Read all database credentials from the secure .env file.
$servername = getenv('DB_HOST'); // This will correctly be 'db' for the Docker container.
$username   = getenv('DB_USER');
$password   = getenv('DB_PASS');
$dbname     = getenv('DB_NAME');

// Create the one and only database connection object.
$conn = new mysqli($servername, $username, $password, $dbname);

// Check for connection errors and stop execution if it fails.
if($conn->connect_error){
    // In production, this should be logged to a file, not shown to the user.
    die('Database Connection Error: ' . $conn->connect_error);
}

// Set the character set to handle Arabic text correctly.
$conn->set_charset("utf8mb4");

// Standard security headers to help protect against common web vulnerabilities.
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
?>
