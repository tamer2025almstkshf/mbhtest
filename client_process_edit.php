<?php
include_once 'connection.php';
include_once 'login_check.php';
include_once 'permissions_check.php';

// Check for permission to edit clients
if ($row_permcheck['clients_eperm'] != 1) {
    header('Location: clients.php?error=noperms');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['client_id'])) {
    // --- Data Collection and Sanitization ---
    $client_id = (int)$_POST['client_id'];
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
        header('Location: clientEdit.php?id=' . $client_id . '&error=missingfields');
        exit();
    }

    // --- Database Update ---
    $sql = "UPDATE client SET
                arname = ?,
                engname = ?,
                client_kind = ?,
                client_type = ?,
                country = ?,
                tel1 = ?,
                tel2 = ?,
                email = ?,
                fax = ?,
                address = ?
            WHERE id = ?";

    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param(
            "ssssssssssi",
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
            $client_id
        );

        if ($stmt->execute()) {
            // Success
            header('Location: clients.php?status=updated');
        } else {
            // Database execution error
            header('Location: clientEdit.php?id=' . $client_id . '&error=dberror');
        }
        $stmt->close();
    } else {
        // SQL statement preparation error
        header('Location: clientEdit.php?id=' . $client_id . '&error=preparefailed');
    }

    $conn->close();
    exit();
} else {
    // Redirect if accessed directly
    header('Location: clients.php');
    exit();
}
