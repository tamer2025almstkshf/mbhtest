<?php
header('Content-Type: application/json');
include_once '../connection.php';
include_once '../login_check.php';
include_once '../permissions_check.php';

/** @var mysqli $conn */
/** @var array $row_permcheck */

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if ($row_permcheck['clients_rperm'] != 1) {
    $response['message'] = 'Permission denied.';
    echo json_encode($response);
    exit();
}

if (isset($_GET['id'])) {
    $client_id = (int)$_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
    
    if($stmt) {
        $stmt->bind_param("i", $client_id);
        $stmt->execute();
        $result = $stmt->get_result();
        $client = $result->fetch_assoc();
        $stmt->close();

        if ($client) {
            $response['status'] = 'success';
            $response['data'] = $client;
        } else {
            $response['message'] = 'Client not found.';
        }
    } else {
         $response['message'] = 'Failed to prepare statement.';
    }
} else {
    $response['message'] = 'No client ID provided.';
}

$conn->close();
echo json_encode($response);

