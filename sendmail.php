<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    
    $apiUrl = 'https://api.brevo.com/v3/smtp/email';

    $apiKey = getenv('SENDINBLUE_API_KEY');
    
    if(isset($_GET['cid'])){
        $cid = safe_output($_GET['cid']);
        $stmtr = $conn->prepare("SELECT * FROM client WHERE id=?");
        $stmtr->bind_param("i", $cid);
        $stmtr->execute();
        $resultr = $stmtr->get_result();
        $rowr = $resultr->fetch_assoc();
        
        $stmtreq = $conn->prepare("SELECT * FROM clients_requests WHERE client_id=? ORDER BY id DESC");
        $stmtreq->bind_param("i", $cid);
        $stmtreq->execute();
        $resultreq = $stmtreq->get_result();
        $rowreq = $resultreq->fetch_assoc();
        
        $fromEmail = 'hashemh101@hotmail.com';
        $toEmail = safe_output($rowr['email']);
        $subject = safe_output($rowreq['subject']);
        $htmlContent = '<p>مكتب محمد بني هاشم للمحاماة و الاستشارات القانونية<br><br>'.safe_output($rowreq['details']).'</p>';
        
        $ch = curl_init($apiUrl);
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json',
            'api-key: ' . $apiKey,
        ]);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
            'sender' => ['email' => $fromEmail],
            'to' => [['email' => $toEmail]],
            'subject' => $subject,
            'htmlContent' => $htmlContent,
        ]));
        
        $response = curl_exec($ch);
        
        if (curl_errno($ch)) {
            header("Location: clientsrequests.php?error=0");
            exit();
            echo 'Error:' . curl_error($ch);
        } else {
            header("Location: clientsrequests.php?mail=sent");
            exit();
            echo 'Response: ' . $response;
        }
        
        curl_close($ch);
    }
?>
