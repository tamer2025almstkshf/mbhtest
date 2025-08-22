<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    
    $instanceID = "instance104369";
    $apiToken = "3tlniap2j4mtxgnn";
    
    $cid = safe_output($_GET['cid']);
    $stmtus = $conn->prepare("SELECT * FROM client WHERE id=?");
    $stmtus->bind_param("i", $cid);
    $stmtus->execute();
    $resultus = $stmtus->get_result();
    $rowus = $resultus->fetch_assoc();
    
    $stmtreq = $conn->prepare("SELECT * FROM clients_requests WHERE client_id=? ORDER BY id DESC");
    $stmtreq->bind_param("i", $cid);
    $stmtreq->execute();
    $resultreq = $stmtreq->get_result();
    $rowreq = $resultreq->fetch_assoc();

    $details = safe_output($rowreq['details']);
    $telno = safe_output($rowus['tel1']);
    $recipient = safe_output($telno);
    $message = "*`مكتب محمد بني هاشم للمحاماة و الاستشارات القانونية`*\n\n*$details*";
    $url = "https://api.ultramsg.com/$instanceID/messages/chat";
    
    $data = [
        'token' => $apiToken,
        'to' => $recipient,
        'body' => $message,
    ];
    
    $ch = curl_init($url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        "Authorization: Bearer $apiToken",
    ]);
    $response = curl_exec($ch);
    
    if ($response === false) {
        die('Error: ' . curl_error($ch));
    }
    curl_close($ch);
    
    if(isset($_GET['addmore']) && $_GET['addmore'] === '1'){
        header("Location: clientsrequests.php?whats=sent&addmore=1");
    }else{
        header("Location: clientsrequests.php?whats=sent");
    }
    exit();
?>