<?php
// Load Composer's autoloader, which is essential for modern PHP
require_once __DIR__ . '/vendor/autoload.php';

// Load the .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Set the default timezone
date_default_timezone_set('Asia/Dubai');

// Read database credentials from environment variables (from .env file)
// This is the key change: DB_HOST will be 'db', not 'localhost'
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

// Create the database connection
$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    // In a production app, you should log this error instead of showing it
    die('Database connection error: ' . $conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Security headers
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
?>
