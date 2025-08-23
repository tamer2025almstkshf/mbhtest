<?php
header('Content-Type: application/json');
include_once '../connection.php';
include_once '../login_check.php';

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['position_name'])) {
    $positionName = trim($_POST['position_name']);

    if (empty($positionName)) {
        $response['message'] = 'Position name cannot be empty.';
    } else {
        // Use prepared statements to prevent SQL injection
        $stmt = $conn->prepare("INSERT INTO positions (position_name) VALUES (?)");
        if ($stmt) {
            $stmt->bind_param("s", $positionName);
            if ($stmt->execute()) {
                $response['status'] = 'success';
                $response['message'] = 'Position added successfully!';
                // Return the new position ID for potentially adding to the dropdown
                $response['new_position_id'] = $conn->insert_id;
            } else {
                $response['message'] = 'Error executing statement: ' . $stmt->error;
            }
            $stmt->close();
        } else {
            $response['message'] = 'Error preparing statement: ' . $conn->error;
        }
    }
} else {
    $response['message'] = 'Invalid request method or missing data.';
}

$conn->close();
echo json_encode($response);
