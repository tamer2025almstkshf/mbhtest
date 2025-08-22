<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    include_once 'errorscheck.php';
    
    $apiUrl = 'https://api.brevo.com/v3/smtp/email';
    
    $apiKey = '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';
    
    if(isset($_GET['id'])){
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
        }
        
        $response = curl_exec($ch);
        if (curl_errno($ch)) {
            header("Location: meetings.php?error=0");
            exit();
            echo 'Error:' . curl_error($ch);
        } else {
            header("Location: meetings.php?mail=sent");
            exit();
            echo 'Response: ' . $response;
        }
        
        curl_close($ch);
    }
?>
