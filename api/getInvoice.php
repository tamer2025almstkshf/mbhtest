<?php
// Only send the JSON content type header if headers have not already been sent.
// This prevents warnings when running under CLI tools like PHPUnit.
if (!headers_sent()) {
    header('Content-Type: application/json');
}

// Include the database connection only if one has not been provided (e.g. by tests).
if (!isset($conn)) {
    include_once __DIR__ . '/../connection.php';
}

require __DIR__ . '/../login_check.php';

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
                $response['status'] = 'error';
                $response['message'] = 'Invoice not found.';
            }
        }
        $stmt->close();
    }
}

echo json_encode($response);
