<?php
header('Content-Type: application/json');
include_once '../connection.php';
include_once '../login_check.php';
include_once '../AES256.php';

$encryption_key = getenv('ENCRYPTION_KEY') ?: 'default_key';
$aes = new AES256($encryption_key);

$response = ['status' => 'error', 'message' => 'Invalid request method.'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // --- Data Collection and Sanitization ---
    $client_id = isset($_POST['client_id']) && !empty($_POST['client_id']) ? (int)$_POST['client_id'] : null;
    $arname = trim($_POST['arname'] ?? '');
    $engname = trim($_POST['engname'] ?? '');
    $client_kind = trim($_POST['client_kind'] ?? '');
    $client_type = trim($_POST['client_type'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $tel1 = trim($_POST['tel1'] ?? '');
    $tel2 = trim($_POST['tel2'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $fax = trim($_POST['fax'] ?? '');
    $address = trim($_POST['address'] ?? '');

    // --- Validation ---
    if (empty($arname) || empty($client_kind) || empty($client_type) || empty($tel1)) {
        $response['message'] = 'Please fill in all required fields.';
        echo json_encode($response);
        exit();
    }

    if ($client_id) {
        // --- Update Existing Client ---
        $sql = "UPDATE client SET arname=?, engname=?, client_kind=?, client_type=?, country=?, tel1=?, tel2=?, email=?, fax=?, address=? WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssssi", $arname, $engname, $client_kind, $client_type, $country, $tel1, $tel2, $email, $fax, $address, $client_id);
    } else {
        // --- Insert New Client ---
        $password = bin2hex(random_bytes(8));
        $encrypted_password = $aes->encrypt($password);
        $sql = "INSERT INTO client (arname, engname, client_kind, client_type, country, tel1, tel2, email, fax, address, password, perm) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssssss", $arname, $engname, $client_kind, $client_type, $country, $tel1, $tel2, $email, $fax, $address, $encrypted_password);
    }

    if ($stmt) {
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = $client_id ? 'Client updated successfully!' : 'Client added successfully!';
        } else {
            $response['message'] = 'Database error: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = 'Database prepare error: ' . $conn->error;
    }
}

echo json_encode($response);
