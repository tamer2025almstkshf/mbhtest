<?php
header('Content-Type: application/json');
include_once '../connection.php';
include_once '../login_check.php';
include_once '../permissions_check.php'; // Important for security

/** @var mysqli $conn */
/** @var array $row_permcheck */

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Basic permission check
    if ($row_permcheck['accexpenses_aperm'] != 1 && empty($_POST['id'])) {
        $response['message'] = 'You do not have permission to add expenses.';
        echo json_encode($response);
        exit();
    }
     if ($row_permcheck['accexpenses_eperm'] != 1 && !empty($_POST['id'])) {
        $response['message'] = 'You do not have permission to edit expenses.';
        echo json_encode($response);
        exit();
    }

    $id = isset($_POST['id']) && !empty($_POST['id']) ? (int)$_POST['id'] : null;
    $date = trim($_POST['date']);
    $amount = (float)$_POST['amount'];
    $category = trim($_POST['category']);
    $description = trim($_POST['description']);
    $user_id = $_SESSION['id'];

    if (empty($date) || empty($amount) || empty($category)) {
        $response['message'] = 'Please fill all required fields.';
    } else {
        if ($id) {
            // Update existing expense
            $stmt = $conn->prepare("UPDATE expenses SET expense_date=?, amount=?, category=?, description=? WHERE id=?");
            $stmt->bind_param("sdssi", $date, $amount, $category, $description, $id);
        } else {
            // Insert new expense
            $stmt = $conn->prepare("INSERT INTO expenses (expense_date, amount, category, description, user_id) VALUES (?, ?, ?, ?, ?)");
            $stmt->bind_param("sdssi", $date, $amount, $category, $description, $user_id);
        }

        if ($stmt && $stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Expense saved successfully!';
        } else {
            $response['message'] = 'Database error: ' . $conn->error;
        }
        if ($stmt) $stmt->close();
    }
}

echo json_encode($response);

