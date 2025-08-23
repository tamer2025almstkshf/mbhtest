<?php
header('Content-Type: application/json');
include_once '../connection.php';
include_once '../login_check.php';
include_once '../permissions_check.php';

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if ($row_permcheck['clients_rperm'] != 1) {
    $response['message'] = 'Permission denied.';
    echo json_encode($response);
    exit();
}

if (isset($_GET['id'])) {
    $client_id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT id, arname, engname, client_kind, client_type, country, tel1, tel2, email, fax, address FROM client WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $client_id);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($client = $result->fetch_assoc()) {
            $response['status'] = 'success';
            $response['data'] = $client;
        } else {
            $response['message'] = 'Client not found.';
        }
        $stmt->close();
    } else {
        $response['message'] = 'Database error on prepare.';
    }
} else {
    $response['message'] = 'No client ID provided.';
}

echo json_encode($response);
$conn->close();
