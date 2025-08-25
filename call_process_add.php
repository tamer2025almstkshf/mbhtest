<?php
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'src/I18n.php';

I18n::load('translations/calls.yaml');

if ($row_permcheck['call_aperm'] != 1) {
    header('Location: clientsCalls.php?error=noperms');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- Data Collection and Sanitization ---
    $caller_name = trim($_POST['caller_name']);
    $caller_no = trim($_POST['caller_no']);
    $details = trim($_POST['details']);
    $action = trim($_POST['action']);
    $moved_to = !empty($_POST['moved_to']) ? (int)$_POST['moved_to'] : null;
    $branch = trim($_POST['branch']);
    $empid = $_SESSION['id']; // Logged-in user
    $timestamp = $empid . " <br> " . date("Y-m-d");

    // --- Validation ---
    if (empty($caller_name) || empty($caller_no)) {
        $error_message = urlencode(I18n::get('error_missing_fields'));
        header("Location: callAdd.php?error={$error_message}&branch=" . urlencode($branch));
        exit();
    }

    // --- Database Insertion ---
    $sql = "INSERT INTO clientsCalls 
            (caller_name, caller_no, details, action, moved_to, branch, timestamp) 
            VALUES (?, ?, ?, ?, ?, ?, ?)";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param(
            "ssssiss",
            $caller_name,
            $caller_no,
            $details,
            $action,
            $moved_to,
            $branch,
            $timestamp
        );

        if ($stmt->execute()) {
            // Success
            $success_message = urlencode(I18n::get('add_success'));
            header("Location: clientsCalls.php?status={$success_message}&branch=" . urlencode($branch));
        } else {
            // Database execution error
            $error_message = urlencode(I18n::get('error_db'));
            header("Location: callAdd.php?error={$error_message}&branch=" . urlencode($branch));
        }
        $stmt->close();
    } else {
        // SQL statement preparation error
        $error_message = urlencode(I18n::get('error_prepare_failed'));
        header("Location: callAdd.php?error={$error_message}&branch=" . urlencode($branch));
    }

    $conn->close();
    exit();
} else {
    // Redirect if accessed directly
    header('Location: clientsCalls.php');
    exit();
}