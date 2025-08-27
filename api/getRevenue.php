<?php
header('Content-Type: application/json');
include_once '../connection.php';
include_once '../login_check.php';

/** @var mysqli $conn */

$response = ['status' => 'error', 'message' => 'Invalid request.'];

if (isset($_GET['id'])) {
    $revenue_id = (int)$_GET['id'];
    
    // Assuming you have a 'revenues' table
    $stmt = $conn->prepare("SELECT * FROM revenues WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $revenue_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $revenue = $result->fetch_assoc();
            if ($revenue) {
                $response = ['status' => 'success', 'data' => $revenue];
            } else {
                $response['message'] = 'Revenue not found.';
            }
        }
        $stmt->close();
    }
}

echo json_encode($response);

