<?php
header('Content-Type: application/json');
include_once '../connection.php';
include_once '../login_check.php';
include_once '../permissions_check.php';

/** @var mysqli $conn */
/** @var array $row_permcheck */

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic permission check
    if ($row_permcheck['accrevenues_aperm'] != 1 && empty($_POST['id'])) {
        $response['message'] = 'You do not have permission to add revenues.';
        echo json_encode($response);
        exit();
    }
     if ($row_permcheck['accrevenues_eperm'] != 1 && !empty($_POST['id'])) { // Assuming a separate edit permission
        $response['message'] = 'You do not have permission to edit revenues.';
        echo json_encode($response);
        exit();
    }

    $id = isset($_POST['id']) && !empty($_POST['id']) ? (int)$_POST['id'] : null;
    $date = trim($_POST['date']);
    $amount = (float)$_POST['amount'];
    $source = trim($_POST['source']);
    $description = trim($_POST['description']);
    $user_id = $_SESSION['id'];

    if (empty($date) || empty($amount) || empty($source)) {
        $response['message'] = 'Please fill all required fields.';
    } else {
        if ($id) {
            // Update existing revenue
            $stmt = $conn->prepare("UPDATE revenues SET revenue_date=?, amount=?, source=?, description=? WHERE id=?");
            $stmt->bind_param("sdssi", $date, $amount, $source, $description, $id);
        } else {
            // Insert new revenue
            $stmt = $conn->prepare("INSERT INTO revenues (revenue_date, amount, source, description, user_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sdssi", $date, $amount, $source, $description, $user_id);
        }

        if ($stmt && $stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Revenue saved successfully!';
        } else {
            $response['message'] = 'Database error: ' . $conn->error;
        }
        if ($stmt) $stmt->close();
    }
}

echo json_encode($response);

