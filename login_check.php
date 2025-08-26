<?php
// Only load the database connection if one has not already been provided.
// This enables tests to inject a mock connection.
if (!isset($conn)) {
    require_once __DIR__ . '/connection.php';
}

// Start the session only for web requests to avoid header issues during CLI tests.
if (PHP_SAPI !== 'cli' && session_status() === PHP_SESSION_NONE) {
    session_start();
}

// If the user is not logged in, redirect for web requests; in CLI just stop further checks.
if (!isset($_SESSION['id'])) {
    if (PHP_SAPI !== 'cli') {
        header('Location: login_emp.php');
        exit();
    }
    return;
}

$sessionid = $_SESSION['id'];
$stmt = $conn->prepare('SELECT signin_perm FROM user WHERE id = ?');
$stmt->bind_param('i', $sessionid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Block access when the user is not permitted to sign in (only in web context).
if (PHP_SAPI !== 'cli' && (int)($row['signin_perm'] ?? 0) === 0) {
    $_SESSION = [];
    session_destroy();

    header('Location: login_emp.php?error=perm');
    exit();
}
?>
