<?php
header('Content-Type: application/json');
include_once '../connection.php';
include_once '../login_check.php';
include_once '../permissions_check.php';
include_once '../AES256.php';

/** @var mysqli $conn */
/** @var array $row_permcheck */

$encryption_key = getenv('ENCRYPTION_KEY') ?: '';
if ($encryption_key === '') {
    $response = ['status' => 'error', 'message' => 'Encryption key not configured.'];
    echo json_encode($response);
    exit();
}
$aes = new AES256($encryption_key);

$response = ['status' => 'error', 'message' => 'An unknown error occurred.'];

if ($row_permcheck['clients_aperm'] != 1) {
    $response['message'] = 'Permission denied.';
    echo json_encode($response);
    exit();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $arname = trim($_POST['arname'] ?? '');
    $tel1 = trim($_POST['tel1'] ?? '');
    
    if (empty($arname) || empty($tel1)) {
        $response['message'] = 'Required fields are missing.';
        echo json_encode($response);
        exit();
    }
    
    $engname = trim($_POST['engname'] ?? '');
    $client_kind = trim($_POST['client_kind'] ?? '');
    $client_type = trim($_POST['client_type'] ?? '');
    $country = trim($_POST['country'] ?? '');
    $tel2 = trim($_POST['tel2'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $fax = trim($_POST['fax'] ?? '');
    $address = trim($_POST['address'] ?? '');
    
    $password = bin2hex(random_bytes(8));
    $encrypted_password = $aes->encrypt($password);

    $sql = "INSERT INTO client (arname, engname, client_kind, client_type, country, tel1, tel2, email, fax, address, password, perm) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, 1)";
    $stmt = $conn->prepare($sql);

    if ($stmt) {
        $stmt->bind_param("sssssssssss", $arname, $engname, $client_kind, $client_type, $country, $tel1, $tel2, $email, $fax, $address, $encrypted_password);
        if ($stmt->execute()) {
            $response['status'] = 'success';
            $response['message'] = 'Client added successfully!';
        } else {
            $response['message'] = 'Database error: ' . $stmt->error;
        }
        $stmt->close();
    } else {
        $response['message'] = 'Failed to prepare statement.';
    }
}

$conn->close();
echo json_encode($response);

