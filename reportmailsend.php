<?php
    include_once 'connection.php';
    include_once 'login_check.php';
    include_once 'safe_output.php';
    include_once 'errorscheck.php';
    
    $apiUrl = 'https://api.brevo.com/v3/smtp/email';

    $apiKey = getenv('SENDINBLUE_API_KEY');
    
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
        foreach ($participantsArray as $participantid) {
            $stmt = $conn->prepare("SELECT email FROM user WHERE id=?");
            $stmt->bind_param("i", $participantid);
            $stmt->execute();
            $result = $stmt->get_result();
            $row = $result->fetch_assoc();
            $stmt->close();

            $toEmail = safe_output($row['email'] ?? '');
            if (empty($toEmail)) {
                continue;
            }

            $fromEmail = 'hashemh101@hotmail.com';
            $subject = 'Meeting report';
            $htmlContent = '<p>مكتب محمد بني هاشم للمحاماة و الاستشارات القانونية<br><br>تم كتابة محضر الاجتماع, يرجى الضغط على الرابط ادناه لمراجعة محتوى المحضر :</p>' .
                '<p><a href="https://mbhtest.com/meeting_report.php?id=' . $meetingid . '&edit=1&rid=' . $id . '">عرض المحضر</a></p>';

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
                curl_close($ch);
                continue;
            }
            curl_close($ch);
        }

        header("Location: meetings.php?mail=sent");
        exit();
    }
?>
