<?php
require_once __DIR__ . '/connection.php';

session_start();

if (!isset($_SESSION['id'])) {
    header('Location: login_emp.php');
    exit();
}

$sessionid = $_SESSION['id'];
$stmt = $conn->prepare('SELECT signin_perm FROM user WHERE id = ?');
$stmt->bind_param('i', $sessionid);
$stmt->execute();
$result = $stmt->get_result();
$row = $result->fetch_assoc();

// Block access when the user is not permitted to sign in.
if ((int)($row['signin_perm'] ?? 0) === 0) {
    $_SESSION = [];
    session_destroy();

    header('Location: login_emp.php?error=perm');
    exit();
}
?>
