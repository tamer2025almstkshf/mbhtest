<?php
// Start the session at the very beginning
session_start();

// Enable error reporting for debugging
// In production, this should be turned off or logged to a file
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Include Composer's autoloader
require_once __DIR__ . '/vendor/autoload.php';

// Include the new helpers file
require_once __DIR__ . '/src/helpers.php';

// Include the one and only database connection
// This also loads Composer's autoloader and the .env file
include_once 'connection.php';

// Include core libraries
include_once 'safe_output.php';
include_once 'AES256.php';

// --- Global User and Permission State ---
$logged_in = isset($_SESSION['id']);
$user_id = $_SESSION['id'] ?? 0;
$row_permcheck = [];
$admin = 0;

if ($logged_in) {
    $stmt = $conn->prepare("SELECT * FROM user WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $row_permcheck = $result->fetch_assoc();
        $stmt->close();

        if ($row_permcheck) {
            $admin = $row_permcheck['admin'] ?? 0;
        } else {
            // Handle cases where user in session doesn't exist in DB
            session_destroy();
            header("Location: login.php");
            exit();
        }
    }
}
// For pages that don't require a login, this script can still be included,
// but $logged_in will be false. We can add login checks within the pages themselves.
