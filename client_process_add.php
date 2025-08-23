<?php
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';
include_once 'AES256.php'; // For password encryption

// Check for permission to add clients
if ($row_permcheck['clients_aperm'] != 1) {
    header('Location: clients.php?error=noperms');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- Data Collection and Sanitization ---
    $arname = trim($_POST['arname']);
    $engname = trim($_POST['engname']);
    $client_kind = trim($_POST['client_kind']);
    $client_type = trim($_POST['client_type']);
    $country = trim($_POST['country']);
    $tel1 = trim($_POST['tel1']);
    $tel2 = trim($_POST['tel2']);
    $email = trim($_POST['email']);
    $fax = trim($_POST['fax']);
    $address = trim($_POST['address']);

    // --- Validation ---
    if (empty($arname) || empty($client_kind) || empty($client_type) || empty($tel1)) {
        header('Location: clientAdd.php?error=missingfields');
        exit();
    }

    // --- Generate a random password for the new client ---
    $password = bin2hex(random_bytes(8)); // Create a 16-character random password
    $encrypted_password = openssl_encrypt($password, $cipher, $key, $options, $iv);

    // --- Database Insertion ---
    $sql = "INSERT INTO client 
            (arname, engname, client_kind, client_type, country, tel1, tel2, email, fax, address, password, perm) 
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)"; // New clients have login perm by default

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param(
            "sssssssssss",
            $arname,
            $engname,
            $client_kind,
            $client_type,
            $country,
            $tel1,
            $tel2,
            $email,
            $fax,
            $address,
            $encrypted_password
        );

        if ($stmt->execute()) {
            // Success
            header('Location: clients.php?status=added');
        } else {
            // Database execution error
            header('Location: clientAdd.php?error=dberror');
        }
        $stmt->close();
    } else {
        // SQL statement preparation error
        header('Location: clientAdd.php?error=preparefailed');
    }

    $conn->close();
    exit();
} else {
    // Redirect if accessed directly
    header('Location: clients.php');
    exit();
}
