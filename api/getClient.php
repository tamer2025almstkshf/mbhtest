<?php
header('Content-Type: application/json');
include_once '../connection.php';
include_once '../login_check.php';

$response = ['status' => 'error', 'message' => 'Invalid Request'];

if (isset($_GET['id'])) {
    $client_id = (int)$_GET['id'];
    
    $stmt = $conn->prepare("SELECT * FROM client WHERE id = ?");
    $stmt->bind_param("i", $client_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($client = $result->fetch_assoc()) {
        $response['status'] = 'success';
        $response['data'] = $client;
        unset($response['message']);
    } else {
        $response['message'] = 'Client not found.';
    }
    $stmt->close();
}

$conn->close();
echo json_encode($response);
