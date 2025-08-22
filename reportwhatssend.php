<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    include_once 'AES256.php';
    
    $instanceID = "instance124551";
    $apiToken = "m5ja822mf3louwgf";
    
    $id = safe_output($_GET['id']);
    $stmtm = $conn->prepare("SELECT * FROM meetings_reports WHERE id=?");
    $stmtm->bind_param("i", $id);
    $stmtm->execute();
    $resultm = $stmtm->get_result();
    $rowm = $resultm->fetch_assoc();
    $stmtm->close();
    $meetingid = $rowm['meeting_id'];
    
    $stmte = $conn->prepare("SELECT * FROM events WHERE id=?");
    $stmte->bind_param("i", $meetingid);
    $stmte->execute();
    $resulte = $stmte->get_result();
    $rowe = $resulte->fetch_assoc();
    $stmte->close();
    $participants = $rowe['participants'];
    
    $participantsArray = explode(",", $participants);
    $message = "*`مكتب محمد بني هاشم للمحاماة و الاستشارات القانونية`*\n\n*تم كتابة محضر الاجتماع, يرجى الضغط على الرابط ادناه لمراجعة محتوى المحضر :*\nhttps://mbhtest.com/meeting_report.php?id=$meetingid&edit=1&rid=$id";
    
    foreach($participantsArray as $participantid){
        $stmt = $conn->prepare("SELECT * FROM user WHERE id=?");
        $stmt->bind_param("i", $participantid);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();
        $stmt->close();
        $telno = safe_output($row['tel1']);
        if (empty($telno)) {
            continue;
        }
        $decrypted_telno = openssl_decrypt($telno, $cipher, $key, $options, $iv);
        $recipient = safe_output($decrypted_telno);
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
    }
    
    header("Location: meetings.php?whats=sent");
    exit();
?>