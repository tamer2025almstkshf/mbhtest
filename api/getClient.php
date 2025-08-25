<?php
header('Content-Type: application/json');
include_once '../connection.php';
include_once '../login_check.php';

$response = ['status' => 'error', 'message' => 'Invalid request.'];

if (isset($_GET['id'])) {
    $client_id = (int)$_GET['id'];
    
    $stmt = $conn->prepare("SELECT id, arname, engname, client_kind, client_type, country, tel1, tel2, email, fax, address FROM client WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $client_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $client = $result->fetch_assoc();
            if ($client) {
                $response = ['status' => 'success', 'data' => $client];
            } else {
                $response['status'] = 'error';
                $response['message'] = 'Client not found.';
            }
        } else {
            $response['message'] = 'Database execute error.';
        }
        $stmt->close();
    } else {
        $response['message'] = 'Database prepare error.';
    }
}

echo json_encode($response);
