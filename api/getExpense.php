<?php
header('Content-Type: application/json');
include_once '../connection.php';
include_once '../login_check.php';

$response = ['status' => 'error', 'message' => 'Invalid request.'];

if (isset($_GET['id'])) {
    $expense_id = (int)$_GET['id'];
    
    // Assuming you have an 'expenses' table
    $stmt = $conn->prepare("SELECT * FROM expenses WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $expense_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $expense = $result->fetch_assoc();
            if ($expense) {
                $response = ['status' => 'success', 'data' => $expense];
            } else {
                $response['message'] = 'Expense not found.';
            }
        }
        $stmt->close();
    }
}

echo json_encode($response);
