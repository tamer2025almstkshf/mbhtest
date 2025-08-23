<?php
header('Content-Type: application/json');
include_once '../connection.php';
include_once '../login_check.php';
include_once '../permissions_check.php';
include_once '../AES256.php';

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if ($row_permcheck['clients_aperm'] != 1) {
    $response['message'] = 'Permission denied.';
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $arname = trim($_POST['arname']);
    $tel1 = trim($_POST['tel1']);

    if (empty($arname) || empty($tel1)) {
        $response['message'] = 'Required fields are missing.';
    } else {
        $password = bin2hex(random_bytes(8));
        $encrypted_password = openssl_encrypt($password, $cipher, $key, $options, $iv);

        $sql = "INSERT INTO client (arname, engname, client_kind, client_type, country, tel1, tel2, email, fax, address, password, perm) 
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
        
        $stmt = $conn->prepare($sql);
        $stmt->bind_param(
            "sssssssssss",
            $arname,
            $_POST['engname'],
            $_POST['client_kind'],
            $_POST['client_type'],
            $_POST['country'],
            $tel1,
            $_POST['tel2'],
            $_POST['email'],
            $_POST['fax'],
            $_POST['address'],
            $encrypted_password
        );

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Client added successfully!';
            $response['new_client_id'] = $conn->insert_id;
        } else {
            $response['message'] = 'Database error: ' . $stmt->error;
        }
        $stmt->close();
    }
} else {
    $response['message'] = 'Invalid request method.';
}

$conn->close();
echo json_encode($response);
