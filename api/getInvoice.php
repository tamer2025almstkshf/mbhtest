<?php
header('Content-Type: application/json');
include_once '../connection.php';
include_once '../login_check.php';

$response = ['status' => 'error', 'message' => 'Invalid request.'];

if (isset($_GET['id'])) {
    $invoice_id = (int)$_GET['id'];
    
    // Assuming an 'invoices' table
    $stmt = $conn->prepare("SELECT * FROM invoices WHERE id = ?");
    if ($stmt) {
        $stmt->bind_param("i", $invoice_id);
        if ($stmt->execute()) {
            $result = $stmt->get_result();
            $invoice = $result->fetch_assoc();
            if ($invoice) {
                $response = ['status' => 'success', 'data' => $invoice];
            } else {
                $response['message'] = 'Invoice not found.';
            }
        }
        $stmt->close();
    }
}

echo json_encode($response);
