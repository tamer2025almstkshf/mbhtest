<?php
header('Content-Type: application/json');
include_once '../connection.php';
include_once '../login_check.php';
include_once '../permissions_check.php';

/** @var mysqli $conn */
/** @var array $row_permcheck */

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

    $sql = "UPDATE client SET arname = ?, engname = ?, client_kind = ?, client_type = ?, country = ?, tel1 = ?, tel2 = ?, email = ?, fax = ?, address = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param(
            "ssssssssssi",
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
            $client_id
        );

        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Client updated successfully!';
        } else {
            $response['message'] = 'Database error on execute.';
        }
        $stmt->close();
    } else {
        $response['message'] = 'Database error on prepare.';
    }
} else {
    $response['message'] = 'Invalid request method or missing data.';
}

echo json_encode($response);
$conn->close();

