<?php
header('Content-Type: application/json');
include_once '../connection.php';
include_once '../login_check.php';
include_once '../permissions_check.php';

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic permission check (assuming similar permission names)
    if ($row_permcheck['invoices_aperm'] != 1 && empty($_POST['id'])) {
        $response['message'] = 'You do not have permission to add invoices.';
        echo json_encode($response);
        exit();
    }
     if ($row_permcheck['invoices_eperm'] != 1 && !empty($_POST['id'])) {
        $response['message'] = 'You do not have permission to edit invoices.';
        echo json_encode($response);
        exit();
    }

    $id = isset($_POST['id']) && !empty($_POST['id']) ? (int)$_POST['id'] : null;
    $client_id = (int)$_POST['client_id'];
    $issue_date = trim($_POST['issue_date']);
    $due_date = trim($_POST['due_date']);
    $total_amount = (float)$_POST['total_amount'];
    $status = trim($_POST['status']);
    $user_id = $_SESSION['id'];

    if (empty($issue_date) || empty($due_date) || empty($total_amount) || empty($status) || empty($client_id)) {
        $response['message'] = 'Please fill all required fields.';
    } else {
        if ($id) {
            // Update existing invoice
            $stmt = $conn->prepare("UPDATE invoices SET client_id=?, issue_date=?, due_date=?, total_amount=?, status=? WHERE id=?");
            $stmt->bind_param("issdsi", $client_id, $issue_date, $due_date, $total_amount, $status, $id);
        } else {
            // Insert new invoice
            $stmt = $conn->prepare("INSERT INTO invoices (client_id, issue_date, due_date, total_amount, status, user_id) VALUES (?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("issdsi", $client_id, $issue_date, $due_date, $total_amount, $status, $user_id);
        }

        if ($stmt && $stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Invoice saved successfully!';
        } else {
            $response['message'] = 'Database error: ' . $conn->error;
        }
        if ($stmt) $stmt->close();
    }
}

echo json_encode($response);
