<?php
header('Content-Type: application/json');
include_once '../connection.php';
include_once '../login_check.php';
include_once '../permissions_check.php';

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if ($row_permcheck['clients_eperm'] != 1) {
    $response['message'] = 'Permission denied.';
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['client_id'])) {
    $client_id = (int)$_POST['client_id'];
    $arname = trim($_POST['arname']);
    $tel1 = trim($_POST['tel1']);
    
    if (empty($arname) || empty($tel1)) {
        $response['message'] = 'Required fields are missing.';
        echo json_encode($response);
        exit();
    }
    
    $engname = trim($_POST['engname']);
    $client_kind = trim($_POST['client_kind']);
    $client_type = trim($_POST['client_type']);
    $country = trim($_POST['country']);
    $tel2 = trim($_POST['tel2']);
    $email = trim($_POST['email']);
    $fax = trim($_POST['fax']);
    $address = trim($_POST['address']);

    $sql = "UPDATE client SET arname = ?, engname = ?, client_kind = ?, client_type = ?, country = ?, tel1 = ?, tel2 = ?, email = ?, fax = ?, address = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("ssssssssssi", $arname, $engname, $client_kind, $client_type, $country, $tel1, $tel2, $email, $fax, $address, $client_id);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Client updated successfully!';
        } else {
            $response['message'] = 'Database error: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = 'Failed to prepare statement.';
    }
}

$conn->close();
echo json_encode($response);
