<?php
// Load Composer's autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Load environment variables from .env file
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

date_default_timezone_set('Asia/Dubai');

// Read database credentials from environment variables
$servername = getenv('DB_HOST');
$username = getenv('DB_USER');
$password = getenv('DB_PASS');
$dbname = getenv('DB_NAME');

$conn = new mysqli($servername, $username, $password, $dbname);
if($conn->connect_error){
    // In a real app, you should log this error, not just die
    die('error connecting to the database'.$conn->connect_error);
}
$conn->set_charset("utf8mb4");

// Security headers
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");
header("X-Frame-Options: SAMEORIGIN");
?>
