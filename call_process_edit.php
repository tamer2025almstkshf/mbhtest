<?php
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';

if ($row_permcheck['call_eperm'] != 1) {
    header('Location: clientsCalls.php?error=noperms');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['call_id'])) {
    // --- Data Collection and Sanitization ---
    $call_id = (int)$_POST['call_id'];
    $caller_name = trim($_POST['caller_name']);
    $caller_no = trim($_POST['caller_no']);
    $details = trim($_POST['details']);
    $action = trim($_POST['action']);
    $moved_to = !empty($_POST['moved_to']) ? (int)$_POST['moved_to'] : null;

    // --- Validation ---
    if (empty($caller_name) || empty($caller_no)) {
        header('Location: callEdit.php?id=' . $call_id . '&error=missingfields');
        exit();
    }

    // --- Database Update ---
    $sql = "UPDATE clientsCalls SET
                caller_name = ?,
                caller_no = ?,
                details = ?,
                action = ?,
                moved_to = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param(
            "ssssii",
            $caller_name,
            $caller_no,
            $details,
            $action,
            $moved_to,
            $call_id
        );

        if ($stmt->execute()) {
            // Success
            header('Location: clientsCalls.php?status=updated');
        } else {
            // Database execution error
            header('Location: callEdit.php?id=' . $call_id . '&error=dberror');
        }
        $stmt->close();
    } else {
        // SQL statement preparation error
        header('Location: callEdit.php?id=' . $call_id . '&error=preparefailed');
    }

    $conn->close();
    exit();
} else {
    // Redirect if accessed directly
    header('Location: clientsCalls.php');
    exit();
}
