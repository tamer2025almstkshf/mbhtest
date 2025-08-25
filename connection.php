<?php

// Use Composer's autoloader for modern dependency management.
require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables from the .env file.
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Set the default timezone for the application.
date_default_timezone_set('Asia/Dubai');

// Retrieve database credentials from environment variables.
$servername = $_ENV['DB_HOST'];
$username   = $_ENV['DB_USER'];
$password   = $_ENV['DB_PASS'];
$dbname     = $_ENV['DB_NAME'];

// Establish a single, reusable database connection.
$conn = new mysqli($servername, $username, $password, $dbname);

// Terminate the script with a clear error message if the connection fails.
if ($conn->connect_error) {
    // In a production environment, this error should be logged, not displayed.
    error_log('Database Connection Error: ' . $conn->connect_error);
    http_response_code(500);
    die('Unable to connect to the database.');
}

// Ensure the connection uses the UTF-8 character set for proper Arabic language support.
$conn->set_charset("utf8mb4");
